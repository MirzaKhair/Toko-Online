<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentProof;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentProofTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    protected function createProductWithCart(): Product
    {
        $category = Category::create(['name' => 'Test Category', 'slug' => 'test-category']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 50000,
            'stock' => 100,
            'is_active' => true,
            'has_variants' => false,
        ]);

        $cart = [
            "{$product->id}" => [
                'product_id' => $product->id,
                'variant_id' => null,
                'product_name' => $product->name,
                'variant_name' => null,
                'sku' => null,
                'price' => $product->price,
                'quantity' => 2,
                'image' => null,
            ],
        ];

        session(['cart' => $cart]);

        return $product;
    }

    protected function withTrackingAccess(Order $order): static
    {
        return $this->withSession([
            "tracking_access.{$order->order_number}" => true,
        ]);
    }

    public function test_checkout_creates_order_with_unpaid_status(): void
    {
        $product = $this->createProductWithCart();

        $response = $this->post('/checkout', [
            'customer_name' => 'John Doe',
            'customer_phone' => '081234567890',
            'customer_address' => 'Jl. Test No. 1',
            'payment_method' => 'cash',
        ]);

        $order = Order::where('customer_phone', '081234567890')->first();

        $this->assertNotNull($order);
        $this->assertEquals('unpaid', $order->payment_status);
        $this->assertEquals('pending', $order->order_status);
        $this->assertNull($order->shipping_finalized_at);
    }

    public function test_qris_customer_can_upload_proof_after_confirmation(): void
    {
        $order = Order::factory()->qris()->confirmed()->create([
            'shipping_cost' => 10000,
            'total_amount' => 110000,
        ]);

        $file = UploadedFile::fake()->image('bukti.png', 200, 200);

        $response = $this->withTrackingAccess($order)->post("/pesanan/{$order->id}/bukti-bayar", [
            'proof' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('waiting_verification', $order->payment_status);

        $proof = PaymentProof::where('order_id', $order->id)->first();
        $this->assertNotNull($proof);
        $this->assertEquals('pending', $proof->status);
    }

    public function test_qris_customer_cannot_upload_proof_before_confirmation(): void
    {
        $order = Order::factory()->qris()->pending()->create([
            'payment_status' => 'unpaid',
        ]);

        $file = UploadedFile::fake()->image('bukti.png', 200, 200);

        $response = $this->withTrackingAccess($order)->post("/pesanan/{$order->id}/bukti-bayar", [
            'proof' => $file,
        ]);

        $response->assertSessionHas('error');

        $order->refresh();
        $this->assertEquals('unpaid', $order->payment_status);

        $this->assertDatabaseMissing('payment_proofs', [
            'order_id' => $order->id,
        ]);
    }

    public function test_qris_customer_cannot_upload_proof_while_waiting_verification(): void
    {
        $order = Order::factory()->qris()->confirmed()->create([
            'payment_status' => 'waiting_verification',
        ]);

        $file = UploadedFile::fake()->image('bukti.png', 200, 200);

        $response = $this->withTrackingAccess($order)->post("/pesanan/{$order->id}/bukti-bayar", [
            'proof' => $file,
        ]);

        $response->assertSessionHas('error');

        $this->assertEquals(0, PaymentProof::where('order_id', $order->id)->count());
    }

    public function test_admin_can_approve_payment_proof(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::factory()->qris()->waitingVerification()->create();
        $proof = PaymentProof::factory()->create(['order_id' => $order->id]);

        $response = $this->actingAs($admin)
            ->patch("/admin/pesanan/{$order->id}/verifikasi-bukti", [
                'status' => 'approved',
                'admin_note' => 'Bukti valid.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);

        $proof->refresh();
        $this->assertEquals('approved', $proof->status);
        $this->assertNotNull($proof->verified_at);
        $this->assertEquals($admin->id, $proof->verified_by);
        $this->assertEquals('Bukti valid.', $proof->admin_note);
    }

    public function test_admin_can_reject_payment_proof(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::factory()->qris()->waitingVerification()->create();
        $proof = PaymentProof::factory()->create(['order_id' => $order->id]);

        $response = $this->actingAs($admin)
            ->patch("/admin/pesanan/{$order->id}/verifikasi-bukti", [
                'status' => 'rejected',
                'admin_note' => 'Bukti tidak jelas.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('rejected', $order->payment_status);

        $proof->refresh();
        $this->assertEquals('rejected', $proof->status);
        $this->assertNotNull($proof->verified_at);
        $this->assertEquals($admin->id, $proof->verified_by);
        $this->assertEquals('Bukti tidak jelas.', $proof->admin_note);
    }

    public function test_cash_customer_cannot_upload_proof(): void
    {
        $order = Order::factory()->cash()->confirmed()->create([
            'payment_status' => 'unpaid',
        ]);

        $file = UploadedFile::fake()->image('bukti.png', 200, 200);

        $response = $this->withTrackingAccess($order)->post("/pesanan/{$order->id}/bukti-bayar", [
            'proof' => $file,
        ]);

        $response->assertSessionHas('error');

        $order->refresh();
        $this->assertEquals('unpaid', $order->payment_status);

        $this->assertDatabaseMissing('payment_proofs', [
            'order_id' => $order->id,
        ]);
    }

    public function test_customer_cannot_access_other_order_success(): void
    {
        $order = Order::factory()->create([
            'order_number' => 'ORD-20260914-TEST01',
        ]);

        $response = $this->get('/pesanan/ORD-20260914-TEST01/berhasil');

        $response->assertRedirect(route('tracking.form'));
        $response->assertSessionHas('error');
    }

    public function test_order_numbers_are_unique(): void
    {
        $orderNumbers = collect();

        for ($i = 0; $i < 20; $i++) {
            $order = Order::factory()->create();
            $orderNumbers->push($order->order_number);
        }

        $this->assertEquals(20, $orderNumbers->unique()->count());

        foreach ($orderNumbers as $orderNumber) {
            $this->assertMatchesRegularExpression('/^ORD-\d{8}-[A-Z0-9]{6}$/', $orderNumber);
        }
    }

    public function test_customer_cannot_access_admin_routes(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect(route('admin.login'));
    }

    public function test_paid_order_cannot_upload_proof_again(): void
    {
        $order = Order::factory()->qris()->paid()->create();
        PaymentProof::factory()->approved()->create(['order_id' => $order->id]);

        $file = UploadedFile::fake()->image('bukti.png', 200, 200);

        $response = $this->withTrackingAccess($order)->post("/pesanan/{$order->id}/bukti-bayar", [
            'proof' => $file,
        ]);

        $response->assertSessionHas('error');

        $this->assertEquals(1, PaymentProof::where('order_id', $order->id)->count());
    }

    public function test_customer_can_upload_new_proof_after_rejection(): void
    {
        $order = Order::factory()->qris()->confirmed()->create([
            'payment_status' => 'rejected',
        ]);
        PaymentProof::factory()->rejected()->create(['order_id' => $order->id]);

        $file = UploadedFile::fake()->image('bukti_baru.png', 200, 200);

        $response = $this->withTrackingAccess($order)->post("/pesanan/{$order->id}/bukti-bayar", [
            'proof' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('waiting_verification', $order->payment_status);

        $this->assertEquals(2, PaymentProof::where('order_id', $order->id)->count());
    }

    public function test_admin_cannot_approve_non_pending_proof(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::factory()->qris()->paid()->create();
        $proof = PaymentProof::factory()->approved()->create(['order_id' => $order->id]);

        $response = $this->actingAs($admin)
            ->patch("/admin/pesanan/{$order->id}/verifikasi-bukti", [
                'status' => 'approved',
                'admin_note' => null,
            ]);

        $response->assertSessionHas('error');
    }

    public function test_upload_validates_file_type(): void
    {
        $order = Order::factory()->qris()->confirmed()->create([
            'payment_status' => 'unpaid',
        ]);

        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->post("/pesanan/{$order->id}/bukti-bayar", [
            'proof' => $file,
        ]);

        $response->assertSessionHasErrors(['proof']);
    }

    public function test_upload_validates_file_size(): void
    {
        $order = Order::factory()->qris()->confirmed()->create([
            'payment_status' => 'unpaid',
        ]);

        $file = UploadedFile::fake()->create('large.png', 3000, 'image/png');

        $response = $this->post("/pesanan/{$order->id}/bukti-bayar", [
            'proof' => $file,
        ]);

        $response->assertSessionHasErrors(['proof']);
    }

    public function test_customer_cannot_upload_proof_for_an_order_that_has_not_been_tracked(): void
    {
        $order = Order::factory()->qris()->confirmed()->create([
            'payment_status' => 'unpaid',
        ]);

        $file = UploadedFile::fake()->image('bukti.png', 200, 200);

        $response = $this->post("/pesanan/{$order->id}/bukti-bayar", [
            'proof' => $file,
        ]);

        $response->assertRedirect(route('tracking.form'));
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('payment_proofs', [
            'order_id' => $order->id,
        ]);
    }

    public function test_admin_can_confirm_order_with_shipping_cost(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::factory()->qris()->pending()->create([
            'subtotal' => 100000,
            'shipping_cost' => 0,
            'total_amount' => 100000,
        ]);

        $response = $this->actingAs($admin)->patch("/admin/pesanan/{$order->id}/konfirmasi", [
            'shipping_cost' => 15000,
            'note' => 'Ongkir standar',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('confirmed', $order->order_status);
        $this->assertEquals(15000, $order->shipping_cost);
        $this->assertEquals(115000, $order->total_amount);
        $this->assertNotNull($order->shipping_finalized_at);
    }

    public function test_admin_cannot_confirm_already_confirmed_order(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::factory()->qris()->confirmed()->create([
            'shipping_cost' => 10000,
        ]);

        $response = $this->actingAs($admin)->patch("/admin/pesanan/{$order->id}/konfirmasi", [
            'shipping_cost' => 20000,
        ]);

        $response->assertSessionHas('error');

        $order->refresh();
        $this->assertEquals(10000, $order->shipping_cost);
    }

    public function test_admin_cannot_confirm_non_pending_order(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::factory()->qris()->create([
            'order_status' => 'processing',
        ]);

        $response = $this->actingAs($admin)->patch("/admin/pesanan/{$order->id}/konfirmasi", [
            'shipping_cost' => 15000,
        ]);

        $response->assertSessionHas('error');

        $order->refresh();
        $this->assertEquals(0, $order->shipping_cost);
    }

    public function test_admin_cannot_change_shipping_cost_after_finalization(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::factory()->qris()->confirmed()->create([
            'shipping_cost' => 15000,
        ]);

        $response = $this->actingAs($admin)->patch("/admin/pesanan/{$order->id}/ongkir", [
            'shipping_cost' => 25000,
        ]);

        $response->assertSessionHas('error');

        $order->refresh();
        $this->assertEquals(15000, $order->shipping_cost);
    }

    public function test_admin_cannot_confirm_order_with_negative_shipping_cost(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::factory()->qris()->pending()->create();

        $response = $this->actingAs($admin)->patch("/admin/pesanan/{$order->id}/konfirmasi", [
            'shipping_cost' => -5000,
        ]);

        $response->assertSessionHasErrors(['shipping_cost']);

        $order->refresh();
        $this->assertEquals('pending', $order->order_status);
    }

    public function test_admin_can_update_status_for_confirmed_order(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::factory()->qris()->confirmed()->create();

        $response = $this->actingAs($admin)->patch("/admin/pesanan/{$order->id}/status", [
            'order_status' => 'processing',
            'note' => 'Mulai diproses',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('processing', $order->order_status);
    }

    public function test_admin_cannot_use_status_route_to_confirm_pending_order(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::factory()->qris()->pending()->create();

        $response = $this->actingAs($admin)->patch("/admin/pesanan/{$order->id}/status", [
            'order_status' => 'confirmed',
        ]);

        $response->assertSessionHas('error');

        $order->refresh();
        $this->assertEquals('pending', $order->order_status);
    }
}

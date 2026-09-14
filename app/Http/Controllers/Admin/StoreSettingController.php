<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateStoreSettingRequest;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\Storage;

class StoreSettingController extends Controller
{
    public function index()
    {
        $storeSetting = StoreSetting::getSingleton();

        return view('admin.settings.index', compact('storeSetting'));
    }

    public function update(UpdateStoreSettingRequest $request)
    {
        $storeSetting = StoreSetting::getSingleton();

        $data = [
            'store_name' => $request->input('store_name'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
        ];

        if ($request->hasFile('qris_image')) {
            $file = $request->file('qris_image');
            $filename = 'qris_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'qris/' . $filename;

            $file->storeAs('qris', $filename, 'public');

            if ($storeSetting->qris_image && Storage::disk('public')->exists($storeSetting->qris_image)) {
                Storage::disk('public')->delete($storeSetting->qris_image);
            }

            $data['qris_image'] = $path;
        }

        $storeSetting->update($data);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan toko berhasil disimpan.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);

        return view('addresses.edit', compact('item'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        $user = Auth::user();

        $addressData = [
            'postcode' => $request->postcode,
            'address'  => $request->address,
            'building' => $request->building,
        ];

        if ($request->update_profile == '1') {
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                $addressData
            );
            session()->forget('shipping_address');
        } else {
            session(['shipping_address' => $addressData]);
        }

        return redirect()->route('purchases.show', ['item_id' => $item_id])
            ->with('message', '住所を更新しました');
    }
}

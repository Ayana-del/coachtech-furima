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

        $addressData = $request->only(['postcode', 'address', 'building']);

        if ($request->boolean('update_profile')) {
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                $addressData
            );
            session()->forget('shipping_address');
        } else {
            session(['shipping_address' => $addressData]);
        }

        return redirect()->route('purchases.show', compact('item_id'))
            ->with('message', '住所を更新しました');
    }
}

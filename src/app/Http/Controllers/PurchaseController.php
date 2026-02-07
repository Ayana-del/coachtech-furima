<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function showPurchasePage($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        $addressData = session('shipping_address', [
            'postcode' => $user->profile->postcode ?? '',
            'address'  => $user->profile->address ?? '',
            'building' => $user->profile->building ?? '',
        ]);

        $address = (object)$addressData;

        return view('purchases.show', compact('item', 'user', 'address'));
    }

    public function storePurchase(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => [$request->payment_method === 'konbini' ? 'konbini' : 'card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $item->name],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchases.success', ['item_id' => $item->id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('purchases.show', ['item_id' => $item->id]),
        ]);

        return redirect($session->url, 303);
    }

    public function successPurchase(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        $addressData = session('shipping_address', [
            'postcode' => $user->profile->postcode ?? '',
            'address'  => $user->profile->address ?? '',
            'building' => $user->profile->building ?? '',
        ]);

        Order::create([
            'user_id'        => $user->id,
            'item_id'        => $item->id,
            'payment_method' => 'stripe',
            'postcode'       => $addressData['postcode'],
            'address'        => $addressData['address'],
            'building'       => $addressData['building'],
        ]);

        session()->forget('shipping_address');

        return redirect()->route('item.index')->with('message', '購入が完了しました');
    }
}

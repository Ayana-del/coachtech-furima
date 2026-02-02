<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function showPurchasePage($item_id)
    {
        $item = Item::findOrFail($item_id);

        /** @var User $user */
        $user = Auth::user();
        $profile = $user->profile;

        return view('purchases.show', compact('item', 'profile'));
    }

    public function storePurchase(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        if ($item->is_sold) {
            return back()->with('error', 'この商品は既に売り切れています');
        }

        // Stripeのシークレットキーを設定
        Stripe::setApiKey(config('services.stripe.secret'));

        // 支払い方法の種類を判別
        $paymentMethodTypes = ($request->payment_method === 'konbini') ? ['konbini'] : ['card'];

        // Stripe Checkoutセッションの作成
        $session = Session::create([
            'payment_method_types' => $paymentMethodTypes,
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

    /**
     * 決済成功後の処理（ここでOrderを作成）
     */
    public function successPurchase(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        /** @var User $user */
        $user = Auth::user();

        Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'stripe_completed',
            'postcode' => $user->profile->postal_code,
            'address' => $user->profile->address,
            'building' => $user->profile->building,
        ]);

        return redirect()->route('item.index')->with('message', '購入が完了しました');
    }

    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);

        /** @var User $user */
        $user = Auth::user();
        $profile = $user->profile;

        return view('addresses.edit', compact('item', 'profile'));
    }

    public function updateAddress(Request $request, $item_id)
    {
        /** @var User $user */
        $user = Auth::user();

        // 住所バリデーション（任意で追加してください）
        $request->validate([
            'postal_code' => 'required',
            'address'     => 'required',
        ]);

        // プロフィールの住所を更新（または新規作成）
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'postal_code' => $request->postal_code,
                'address'     => $request->address,
                'building'    => $request->building,
            ]
        );

        // 更新後、その商品の購入画面へ戻る
        return redirect()->route('purchases.show', ['item_id' => $item_id]);
    }
}

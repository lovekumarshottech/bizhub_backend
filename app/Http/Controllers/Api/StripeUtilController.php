<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class StripeUtilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $user =  request()->user();
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        //get user from auth middleware

        if ($user->is_account_created == 0) {

            if ($user->account_id == null) {
                $account = $stripe->accounts->create(['type' => 'express', 'country' => 'US', 'email' => $user->email]);
                $user->account_id = $account->id;
                $user->save();

                // $stripeAccUpdate = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            }
            $accountLinks = $stripe->accountLinks->create([
                'account' => $user->account_id,
                'refresh_url' => 'http://127.0.0.1:8000',
                'return_url' => route('update-account', ['id' => $user->id]),
                'type' => 'account_onboarding',
            ]);
            return response()->json(['success' => true, 'data' => $accountLinks->url]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Account already created'], 401);
        }
    }

    public function accountUpdate($id)
    {

        $user = User::findOrFail($id);
        $user->is_account_created = '1';
        $user->save();

        return Redirect::away('https://bizhub.page.link/?link=https://www.google.com?suceess%3D1%26account-id%3D' . $user->account_id . '&apn=com.bizhub.android');
    }

    public function accountPayoutMannual($accountId)
    {

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        // return response()->json(['success' => true, 'data' => $stripe->balance->retrieve()]);

        // $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        // //Stripe (or your platform) makes payouts to your bank account
        // $account = $stripe->accounts->retrieve($accountId);

        // return response()->json(['success' => true, 'data' => $account]);

        $stripe->accounts->update(
            $accountId,
            ['settings' => ['payouts' => ['schedule' => ['interval' => 'manual']]]]
        );


        //payout to platform account and after few days it will be paid to user

    }

    public function checkBalance()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        return response()->json(['success' => true, 'data' => $stripe->balance->retrieve()]);
    }

    public function payout($accountId)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $payout = \Stripe\Payout::create([
            'amount' => 5,
            'currency' => 'usd',
        ], [
            'stripe_account' => $accountId,
        ]);


        return response()->json(['success' => true, 'data' => $payout]);
    }

    public function addFund()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $stripe->topups->create(
            [
                'amount' => 20000,
                'currency' => 'usd',
                'description' => 'Top-up for week of May 31',
                'statement_descriptor' => 'Weekly top-up',
            ]
        );
        return response()->json(['success' => true, 'data' => 'Fund Added']);
    }




    public function checkoutSession(Request $request)
    {
        // return $request->all();
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));


        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ["card"],
            'line_items' => [[
                'amount' => 20000,
                'quantity' => 1,
                'currency' => 'usd',
                'name' => 'T-shirt',
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout-session-complete', ['id' => $request->services_id]),
            'cancel_url' => 'https://bizhub.page.link/?link=https://www.google.com?checkout-success%3D0&apn=com.bizhub.android',
            'payment_intent_data' => [
                'application_fee_amount' => 123,
                'transfer_data' => [
                    'destination' => $request->account_id,
                ],
            ],
        ]);

        return response()->json(['success' => true, 'data' => $session], 200);
    }

    public function checkoutSessionComplete($id)
    {

        // $job = DB::table('services')
        //     ->where('id', '=', $id)
        //     ->where('status', '=', '3')
        //     ->update(['status' => '1']);
        // $status = DB::table('service_applications')
        //     ->where('status', '=', '2')
        //     ->where('service_id', $id)
        //     ->update(['status' => '1']);
        return  Redirect::away('https://bizhub.page.link/?link=https://www.google.com?checkout-success%3D1&apn=com.bizhub.android');
    }


    public function promotionalCode()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $coupons = $stripe->coupons->create(['percent_off' => 50, 'duration' => 'once']);



        $stripe->promotionCodes->create(
            ['coupon' => $coupons->id, 'code' => 'TEST']
        );

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'amount' => '50000',
                'quantity' => 1,
                'currency' => 'usd',
                'name' => 'Towing Monthly Subscription',
            ]],
            'mode' => 'payment',
            'allow_promotion_codes' => true,
            // 'discounts' => [[
            //     'coupon' => $coupons->id,
            // ]],
            'success_url' => 'https://example.com/success',
            'cancel_url' => 'https://example.com/cancel',
        ]);
        return response()->json(['success' => true, 'data' => $session], 200);
    }

    public function tranferToSpecificAccount($accountId)
    {
        // return 'hello';
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $transfer = \Stripe\Transfer::create([
            "amount" => 1000,
            "currency" => "usd", 
            "destination" => $accountId,
        ]);
        return response()->json(['success' => true, 'data' => $transfer], 200);
    }

    public function refund($paymentIntentId)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $refund = $stripe->refunds->create(['payment_intent' => $paymentIntentId]);
        return response()->json(['success' => true, 'data' => $refund], 200);
    }
}

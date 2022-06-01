<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\BaseController;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Support;
use App\Models\SupportImages;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SupportController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $support = Support::all();
        return $this->sendResponse($support, 'Support fetched successfuly.');
    }

    //show my supports
    public function mySupport()
    {
        $support = Support::where('user_id', auth()->user()->id)->get();
        return $this->sendResponse($support, 'Support fetched successfuly.');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'title' => 'required:min:3',
            'description' => 'required:min:3',

        ]);
        $support = Support::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => Support::ACTIVE,
            'service_id' => $request->service_id,
        ]);
        try {
            if ($support) {
                if ($request->has('images')) {
                    $images = json_decode($request->images);
                    foreach ($images as $image) {

                        $path =  $this->convert($image, "support/");

                        SupportImages::create([
                            'support_id' => $support->id,
                            'file_name' => $path,
                        ]);
                    }
                }
                // return $this->sendResponse($support, 'Support created successfully');
                return response()->json([
                    'message' => 'Support created successfully',
                    'data' => $support
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Support not created',
                    'data' => $support
                ], 401);
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }


    public function convert($image, $destinationPath)
    {

        $rand = rand(1000, 9999);

        // public_path()/storage/uploads/support/423423.png

        $fileName = $rand . time() . '.' . $image->extension;
        $destinationPath = public_path() . '/storage/uploads/' . $destinationPath;
        file_put_contents($destinationPath . $fileName, base64_decode($image->image));

        return $fileName;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Support  $support
     * @return \Illuminate\Http\Response
     */
    public function destroy(Support $support)
    {
        try {
            $support = $support->delete();
            if ($support) {
                return $this->sendResponse($support, 'Support deleted successfully');
            } else {
                return $this->sendError('Support not deleted');
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    //create static support method


    public static function refund()
    {

        // those services which are expired and not refunded yet and it has been 24 hours of cancel job

        $services = Service::where('status', '2')->where('updated_at', '<=', Carbon::now()->subDay())->whereNull('refund_id')->get();

        // return $services;
        // 'updated_at < = 2022-04-05T18:10:41.543858Z'
        // '2022-04-06T17:27:21.000000Z <= 2022-04-05T18:10:41.543858Z'

        foreach ($services as $service) {
            $support = Support::where('service_id', $service->id)->where('status', Support::ACTIVE)->first();
            if (!$support) {
                // return  "no support found , Refunded successfully";
                $payment = Payment::where('service_id', $service->id)->first();
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                $refund = $stripe->refunds->create(['payment_intent' => $payment->transaction_id]);
                $service->update(['refund_id' => $refund->id]);
            }
        }
    }
}

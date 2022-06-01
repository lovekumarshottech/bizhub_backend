<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activeAndCompleteEarnedPayments($status)
    {


        try {
            if (!($status == 1 || $status == 0)) {
                return $this->sendError('Invalid status');
                // 0 for Active, 1 for Complete
            } else if ($status == 0) {
                $payments = Payment::with(['service', 'to', 'from'])
                ->where(function ($query) {
                    $query->where('to_user_id', request()->user()->id)
                    ->whereNull('payout_id');
                })
                // ->where('to_user_id', request()->user()->id)->whereNull('payout_id')
                ->orderBy('created_at', 'desc')
                ->get();
            } else if ($status == 1) {
                $payments =  Payment::with(['service', 'to', 'from'])
                ->where(function ($query) {
                    $query->where('to_user_id', request()->user()->id)
                    ->whereNotNull('payout_id');
                })
                // ->where('to_user_id', request()->user()->id)->whereNotNull('payout_id')
                ->orderBy('created_at', 'desc')
                ->get();
            }
            $object = new \stdClass();
            $object->payments = $payments;
            $object->total = $payments->sum('amount');

            return $this->sendResponse(Response::HTTP_OK, $object, 'Payments Retrived Successfully');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    public function activeAndCompleteSpendPayments($status)
    {
        try {
            if (!($status == 1 || $status == 0)) {
                return $this->sendError('Invalid status');
            } else  if ($status == 0) {
                $payments = Payment::with(['service', 'to', 'from'])
                ->where(function ($query) {
                    $query->where('from_user_id', request()->user()->id)
                    ->whereNull('payout_id');
                })
                // ->where('from_user_id', request()->user()->id)->whereNull('payout_id')
                ->orderBy('created_at', 'desc')
                ->get();
            } else if ($status == 1) {
                $payments = Payment::with(['service', 'to', 'from'])
                ->where(function ($query) {
                    $query->where('from_user_id', request()->user()->id)
                    ->whereNotNull('payout_id');
                })
                // ->where('from_user_id', request()->user()->id)->whereNotNull('payout_id')
                ->orderBy('created_at', 'desc')
                ->get();
            }
            $object = new \stdClass();
            $object->payments = $payments;
            $object->total = $payments->sum('amount');
            return $this->sendResponse(Response::HTTP_OK, $object, 'Payments Retrived Successfully');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}

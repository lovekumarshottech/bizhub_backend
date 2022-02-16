<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\{ServiceFiltersRequest, StoreServiceApplicationRequest, StoreServiceRequest};
use App\Models\{Service, ServiceApplication};
use Auth;
use Carbon\Carbon;
use db;
use Illuminate\Database\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends BaseController
{
    public function index(ServiceFiltersRequest $request)
    {
        $searchValues = preg_split('/\s+/', $request->q, -1, PREG_SPLIT_NO_EMPTY);
        $services = Service::select('id', 'user_id', 'title', 'created_at', 'start_date', 'amount', 'address')->selectRaw("
                     ( 3959 * acos( cos( radians(?) ) *
                       cos( radians( latitude ) )
                       * cos( radians( longitude ) - radians(?)
                       ) + sin( radians(?) ) *
                       sin( radians( latitude ) ) )
                     ) AS distance", [$request->latitude, $request->longitude, $request->latitude])
            ->whereHas('user', function ($q) {
                $q->where('user_id', '<>', request()->user()->id);
            })->with(['user:id,image'])->withCount('applications')->when($request->amount_from != "", function ($q) use ($request) {
                $q->whereBetween('amount', [$request->amount_from, $request->amount_to]);
            })->when($request->category_id, function ($q, $category) {
                $q->where('category_id', $category);
            })->when($request->distance, function ($q, $distance) {
                $q->having("distance", "<=", $distance);
            })->where(function ($q) use ($searchValues) {
                foreach ($searchValues as $value) {
                    $q->orWhere('title', 'like', "%{$value}%");
                    $q->orWhere('description', 'like', "%{$value}%");
                }
            })->active()->latest()->get();
        return $this->sendResponse(Response::HTTP_OK, $services, 'Services fetched successfuly.');
    }

    public function getMyServices(Request $request)
    {
        // $services = \DB::table('ratings')->where('service_id', 1)->get();
        // $totalReviews=0;
        // $totalRatings=0;
        // foreach($services as $ser){
        //     $totalRatings+=$ser->rating;
        //     $totalReviews++;
        // }
        // $totalRatings = $totalRatings/$totalReviews;
        // return $totalRatings;
        // return $totalReviews;
        $services = Service::whereHas('user', function ($q) {
            $q->where('user_id', request()->user()->id);
        })->withCount('applications')->with(['user', 'category', 'applications' => function ($q) {
            $q->with(['user', 'comments'
            => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                }
            ]);
        }
        ])->orderBy('created_at', 'DESC')->get();
        //['quotationInvoice' => function($q){ $q->with(['user']); }])->orderBy('id', 'DESC')

        return $this->sendResponse(Response::HTTP_OK, $services, 'Your posted Services fetched successfuly.');
    }

// Abd Function


    public function servicesEdit(Request $request)
    {
        //     $validator = Validator::make($request->all(), [
        //     'title' => 'required',
        //     'amount' => 'required',
        //     'description' => 'required',
        //     'latitude' => 'required',
        //     'longitude' => 'required',
        //     'address' => 'required',
        //     'start_date' =>  'required',
        //     'is_negotiable' =>  'required',
        //     'people_required'  => 'required',
        // ]);
        // if ($validator->fails()) {
        //     return $this->sendError(Response::HTTP_NOT_FOUND,$validator, 'All Fields Are Required.');
        // } 
        // else {
        $user = $request->user();
        // dd($request->all());
        // $services = Service::where('id',$request->id)->where('id',$user->id)
        $services = \DB::table('services')
            ->where('id', $request->service_id)
            ->where('user_id', $user->id)
            ->update([
                'title' => $request->title,
                'amount' => $request->amount,
                'description' => $request->description,
                'updated_at' => Carbon::now()->format('Y/m/d H:i:s'),
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'address' => $request->address,
                'is_negotiable' => $request->is_negotiable,
                'no_of_ppl' => $request->no_of_ppl,
                'status' => $request->status,
                'start_date' => $request->start_date
            ]);
        if ($services) {
            return $this->sendResponse(Response::HTTP_OK, $services, 'Services Data Has Been Successfully Updated.');
        } else {
            return $this->sendError(Response::HTTP_NOT_FOUND, $services, 'Service Failed Data Not Found or InComplete.');

        }
        // }
    }


    public function ServicesDelete($id)
    {
        $services;
        if ($id) {
            $service = Service::findOrFail($id);
            if ($service) {
                $services = $service->delete();
                return $this->sendResponse(Response::HTTP_OK, $services, 'Service Deleted successfuly.');
            } else {
                return $this->sendError(Response::HTTP_NOT_FOUND, $services, 'Service Deleting Failed Data Not Found.');
            }
        } else {
            return $this->sendError(Response::HTTP_NOT_FOUND, $services, 'Service Deleting Failed ID Is Required.');
        }
    }


    public function myServies($id)
    {
        try {
            // $service = Service::findOrFail($id);
            // $serviceData = $service->whereHas('user', function($q){
            //     $q->where('user_id', '=',request()->user()->id);
            // })->with(['user','category', 'applications', 'comments' => function($q){
            //     return $q->select('id','service_id','comment','created_at')->where('parent_id', null);
            // }, 'comments.reply.user'])->where('id',$service->id)->first();
            // return $this->sendResponse(Response::HTTP_OK, $serviceData, 'Service fetched successfuly.');

//            $ratings = \DB::table('ratings')->where('user_rate_to', $user->id)->where('status', '1')->get();
            $totalReviews = '';
            $totalRatings = 0;
            /*foreach ($ratings as $rate) {
                $totalRatings += $rate->rating;
                $totalReviews++;
            }
            if ($totalReviews > 0) {
                $totalRatings /= $totalReviews;
                $user->totalReviews = $totalReviews;
                $user->totalRatings = $totalRatings;
            } else {
                $user->totalReviews = 'No Reviews';
                $user->totalRatings = 0;
            }*/

            $service = Service::findOrFail($id);
            $serviceData = $service->whereHas('user', function ($q) {
                $q->where('user_id', '=', request()->user()->id);
            })->with(['user', 'category', 'applications.user:id,device_id,first_name,last_name,image'
                , 'comments.user:id,device_id,first_name,last_name,image', 'comments' => function ($q) {
                    return $q->select('id', 'user_id', 'service_id', 'comment', 'created_at')->where('parent_id', null);
                }, 'comments.reply:id,parent_id,user_id,comment,service_id,created_at', 'comments.reply.user:id,device_id,first_name,last_name,image'])->where('id', $service->id)->first();
            /*foreach ($serviceData as $service) {
                foreach ($service->applications as $apps) {
                    $totalReviews += ' '.$apps->user_id;
                }
            }
            return $totalReviews;*/
            return $this->sendResponse(Response::HTTP_OK, $serviceData, 'Service fetched successfully.');
        } catch (Throwable $e) {
            return $this->sendError(Response::HTTP_NOT_FOUND, $serviceData, 'Service Failed.');
        }
    }

    // To Display User information and offer derails
    public function userOffer($id)
    {
        if ($id) {
            $users = \DB::table('users')
                ->where('service_id', $id)
                ->join('service_applications', 'users.id', '=', 'service_applications.user_id')
                ->select('users.*', 'service_applications.description', 'service_applications.amount')
                ->get();
            return $this->sendResponse(Response::HTTP_OK, $users, 'Offer Has Been Fetched successfuly.');
        } else {
            return $this->sendError(Response::HTTP_NOT_FOUND, $users, 'Service Failed ID Is Required.');
        }
    }

//  Service Accept Offer For User
    public function offerAccept(Request $request)
    {
        $service = \DB::table('service_applications')->where('service_id', $request->service_id)->get();
        foreach ($service as $services) {
            $service = \DB::table('service_applications')
                ->where('service_id', $services->service_id)
                ->update([
                    'status' => '3']);
        }
        $service = DB::table('service_applications')
            ->where('id', $request->id)
            ->update([
                'status' => '2']);
        return $this->sendResponse(Response::HTTP_OK, $service, 'Offer Accepted.');
    }

    // POST API for rating and reviews
    public function rating(Request $request)
    {

        $rating = \DB::table('ratings')
            ->insert([
                'user_id' => \Auth()->user()->id,
                'service_id' => $request->service_id,
                'user_rate_to' => $request->rate_to,
                'comment' => $request->review,
                'rating' => $request->rating,
                'status' => $request->status,
                'created_at' => Carbon::now()->format('Y/m/d H:i:s'),
                'updated_at' => Carbon::now()->format('Y/m/d H:i:s')
            ]);
        return $this->sendResponse(Response::HTTP_OK, $rating, 'Rating Stored Successfuly.');
    }


    public function getRating(Request $request)
    {

        $user = $request->user();
        if ($request->status == 0) {
            $users = \DB::table('ratings')
                ->select('ratings.*', 'users.first_name', 'users.last_name', 'users.image')
                ->where('user_rate_to', $user->id)
                ->where('status', '1')
                ->leftJoin('users', 'ratings.user_id', '=', 'users.id')
                ->get();
            $user->ratings = $users;
            $services = \DB::table('ratings')->where('user_rate_to', $user->id)->where('status', '1')->get();
            $totalReviews = 0;
            $totalRatings = 0;
            foreach ($services as $ser) {
                $totalRatings += $ser->rating;
                $totalReviews++;
            }
            if ($totalReviews > 0) {
                $totalRatings /= $totalReviews;
                $user->totalReviews = $totalReviews;
                $user->totalRatings = $totalRatings;
            } else {
                $user->totalReviews = 'No Reviews';
                $user->totalRatings = 0;
            }
            return $this->sendResponse(Response::HTTP_OK, $user, 'Rating Fetched Successfuly.');
        } else if ($request->status == 1) {
            $users = \DB::table('ratings')
                ->where('user_rate_to', $user->id)
                ->where('status', '0')
                ->leftJoin('users', 'ratings.user_id', '=', 'users.id')
                ->select('ratings.*', 'users.first_name', 'users.last_name', 'users.image')
                ->get();
            $user->ratings = $users;

            $services = \DB::table('ratings')->where('user_rate_to', $user->id)->where('status', '0')->get();
            $totalReviews = 0;
            $totalRatings = 0;
            foreach ($services as $ser) {
                $totalRatings += $ser->rating;
                $totalReviews++;
            }
            if ($totalReviews > 0) {
                $totalRatings /= $totalReviews;
                $user->totalReviews = $totalReviews;
                $user->totalRatings = $totalRatings;
            } else {
                $user->totalReviews = 'No Reviews';
                $user->totalRatings = 0;
            }

            return $this->sendResponse(Response::HTTP_OK, $user, 'Rating Fetched Successfuly.');
        } else {
            return $this->sendError(Response::HTTP_OK, $user, 'Rating Fetched Successfuly.');
        }
    }

    public function jobComplete($id)
    {
        $job = \DB::table('services')
            ->where('id', '=', $id)
            ->update(['status' => '1']);
        $status = DB::table('service_applications')
            ->where('status', '=', '2')
            ->where('service_id', $id)
            ->update(['status' => '1']);
        return $this->sendResponse(Response::HTTP_OK, $job, 'Job Completed.');
    }

    public function jobCancel(Request $request)
    {
        $job = \DB::table('services')
            ->where('id', '=', $request->services_id)
            ->update([
                'status' => '2',
                'cancel_reason' => $request->cancel_reason
            ]);
        $status = \DB::table('service_applications')
            ->where('service_id', $request->services_id)
            ->update(['status' => '3']);
        return $this->sendResponse(Response::HTTP_OK, $job, 'Job Canceled.');
    }

    public function contactUs(Request $request)
    {
        $contactUs = \DB::table('contact_us')->insert([
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "subject" => $request->subject,
            "email" => $request->email,
            "number" => $request->number,
            "message" => $request->message,
        ]);
        return $this->sendResponse(Response::HTTP_OK, $contactUs, 'Thank You, We Will Get Back To You Soon.');
    }


// Abd Function End

    public function getAppliedServices(Request $request)
    {

        $services = Service::with(['user', 'category', 'applications' => function ($q) {
            $q->with(['user', 'comments'
            => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                }
            ])->where('user_id', request()->user()->id);
        }
        ])->whereHas('applications', function ($q) {
            $q->where('user_id', request()->user()->id);
        })->orderBy('created_at', 'DESC')->get();


        return $this->sendResponse(Response::HTTP_OK, $services, 'Applied Services fetched successfuly.');
    }


    public function store(StoreServiceRequest $request)
    {
        // dd(auth()->id());
        // dd($request->all());
        // Service::create($request->validated() + [
        //     'user_id' => auth()->id()
        // ]);
        // Service::create(array_merge(
        //     $request->validated(),
        //     [
        //         'user_id' => auth()->id(),
        //     ]
        //     ));

        $user = $request->user();
        $service = new Service($request->validated());
        $user->services()->save($service);
        return $this->sendResponse(Response::HTTP_CREATED, [], 'Job created successfully.');
    }

    public function show(Service $service)
    {
        $serviceData = $service->whereHas('user', function ($q) {
            $q->where('user_id', '<>', request()->user()->id);
        })->with(['user', 'category', 'applications.user:id,device_id,first_name,last_name,image'
            , 'comments.user:id,device_id,first_name,last_name,image', 'comments' => function ($q) {
                return $q->select('id', 'user_id', 'service_id', 'comment', 'created_at')->where('parent_id', null);
            }, 'comments.reply:id,parent_id,user_id,comment,service_id,created_at', 'comments.reply.user:id,device_id,first_name,last_name,image'])->where('id', $service->id)->first();
        return $this->sendResponse(Response::HTTP_OK, $serviceData, 'Service fetched successfuly.');
    }


    public function applyService(StoreServiceApplicationRequest $request)
    {
        $user = $request->user();

        // if($user->isAuthUserAppliedService()){
        //     return $this->sendResponse(Response::HTTP_UNPROCESSABLE_ENTITY, [], 'Application already submitted!');
        // } else {

        $service = \DB::table('service_applications')
            ->where('user_id', \Auth::user()->id)
            ->where('service_id', $request->service_id)
            ->first();
        if ($service) {
            return $this->sendResponse(Response::HTTP_UNPROCESSABLE_ENTITY, [], 'Offer already submitted!');
        } else {
            // $application = \DB::table('service_applications')->insert([
            //     'service_id' => $request->service_id,
            //     'amount' => $request->amount,
            //     'description' => $request->description,
            //     'status' => "0",
            // ]);
            $application = new ServiceApplication([
                'service_id' => $request->service_id,
                'amount' => $request->amount,
                'description' => $request->description,
                'status' => "0",
            ]);
            $user->applications()->save($application);
            return $this->sendResponse(Response::HTTP_CREATED, [], 'Offer submited successfully!');
        }

        // }
    }


    public function startInterview(Request $request)
    {
        $user = $request->user();
        if ($user->isAuthUserOwnService()) {
            ServiceApplication::where('id', $request->application_id)->update(['status' => $request->status]);
            return $this->sendResponse(Response::HTTP_CREATED, [], 'Interviewed initiated!');
        } else {
            return $this->sendResponse(Response::HTTP_UNPROCESSABLE_ENTITY, [], 'You are un-authorized to inititate interview');
        }
    }

    public function update(Request $request)
    {

    }

    public function destroy(Request $request)
    {

    }
}

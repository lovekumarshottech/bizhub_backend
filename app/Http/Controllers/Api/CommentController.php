<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\{Comment,Service, ServiceApplication};
use Illuminate\Http\Request;
use App\Http\Requests\{StoreCommentRequest};
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\BaseController as BaseController;

class CommentController extends BaseController
{
    public function index(Request $request){
        // $services = Service::whereHas('user', function($q){
        //     $q->where('user_id', '<>', request()->user()->id);
        // })->with(['user','category'])->active()->latest()->get();

        // return $this->sendResponse(Response::HTTP_OK, $services, 'Services fetched successfuly.');
    }


    public function store(StoreCommentRequest $request) {
        //dd($request->body);
        $user = $request->user();
        $comment = new Comment($request->validated());
        $user->comments()->save($comment);
        return $this->sendResponse(Response::HTTP_CREATED, [], 'Comment posted successfully!');
    }

    public function show(Request $request) {
        // $serviceData = $service->whereHas('user', function($q){
        //     $q->where('user_id', request()->user()->id);
        // })->with(['user','category'])->where('id',$service->id)->first();

        // return $this->sendResponse(Response::HTTP_OK, $serviceData, 'Service fetched successfuly.');
    }

}

<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\BaseController as BaseController;

class CategoryController extends BaseController
{
    public function index(Request $request){
        $categories = Category::where('is_active',"1")->orderBy('title', 'ASC')->get();
        return $this->sendResponse(Response::HTTP_OK, $categories, 'Categories fetched successfuly.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use App\Models\Support;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // public function index()
    // {
    //     return view('home');
    // }


    //all services view
    public function allServices()
    {
        $services = Service::with(['user', 'category'])->get();
        return view('admin.jobs', compact('services'));
    }



    //all categories view
    public function allCategories()
    {
        $categories = Category::all();
        return view('admin.categories', compact('categories'));
    }



    //all users view
    public function allUsers()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }



    //all disputes with supportimages view
    public function allDisputes()
    {
        $disputes = Support::with(['supportImages', 'service', 'service.user'])->get();
        //application which is cancel according to the service
        foreach ($disputes as $dispute) {
            $dispute->application = $dispute->service->application()->where('service_id', $dispute->service_id)->where('status', '4')->with('user')->first();
        }
        // return response()->json($disputes);
        return view('admin.disputes', compact('disputes'));
    }



    //add or edit category view
    public function addOrEditCategory($id = null)
    {
        if ($id) {
            $category = Category::find($id);
            return view('admin.add-category', compact('category'));
        }

        return view('admin.add-category');
    }



    //add category
    public function addCategory()
    {
        $category = Category::create([
            'title' => request('title'),
            'description' => request('description'),
            'is_active' => request('is_active')
        ]);

        if ($category) {
            return redirect()->route('categories')->with('success', 'Category added successfully');
        } else {
            return redirect()->route('add-category-view')->with('error', 'Category could not be added');
        }
    }



    //edit category
    public function editCategory()
    {
        $category = Category::find(request('id'));
        $category->title = request('title');
        $category->description = request('description');
        $category->is_active = request('is_active');
        $category->save();

        if ($category) {
            return redirect()->route('categories')->with('success', 'Category updated successfully');
        } else {
            return redirect()->route('add-category-view')->with('error', 'Category could not be updated');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Payment;
use App\Models\Service;
use App\Models\ServiceApplication;
use App\Models\Support;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Services\DataTable;

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
    public function allServicesView()
    {
        return view('admin.jobs');
    }

    public function allServices()
    {
        if (request()->ajax()) {
            $services = Service::with(['user', 'category'])->get();
            //counting the number of service applications
            foreach ($services as $service) {
                $service->applications_count = $service->applications()->count();
            }
            return DataTables::of($services)
                ->addColumn('action', function ($service) {
                    return '<a href = "' . route('view.applications', $service->id) . '" class="btn btn-danger btn-sm">' . $service->applications_count . '</a>';
                })
                ->make(true);
        }
        return view('admin.jobs');
    }



    //all categories view
    public function allCategories(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::latest()->get();
            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('action', function ($category) {
                    return '<a href="' . route('add-category-view', $category->id) . '" class="btn btn-primary btn-sm">Edit</a>
                    <a  class="btn btn-danger btn-sm" onClick="openModal(' . $category->id . ')">Delete</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        // $categories = Category::all();
        // return view('admin.categories', compact('categories'));
    }

    public function categoryIndexView()
    {
        return view('admin.categories');
    }

    public function usersIndexView()
    {
        return view('admin.users');
    }




    //all users view
    public function allUsers()
    {
        if (request()->ajax()) {
            $users = User::latest()->get();
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($user) {
                    return '<a onClick="openModal(' . $user->id . ')" class="btn btn-danger btn-sm">Delete</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }



    //all disputes with supportimages view
    public function allDisputes()
    {

        if (request()->ajax()) {
            $disputes = Support::with(['images', 'service', 'service.user'])->get();
            //application which is cancel according to the service
            foreach ($disputes as $dispute) {
                $dispute->application = $dispute->service->application()->where('service_id', $dispute->service_id)->where('status', '4')->with('user')->first();
            }
            return DataTables::of($disputes)
                ->addIndexColumn()
                ->addColumn('action', function ($dispute) {
                    return '<a href="' . route('view.dispute', $dispute->id) . '" class="btn btn-success btn-sm">Show</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function allDisputesView()
    {
        
        //  $disputes = Support::with(['images', 'service', 'service.user'])->get();
        //     //application which is cancel according to the service
        //     foreach ($disputes as $dispute) {
        //         $dispute->application = $dispute->service->application()->where('service_id', $dispute->service_id)->where('status', '4')->with('user')->first();
        //     }
        // return response()->json($disputes);

        return view('admin.disputes');
    }

    //show dispute details
    public function showDispute($id)
    {
        $dispute = Support::with(['images', 'service', 'service.user'])->find($id);
        $dispute->application = $dispute->service->application()->where('service_id', $dispute->service_id)->where('status', '4')->with('user')->first();
        // return response()->json($dispute);
        return view('admin.show-dispute', compact('dispute'));
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
        // return response()->json($category);
        $category->save();

        if ($category) {
            return redirect()->route('view.categories')->with('success', 'Category updated successfully');
        }
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id)->delete();

        if ($category) {
            return redirect()->route('view.categories')->with('success', 'Category deleted successfully');
        }
    }
    public function deleteUser($id)
    {
        $user = User::find($id)->delete();

        if ($user) {
            return redirect()->route('view.users')->with('success', 'User deleted successfully');
        }
    }

    public function applications($id)
    {

        if (request()->ajax()) {
            $applications = ServiceApplication::with(['user', 'service'])->where('service_id', $id)->get();
            return DataTables::of($applications)
                ->make(true);
        }
    }
    public function applicationsIndexView($id)
    {
        return view('admin.applications', compact('id'));
    }
    public function allQueries()
    {

        if (request()->ajax()) {
            $queries =  \DB::table('contact_us')->get();
            return DataTables::of($queries)
                ->make(true);
        }
    }
    public function allQueriesIndexView()
    {
        return view('admin.contact-us');
    }

    public function dashboard()
    {
        $users_count = User::where('is_admin', '0')->count();
        $queries_count =  \DB::table('contact_us')->count();
        $services_count = Service::count();

        $income = Payment::whereNotNull('transfer_id')->whereNotNull('payout_id')->sum('amount');
        $income_percents = $income * 0.15;

        $pending_payments = Payment::whereNull('transfer_id')->whereNull('payout_id')->sum('amount');
        $dispute_count = Support::where('status', '0')->count();
        return view('admin.dashboard', compact('users_count', 'services_count', 'dispute_count', 'queries_count', 'income_percents', 'pending_payments'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush();
        return redirect('/login');
    }

    public function payout($id)
    {

        $payment = Payment::with(['to', 'from'])->where('service_id', $id)->first();


        $amount = $payment->amount;
        $amount = round($amount - ($amount * (config('app.admin_cut') / 100)));



        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $transfer = \Stripe\Transfer::create([
            "amount" => $amount,
            "currency" => "usd",
            "destination" => $payment->to->account_id,
        ]);


        $payout = \Stripe\Payout::create([
            'amount' => $amount,
            'currency' => 'usd',
        ], [
            'stripe_account' => $payment->to->account_id,
        ]);

        $payment->transfer_id = $transfer->id;
        $payment->payout_id = $payout->id;
        $payment->save();
        if ($payment) {
            Support::where('service_id', $id)->where('status', '0')->update(['status' => '1']);
            return redirect()->route('view.disputes', $id)->with('success', 'Payout done successfully');
        }
    }

    public function refund($id)
    {
        $payment = Payment::where('service_id', $id)->first();


        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        $refund = $stripe->refunds->create(['payment_intent' => $payment->transaction_id]);
        return response()->json(['success' => true, 'data' => $refund], 200);
    }
}

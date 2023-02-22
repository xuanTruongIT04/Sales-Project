<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;

class DashboardController extends Controller
{
    //
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => "dashboard"]);
            return $next($request);
        });
    }

    function show()
    {
        $orders = Order::withoutTrashed()->orderbyDesc("created_at")->Paginate(20);
        $count_order = $orders -> total();
        return view("admin.dashboard", compact('orders', 'count_order'));
    }
}

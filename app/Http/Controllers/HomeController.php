<?php

namespace App\Http\Controllers;

use App\Payment;
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
        $this->middleware('is_subscriber');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function payments()
    {
        $payments = Payment::where('user_id', auth()->id())->paginate(10);
//        return $payments;
        return view('payments', compact('payments'));
    }
}

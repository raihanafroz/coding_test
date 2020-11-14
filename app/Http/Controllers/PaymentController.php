<?php

namespace App\Http\Controllers;

use App\Payment;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
  public function view(){
    return view('payment');
  }
  public function payment(Request $request){
    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    $response = \Stripe\Charge::create ([
      "amount" => 10 *100,
      "currency" => "usd",
      "source" => $request->stripeToken,
      "description" => "Monthly Subscription 10 USD"
    ]);
    if($response->status === "succeeded"){
      Payment::create([
        'user_id' => auth()->id(),
        'amount' => 10,
        'card_brand' => $response->source->brand,
        'res_at' => $response->created,
      ]);
      User::find(auth()->id())->update(['status' => 1, 'subscribe_at' => Carbon::now() ]);

      return redirect()->route('home');
    }
    \Session::flash('warning', 'Payment not successful');
    return back();
  }
}

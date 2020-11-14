<?php

namespace App\Http\Controllers;

use App\Payment;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('can_subscribe');
  }

  public function view(){
    return view('payment');
  }


  public function payment(Request $request){
    try {
      \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
      $response = \Stripe\Charge::create([
        "amount" => 10 * 100,
        "currency" => "usd",
        "source" => $request->stripeToken,
        "description" => "Monthly Subscription 10 USD"
      ]);
      if ($response->status === "succeeded") {
        Payment::create([
          'user_id' => auth()->id(),
          'amount' => 10,
          'card_brand' => $response->source->brand,
          'payment_at' => $response->created,
          'status' => $response->status,
        ]);
        User::find(auth()->id())->update(['status' => 1, 'subscribe_at' => Carbon::now()]);

        return redirect()->route('home');
      }
      $status = '<div class="alert alert-warning alert-dismissible show" role="alert">
                      <strong>Sorry!1! </strong> Payment not successful.
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                    </div>';
      return redirect()->back()->with('status', $status)->withInput();
    }catch (\Exception $e){

      $status = '<div class="alert alert-warning alert-dismissible show" role="alert">
                      <strong>Sorry!1! </strong> Something went wrong.
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                    </div>';
      return redirect()->back()->with('status', $status)->withInput();
    }
  }
}

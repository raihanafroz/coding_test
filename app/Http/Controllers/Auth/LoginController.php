<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

  protected $maxAttempts = 3;
  protected $decayMinutes = 10;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
//    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('guest')->except('logout');
//    }

      public function login(Request $request){
          if ($request->isMethod('POST')){
            $data = $request->all();
            //        Restrict wrong login attempt start
            $this->incrementLoginAttempts($request);
            $key = $this->throttleKey($request);
            $rateLimiter = $this->limiter();

            if ($this->hasTooManyLoginAttempts($request)) {
              $attempts = $rateLimiter->attempts($key);
//          return $attempts;
              $rateLimiter->clear($key);
              if ($attempts === 3) {
                $this->decayMinutes = 10;
              }
              if ($attempts >= 5) {
                $this->decayMinutes = 30;
              }
              for ($i = 0; $i < $attempts; $i++) {
                $this->incrementLoginAttempts($request);
              }
              $this->fireLockoutEvent($request);
              return $this->sendLockoutResponse($request);
            }
//        Restrict wrong login attempt end

//        validation start
            $validator = Validator::make($data, [
              'email' => 'required|email',
              'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
              return redirect()->back()
                ->withErrors($validator)
                ->withInput();
            }
//      validation end

            try {
              if (Auth::attempt(['email'=> $data['email'], 'password'=> $data['password']])) {
                $user = User::find(auth()->id());
                if($user->status == 1) {
                  $expire = Carbon::now()->subDays(30);
                  if($user->subscribe_at < $expire){
                    $user->update(['status'=> 0]);
                  }
                }
                return redirect()->route('home');
              }

              $status = '<div class="alert alert-warning alert-dismissible show" role="alert">
                      <strong>Sorry!! </strong> Email or password not match.
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                    </div>';
              return redirect()->back()->with('status', $status)->withInput();
            } catch (QueryException $e) {
//        dd($e);
              $status = '<div class="alert alert-warning alert-dismissible show" role="alert">
                      <strong>Sorry!!! </strong>Something Went wrong.
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                    </div>';
              return redirect()->back()->with('status', $status)->withInput();
            }
          }
          return view('auth.login');
      }
}

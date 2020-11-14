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
//              $user = User::where('email', $data['email'])->first();
//              if($user->status == 1) {
//                $expire = Carbon::now()->subDays(12);
//                if($user->subscribe_at < $expire){
//                  User::find(auth()->id())->update(['status'=> 0]);
//                }
//              }
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

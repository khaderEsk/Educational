<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    use GeneralTrait;

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        $token = JWTAuth::attempt($credentials);

        if (!$token)
            return $this->returnError("401",'token Not found');

        $user =auth()->user();
        $user->token = $token;

        return $this->returnData($user,'operation completed successfully');

    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();

        $existingUser = User::where('google_id', $user->id)->first();

        if ($existingUser) {
            auth()->login($existingUser, true);
        } else {
            $newUser = new User();
            $newUser->name = $user->name;
            $newUser->email = $user->email;
            $newUser->google_id = $user->id;
            $newUser->password = bcrypt(request(Str::random()));
            $newUser->save();

            auth()->login($newUser, true);
        }

        return $this->returnData($user,'operation completed successfully');
    }



    public function login_admin(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        $token = JWTAuth::attempt($credentials);

        if (!$token)
            return $this->returnError("",'Unauthorized');

        $user =auth()->user();
        $user->token = $token;

        return $this->returnData($user,'operation completed successfully');
    }

    public function register(RegisterRequest $request)
    {
        $user=User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => $request->password,
            'age'            => $request->age,
            'adress'         => $request->adress,
            'governorate'    => $request->governorate,
        ]);

        $credentials =['email'=>$user->email,'password'=>$request->password];
        $token = JWTAuth::attempt($credentials);
        $user->token=$token;

        if (!$token)
            return $this->returnError("",'Unauthorized');

        return $this->returnData($user,'operation completed successfully');
    }


    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        if ($token) {
            try {
                JWTAuth::setToken($token)->invalidate();
                return $this->returnError("",'Logged out successfully');
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return $this->returnError("",'some thing went wrongs');
            }
        } else {
            return $this->returnError("",'some thing went wrongs');
        }
    }


}

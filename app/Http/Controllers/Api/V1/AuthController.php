<?php

namespace App\Http\Controllers\Api\V1;

use App\Utils\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;


class AuthController extends Controller
{

    public function register(Request $request)
    {
        // return $request->all();

        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
            ]
        );

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        // $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->save();
        // $token = $user->createToken('Transitions')->accessToken;
        return ResponseHelper::success('Successfully Registered.', null);
    }

    public function login(Request $request)
    {
        $request->validate(
            [
                // 'phone' => ['required', 'string'],
                'email' => ['required'],
                'password' => ['required', 'string'],
            ]
        );

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = auth()->user();
            $token = $user->createToken('Transitions')->accessToken;
            return ResponseHelper::success('Successfuly logined.', ['token' => $token]);
        } else {
            return "error";
        }

        return ResponseHelper::fail('These credentials do not match our records.', '');
    }

    public function getUser(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            return $user;
        }
        return "error";
    }


    public function logout()
    {
        $user = auth()->user();
        $user->token()->revoke();

        return ResponseHelper::success('Successfully logouted.', null);
    }
}

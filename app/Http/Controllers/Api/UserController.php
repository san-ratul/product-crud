<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            return $user->createToken('Auth Token')->accessToken;
        } else {
            throw ValidationException::withMessages([
                'error' => 'Provided creditials did not matched'
            ]);
        }
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);
        return $user->createToken('Auth Token')->accessToken;
    }

    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
        return response()->json('logout', Response::HTTP_ACCEPTED);
    }
}

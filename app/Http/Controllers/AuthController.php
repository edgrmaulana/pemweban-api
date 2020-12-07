<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    
	public function register(Request $request)
	{
		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => bcrypt($request->password),
		]);

		$token = auth()->login($user);

		return $this->respondWithToken($token);
	}

	public function login(Request $request)
	{
		$credentials = $request->only(['email', 'password']);

		if (!$token = auth()->attempt($credentials)) {
			return response()->json(['error'  =>  'Unauthorized'], 401);
		}

		return $this->respondWithToken($token);
	}

	protected function respondWithToken($token)
	{
		return response()->json([
			'acces_token' => $token,
			'token_type' => 'bearer',
			'expires_in' => auth()->factory()->getTTL() * 60
		]);
	}

	public function getAuthUser(Request $request)
	{
		return response()->json(auth()->user());
	}

	public function logout()
	{
		auth()->logout();
		return response()->json(['message' => 'Successfully Logged Out!'], 200);
	}
}

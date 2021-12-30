<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponser;
    /**
     * This controller provides a token for mobile apps or frontend website
     * It can also be used to access the API via a GUI
     * Insomnia or Postman are two examples of GUI's
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->orWhere('phone', 'like', '%'.$request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error(__('Invalid Credentials'), 401);
        }

        // delete any existing tokens for that user to avoid DB flooding
        $user->tokens()->delete();

        $token = $user->createToken($request->email . '_api-token')->plainTextToken;
        return $this->success(['token' => $token], __('Logged in successfully'), 201);

    }
}

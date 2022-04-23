<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\NotificationCollection;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * POST - Login
     * @param LoginRequest $request
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        if(!auth()->attempt($data)){
            return response(['message' => 'Invalid Username or Password']);
        }

        $accessToken = auth()->user()->createToken('authToken')->plainTextToken;

        return response([
            'user' => auth()->user(),
            'access_token' => $accessToken,
            'message' => 'Login successful'
        ], 200);
    }

    /**
     * Get logged in users notifications
     * @param Request $request
     */
    public function notifications(Request $request)
    {
        return response(new NotificationCollection($request->user()->unreadNotifications), 200);
    }
}

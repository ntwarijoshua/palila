<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWTAuth;

class UserController extends Controller
{

    private const managerRoleSlug = 'manager';

    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function registerEventUser(Request $request)
    {
        /*
         * 1. get the user input
         * 2. validate the input for errors
         * 3. if errors return 400
         * 4. if no errors return 200 success
         */

        $userInput = $request->all();
        $validator = Validator::make($userInput, [
            'names' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return JsonResponse::create([
                'status' => 'failed',
                'errors' => $validator->errors(),
            ], 400);
        }
        $user = User::create([
            'names' => $userInput['names'],
            'email' => $userInput['email'],
            'password' => Hash::make($userInput['password']),
            'role_id' => Role::where('slug', self::managerRoleSlug)->first()->id,
        ]);

        if ($user) {
            return JsonResponse::create([
                'status' => 'success',
                'data' => $user,
            ], 200);
        }

    }

    public function authenticateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return JsonResponse::create([
                'status' => 'failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        try {
            if (!$token = $this->jwt->attempt($request->only('email', 'password'))) {
                return JsonResponse::create([
                    'status' => 'failed',
                    'error' => 'no user matching credentials in our database.',
                ], 404);
            }
        } catch (TokenExpiredException $e) {
            return JsonResponse::create([
                'status' => 'failed',
                'error' => 'token expired',
            ], 401);
        } catch (TokenInvalidException $e) {
            return JsonResponse::create([
                'status' => 'failed',
                'error' => 'token invalid',
            ], 401);
        } catch (JWTException $e) {
            return JsonResponse::create([
                'status' => 'failed',
                'error' => 'token absent',
            ], 500);
        }
        return JsonResponse::create([
            'status' => 'successful',
            'data' => compact('token'),
        ], 200);

    }

}

<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ResponseController;

class AuthService extends ResponseController {

    public function login($request) {
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:50',
            'password' => 'required|min:8|max:16',
        ]);
        
        if ($validator->fails()) {
            $data = [
                'message' => "Validation Fails",
            ];
            $errors = $validator->errors();
            return $this->successResponse($data, $errors);
        }

        try {
            if (Auth::attempt($request->all())) {
                $user = Auth::user();
                $token = $user->createToken('authToken')->plainTextToken;
                if($user->hasRole('admin')) {
                    $isAdmin = true;
                } else {
                    $isAdmin = false;
                }
                $data = [
                    'accessToken' => $token,
                    'isAdmin'     => $isAdmin,
                    'message'     => "Login Success",
                ];
                return $this->successResponse($data);
            } else {
                $data = null;
                $error = [
                    'code' => 'E0002',
                    'message' => 'Authentication fail',
                    'details' => 'blah blah',
                ];
                return $this->successResponse($data, $error);
            }
        } catch (\Exception $e) {
            $errors =  [
                'code' => 'E0001',
                'message' => 'An error occurred.',
                'details' => $e->getMessage(),
            ];
            return $this->failResponse(null, $errors);
        }
    }

    public function register($request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50|string',
            'email' => 'required|max:50|unique:users|string',
            'phone' => 'required|min:10|max:12|string',
            'identification_no' => 'required|string',
            'address' => 'required|string',
            'password' => 'required|min:8|max:16|string',
            'confirm_password' => 'required|min:8|max:16|string|same:password',
        ]);
        
        if ($validator->fails()) {
            $data = [
                'message' => "Validation Fails",
            ];
            $errors = $validator->errors();
            return $this->successResponse($data, $errors);
        }

        // phone check 

        if(!Str::startsWith($request->phone, '09')) {
            $data = [
                'message' => "Validation Fails",
            ];
            $errors = [
                'message' => "Phone number should starts with 09***********"
            ];
            return $this->successResponse($data, $errors);
        }

        // Create User
        return $this->createUser($request);    
    }

    public function logout() {
        try {
            // Revoke the current user's access token
            Auth::user()->currentAccessToken()->delete();
    
            $data = [
                'message' => 'Logout successful',
            ];
    
            return $this->successResponse($data);
        } catch (\Exception $e) {
            $errors = [
                'code' => 'E0004',
                'message' => 'Logout failed',
                'details' => $e->getMessage(),
            ];
    
            return $this->failResponse(null, $errors);
        }
    }

    public function createUser($request) {
        try {
            $res = DB::transaction(function () use($request) {
                $registered_no = $this->generateUserCode();
               
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'password' => Hash::make($request->password),
                    'registered_no' => $registered_no,
                    'identification_no' => $request->identification_no,
                ]);

                $user->assignRole('user');
                
                // create token 
        
                if($user) {
                    $token = $user->createToken('authToken')->plainTextToken;
                    $data = [
                        'accessToken' => $token,
                        'message'     => "User Registeration Success",
                    ];
                    return $this->successResponse($data);
                } else {
                    $data = ['message', 'user not found'];
                    return $this->successResponse($data);
                }
            });
            return $res;
        }  catch(\Exception $e) {
            $errors =  [
                'code' => 'E0001',
                'message' => 'An error occurred.',
                'details' => $e->getMessage(),
            ];
            return $this->failResponse(null, $errors);
        }
    }

    public function generateUserCode()
    {
        $lastUserCode = User::orderBy('id', 'desc')->value('registered_no');

        $lastUserNumericPart = intval(substr($lastUserCode, 2));
        $nextUserNumericPart = $lastUserNumericPart + 1;

        // Generate the next user code
        $nextUserCode = 'M-' . str_pad($nextUserNumericPart, 6, '0', STR_PAD_LEFT);

        return $nextUserCode;
    }
}
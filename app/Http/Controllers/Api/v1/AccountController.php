<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ResponseController;

class AccountController extends ResponseController
{
    protected $role;
    public function __construct(string $role = null)
    {
        $this->role = $role;
    }

    public function show($id)
    {
        $user = User::whereHas('roles', function ($q) {
            $q->where('name', $this->role);
        })->find($id);
        if ($user) {
            $data = [
                'profile' => new UserResource($user),
            ];
            return $this->successResponse($data);
        } else {
            $data = [];
            $error = [
                'code' => 'E0004',
                'message' => 'User not found',
            ];
            return $this->successResponse($data, $error);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::whereHas('roles', function ($q) {
            $q->where('name', $this->role);
        })->find($id);


        if ($user) {
            $name = $request->name;
            $email = $request->email;
            $phone = $request->phone;
            $address = $request->address;

            $validator = Validator::make($request->all(), [
                'name' => 'required|max:50',
                'email' => 'required|max:50|unique:users,email,' . $id,
                'address' => 'required|max:255',
                'phone' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                $data = [
                    'message' => "Validation Fails",
                ];
                $errors = $validator->errors();
                return $this->successResponse($data, $errors);
            }
            try {
                $user->update([
                    'name' => $name,
                    'email' => $email,
                    'address' => $address,
                    'phone' => $phone,
                ]);
                $data = [
                    'profile' => new UserResource($user),
                    'message' => "Profile Updated Successfully"
                ];
                return $this->successResponse($data);
            } catch (\Exception $e) {
                $errors =  [
                    'code' => 'E0001',
                    'message' => 'An error occurred.',
                    'details' => $e->getMessage(),
                ];
                return $this->failResponse(null, $errors);
            }
        } else {
            $data = [];
            $error = [
                'code' => 'E0004',
                'message' => 'User not found',
            ];
            return $this->successResponse($data, $error);
        }
    }

    public function change_password(Request $request, $id) {
        $user = User::whereHas('roles', function ($q) {
            $q->where('name', $this->role);
        })->find($id);

        if ($user) {
            $new_password = $request->new_password;
            $old_password = $request->old_password;

            $validator = Validator::make($request->all(), [
                'new_password' => 'required|min:8|max:50',
                'old_password' => 'required',
                'confirm_password' => 'required|min:8|max:50|same:new_password',
            ]);

            if ($validator->fails()) {
                $data = [
                    'message' => "Validation Fails",
                ];
                $errors = $validator->errors();
                return $this->successResponse($data, $errors);
            }

            // check password 

            $isCorrect = Hash::check($old_password, $user->password);
            if(!$isCorrect) {
                $data = [];
                $errors = [
                    'code' => '',
                    'message' => "Incorrect Password",
                ];
                return $this->successResponse($data, $errors);
            }
            try {
                $user->update([
                    'password' => Hash::make($new_password),
                ]);
                $data = [
                    'profile' => new UserResource($user),
                    'message' => "Password Updated Successfully"
                ];
                return $this->successResponse($data);
            } catch (\Exception $e) {
                $errors =  [
                    'code' => 'E0001',
                    'message' => 'An error occurred.',
                    'details' => $e->getMessage(),
                ];
                return $this->failResponse(null, $errors);
            }
        } else {
            $data = [];
            $error = [
                'code' => 'E0004',
                'message' => 'User not found',
            ];
            return $this->successResponse($data, $error);
        }
    }
}

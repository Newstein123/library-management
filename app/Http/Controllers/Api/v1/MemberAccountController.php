<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MemberAccountController extends AccountController
{   
    public function __construct() {
        parent::__construct('member');
    }

    public function delete($id) {
        $user =  User::find($id);
        if ($user) {
            try {
                $user->delete();
                $data = [
                    'message' => 'User Deleted Successfully'
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

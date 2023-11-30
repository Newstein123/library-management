<?php

namespace App\Http\Controllers\Api\v1;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserCollection;
use App\Http\Controllers\ResponseController;

class MemberController extends ResponseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::whereHas('roles', function($q) {
            $q->where('name', 'member');
        })->latest('id');

        $name = $request->name;
        $phone = $request->phone;
        $email = $request->email;
        $registered_no = $request->registered_no;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $page = $request->page ?? 1;
        $perpage = $request->perpage ?? 10;

        $total = User::whereHas('roles', function($q) {
            $q->where('name', 'member');
        })->count();
        
        if($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if($phone) {
            $query->where('phone', $phone);
        }

        if($email) {
            $query->where('email', 'like', '%' . $email . '%');
        }

        if($registered_no) {
            $query->where('registered_no', $registered_no);
        }

        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [Carbon::parse($start_date), Carbon::parse($end_date)]);
        }

        $users = $query->skip(($page - 1) * $perpage)->take($perpage)->get();
        $data = [
            'members' => new UserCollection($users),
            'total' => $total,
        ];
        return $this->successResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::whereHas('roles', function ($q) {
            $q->where('name', 'member');
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function change_status(string $id) {
        $user = User::whereHas('roles', function ($q) {
            $q->where('name', 'member');
        })->find($id);
        if ($user) {
            $status = ($user->is_active == 1) ? 0 : 1;
            $user->update([
                'is_active' => $status,
            ]);
            $data = [
                'message' => 'User Status Changed',
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
}

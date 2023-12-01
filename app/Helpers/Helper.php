<?php 

namespace App\Helpers;

use App\Models\User;
use App\Models\Notification;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Helper {
    public static function storeImage($file, $dir) : string {
        $filename = $file->getClientOriginalName();
        $path = Storage::putFileAs($dir, $file, $filename);
        return $path;
    }

    public static function deleteImage($filename, $dir) {
        if($filename != null && Storage::exists($dir)) {
            Storage::delete($filename);
        }
    }

    public static function makeNotification($data) {
        $noti = Notification::create([
            'user_id' => $data['user_id'],
            'title'   => $data['title'],
            'message'   => $data['message'],
        ]);
        return $noti;
    }

    public static function getRoleId($role) {
        $id = User::whereHas('roles', function($q) use($role) {
            $q->where('name', $role);
        })->value('id');
        return $id;
    }
}
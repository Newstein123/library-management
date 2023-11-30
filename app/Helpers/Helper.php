<?php 

namespace App\Helpers;

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
}
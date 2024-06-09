<?php

namespace App\Http\Traits;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use League\Flysystem\Visibility;
use Illuminate\Support\Facades\Storage;

trait FileStorageTrait
{
    public function storeFile($file)
    {
        // $file = $request->file;
        $originalName = $file->getClientOriginalName();

        // Check for double extensions in the file name
        if (preg_match('/\.[^.]+\./', $originalName)) {
            throw new Exception(trans('general.notAllowedAction'), 403);
        }

        //validate the mime type and extentions
        $allowedMimeTypes = ['image/jpeg','image/png','image/gif'];
        $allowedExtensions = ['jpeg','png','gif','jpg'];
        $mime_type = $file->getClientMimeType();
        $extension = $file->getClientOriginalExtension();

        if (!in_array($mime_type,$allowedMimeTypes) || !in_array($extension,$allowedExtensions)){
            throw new Exception(trans('general.invalidFileType'), 403);
        }

        // Sanitize the file name to prevent path traversal
        $fileName = Str::random(32);
        $fileName = preg_replace('/[^A-Za-z0-9_\-]/','',$fileName);

        //store the file in the public disc
        $path = $file->storeAs('images',$fileName . '.' . $extension,'public');

        //verify the path to ensure it matches the expected pattern
        $expectedPath = storage_path('app/public/images/' . $fileName . '.' . $extension);
        if (realpath(storage_path('app/public') . '/' . $path) !== $expectedPath){
            Storage::disk('public')->delete($path);
            throw new Exception(trans('general.notAllowedAction'),403);
        }

        // get the url of the stored file
        $url = Storage::disk('public')->url($path);
        return $url;
    }
}

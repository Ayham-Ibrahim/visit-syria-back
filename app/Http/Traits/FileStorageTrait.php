<?php

namespace App\Http\Traits;

use Exception;
use App\Models\Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use League\Flysystem\Visibility;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait FileStorageTrait
{
    /**
     *  Store photo and protect the site
     *
     * @param  string  $folderName The folder to upload the file to.
     * @param  file  $file The name of the file input field in the request.
     * @return string|null The url the photo url.
     */
    public function storeFile($file,string $folderName)
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
        //validate the mime type and extentions
        $allowedMimeTypes = ['image/jpeg','image/png','image/gif','image/jfif'];
        $allowedExtensions = ['jpeg','png','gif','jpg','jfif'];
        $mime_type = $file->getClientMimeType();
        $extension = $file->getClientOriginalExtension();

        if (!in_array($mime_type,$allowedMimeTypes) || !in_array($extension,$allowedExtensions)){
            throw new Exception(trans('general.invalidFileType'), 403);
        }

        // Sanitize the file name to prevent path traversal
        $fileName = Str::random(32);
        $fileName = preg_replace('/[^A-Za-z0-9_\-]/','',$fileName);

        //store the file in the public disc
        $path = $file->storeAs($folderName,$fileName . '.' . $extension,'public');

        //verify the path to ensure it matches the expected pattern
        $expectedPath = storage_path('app/public/'. $folderName .'/' . $fileName . '.' . $extension);
        $actualPath = storage_path('app/public/'.$path);
        if ($actualPath !== $expectedPath){
            Storage::disk('public')->delete($path);
            throw new Exception(trans('general.notAllowedAction'),403);
        }

        // get the url of the stored file
        // $url = Storage::disk('public')->url($path);
        $url = Storage::url($path);
        return $url;
    }


    /**
     * Check if a file exists and upload it.
     *
     * This method checks if a file exists in the request and uploads it to the specified folder.
     * If the file doesn't exist, it returns null.
     *
     * @param  Request  $request The HTTP request object.
     * @param  string  $folder The folder to upload the file to.
     * @param  string  $fileColumnName The name of the file input field in the request.
     * @return string|null The file path if the file exists, otherwise null.
     */
    public function fileExists($file,string $folderName)
    {
        if (empty($file)) {
            return null;
        }
        return $this->storeFile($file, $folderName);
    }


    /**
     *  Store array of images in images table and uploaded them to the specified folder
     *
     * This method take the uploaded images and upload them to the specified folder and store them in images table
     *
     * @param  object  $model the object that own the uploaded images
     * @param  string  $folderName The folder to upload the file to.
     * @param  files  $images the array of images that will uploaded
     */
    public function storeAndAssociateImages($model, $images,string $folderName)
    {
        Log::info($images);
        foreach ($images as $image) {
            $image = Image::create([
                'path' =>  $this->storeFile($image, $folderName),
                'imageable_id' => $model->id,
                'imageable_type' => get_class($model),
            ]);
        }
    }

    
    /**
     *  update array of images in images table and uploaded them to the specified folder
     *
     * This method will delete all old images and upload the new ones and store them in the specified folder
     *
     * @param  object  $model the object that own the uploaded images
     * @param  string  $folderName The folder to upload the file to.
     * @param  files  $images the array of images that will uploaded
     */
    public function updateAndAssociateNewImages($model, $images,string $folderName){
        DB::beginTransaction();
        try {
            $model->images()->each(function ($image) {
                $filePath = public_path($image->path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $image->forceDelete();
            });
            $this->storeAndAssociateImages($model, $images, $folderName);
            DB::commit();
        }catch (Exception $e) {
            DB::rollback();
            Log::error("Error deleting file: {$e->getMessage()}");
        }
    }
}

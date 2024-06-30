<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUser;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\FileStorageTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use ApiResponseTrait, FileStorageTrait;
    /**
     * Update the specified user.
     *
     * @param UpdateUser $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUser $request, User $user)
    {
        try {

            DB::beginTransaction();
            $user->name = $request->input('name') ?? $user->name;
            $user->country = $request->input('country') ?? $user->country;
            $user->email = $request->input('email') ?? $user->email;
            $user->password = $request->input('password') ?? $user->password;
            $user->image = $this->fileExists($request->image, 'user') ?? $user->image;
            $user->save();
            DB::commit();
            $user_updated = $user->only('name', 'country', 'email', 'image');
            return $this->successResponse($user_updated, 'user updated successfuly');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            return $this->errorResponse(null, "there is something wrong in server", 500);
        }
    }
    /**
     * Update the Admin user.
     *
     * @param UpdateUser $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAdmin(UpdateUser $request, User $user)
    {
        if ($user->is_admin) {
            try {
                DB::beginTransaction();
                $user->name = $request->input('name') ?? $user->name;
                $user->country = $request->input('country') ?? $user->country;
                $user->email = $request->input('email') ?? $user->email;
                $user->password = $request->input('password') ?? $user->password;
                
                $user->save();
                DB::commit();
                $user_updated = $user->only('name', 'country', 'email', 'image');
                return $this->successResponse($user_updated, 'admin updated successfuly');
            } catch (\Throwable $th) {
                DB::rollback();
                Log::error($th);
                return $this->errorResponse(null, "there is something wrong in server", 500);
            }
        } else {

            return $this->errorResponse(null, "this user not an admin", 400);
        }
    }
    /**
     * Update the Admin user photo.
     *
     * @param UpdateUser $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAdminImage(UpdateUser $request, User $user)
    {
        if ($user->is_admin) {
            try {
                
                DB::beginTransaction();
                $filePath = public_path($user->image);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $user->image = $this->fileExists($request->image, 'user') ?? $user->image;
                $user->save();
                DB::commit();
                $user_updated = $user->only('image');
                return $this->successResponse($user_updated, 'admin photo updated successfuly');
            } catch (\Throwable $th) {
                DB::rollback();
                Log::error($th);
                return $this->errorResponse(null, "there is something wrong in server", 500);
            }
        } else {
            return $this->errorResponse(null, "this user not an admin", 400);
        }
    }
    /**
     * Delete the Admin user photo.
     *
     * @param UpdateUser $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAdminImage( User $user)
    {
        if ($user->is_admin) {
            try {
                DB::beginTransaction();
                $filePath = public_path($user->image);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $user->image = null;
                $user->save();
                DB::commit();
                return $this->successResponse(null, 'admin photo deleted successfuly');
            } catch (\Throwable $th) {
                DB::rollback();
                Log::error($th);
                return $this->errorResponse(null, "there is something wrong in server", 500);
            }
        } else {
            return $this->errorResponse(null, "this user not an admin", 400);
        }
    }
}

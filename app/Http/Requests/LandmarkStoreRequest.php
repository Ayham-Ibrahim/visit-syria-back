<?php

namespace App\Http\Requests;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LandmarkStoreRequest extends FormRequest
{
    use ApiResponseTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                  => 'required|string|max:150|min:3',
            'location'              => 'required|string|max:150|min:3',
            'primary_description'   => 'required|string|max:1000|min:3',
            'secondary_description' => 'required|string|max:10000|min:3',
            'internal_image'        => 'required|file|image|mimes:png,jpg,jpeg,tiff|max:10000|mimetypes:image/jpeg,image/png,image/jpg,image/tiff',
            'external_image'        => 'required|file|image|mimes:png,jpg,jpeg,tiff|max:10000|mimetypes:image/jpeg,image/png,image/jpg,image/tiff',
            'city_id'               => 'required|exists:cities,id',
            'images'                => 'required',
            'images.*'              => 'file|image|mimes:png,jpg,jpeg,tiff|max:10000|mimetypes:image/jpeg,image/png,image/jpg,image/tiff',
        ];
    }

    protected function failedValidation(Validator $Validator)
    {
        $errors = $Validator->errors()->all();
        throw new HttpResponseException($this->errorResponse($errors, 'Validation error', 422));
    }
}

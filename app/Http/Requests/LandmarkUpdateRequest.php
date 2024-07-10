<?php

namespace App\Http\Requests;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LandmarkUpdateRequest extends FormRequest
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
            'name'                  => 'nullable|string|max:150|min:3',
            'location'              => 'nullable|string|max:150|min:3',
            'primary_description'   => 'nullable|string|max:1000|min:3',
            'secondary_description' => 'nullable|string|max:10000|min:3',
            'internal_image'        => 'nullable|file|image|mimes:png,jpg,jpeg,tiff|max:10000|mimetypes:image/jpeg,image/png,image/jpg,image/tiff',
            'external_image'        => 'nullable|file|image|mimes:png,jpg,jpeg,tiff|max:10000|mimetypes:image/jpeg,image/png,image/jpg,image/tiff',
            'city_id'               => 'nullable|exists:cities,id',
            'images'                => 'nullable',
            'images.*'              => 'file|image|mimes:png,jpg,jpeg,tiff|max:10000|mimetypes:image/jpeg,image/png,image/jpg,image/tiff',
        ];
    }
    
    protected function failedValidation(Validator $Validator){
        $errors = $Validator->errors()->all();
        throw new HttpResponseException($this->errorResponse($errors,'Validation error',422));
    }
}

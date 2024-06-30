<?php

namespace App\Http\Requests;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateResturantRequest extends FormRequest
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
            'name' => 'nullable',
            'location' => 'nullable',
            'city_id' => 'nullable|exists:cities,id',
            'primary_description' => 'nullable',
            'secondary_description' => 'nullable',
            'logo' => 'nullable|file|image|mimes:png,jpg|max:10000|mimetypes:image/jpeg,image/png,image/jpg',
            'cover_image' => 'nullable|file|image|mimes:png,jpg|max:10000|mimetypes:image/jpeg,image/png,image/jpg',
            'table_price' => 'numeric',
            'menu' => 'nullable',
            'services' => 'array|nullable',
            'services.*' => 'exists:services,id',
            'images'                 => 'array', // Ensure images is an array
            'images.*'              => 'nullable|file|image|mimes:png,jpg,jpeg,jfif|max:10000|mimetypes:image/jpeg,image/png,image/jpg,image/jfif', // Validate each image individually
        ];
    }

    protected function failedValidation(Validator $Validator){
        $errors = $Validator->errors()->all();
        throw new HttpResponseException($this->errorResponse($errors,'Validation error',422));
    }
}

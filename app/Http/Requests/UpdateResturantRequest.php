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
            'name' => 'sometimes',
            'location' => 'sometimes',
            'city_id' => 'sometimes|exists:cities,id',
            'primary_description' => 'sometimes',
            'secondary_description' => 'nullable',
            'logo' => 'sometimes|file|image|mimes:png,jpg|max:10000|mimetypes:image/jpeg,image/png,image/jpg',
            'cover_image' => 'sometimes|file|image|mimes:png,jpg|max:10000|mimetypes:image/jpeg,image/png,image/jpg',
            'table_price' => 'sometimes|numeric',
            'menu' => 'nullable',
            'services' => 'array|nullable',
            'services.*' => 'exists:services,id',
        ];;
    }

    protected function failedValidation(Validator $Validator){
        $errors = $Validator->errors()->all();
        throw new HttpResponseException($this->errorResponse($errors,'Validation error',422));
    }
}

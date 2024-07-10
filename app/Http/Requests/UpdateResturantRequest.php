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
            'name'                  => ['nullable','string','min:2','max:20'],
            'location'              => ['nullable','string','min:5','max:255'],
            'city_id'               => ['nullable','integer','exists:cities,id','min:1'],
            'primary_description'   => ['nullable','string','min:5'],
            'secondary_description' => ['nullable','string','min:5'],
            'cover_image'           => 'nullable|file|image|mimes:png,jpg,jfif|max:10000|mimetypes:image/jpeg,image/png,image/jpg,image/jfif',
            'logo'                  => 'nullable|file|image|mimes:png,jpg,jfif|max:10000|mimetypes:image/jpeg,image/png,image/jpg,image/jfif',
            'table_price'           => ['nullable','numeric','min:0'],
            'menu'                  =>'nullable|file|image|mimes:png,jpg,jfif|max:10000|mimetypes:image/jpeg,image/png,image/jpg,image/jfif',
            'services'              => 'array|nullable',
            'services.*'            => 'exists:services,id',
            'images.*'              => 'nullable|file|image|mimes:png,jpg,jpeg,jfif|max:10000|mimetypes:image/jpeg,image/png,image/jpg,image/jfif', // Validate each image individually
            'images'                => 'array', // Ensure images is an array
        ];
    }

    protected function failedValidation(Validator $Validator){
        $errors = $Validator->errors()->all();
        throw new HttpResponseException($this->errorResponse($errors,'Validation error',422));
    }
}

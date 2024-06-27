<?php

namespace App\Http\Requests\About;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateAboutRequest extends FormRequest
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
            //
            'title' => 'nullable |string|max:30',
            'content' =>'nullable |string|max:1000|min:5',
            'category' =>' nullable|in:الحضارات,الآثار,التاريخ,الطبيعة,السياحة',
            'main_image' =>'nullable|file|image|mimes:png,jpg|max:10000|mimetypes:image/jpeg,image/png,image/jpg'

        ];
    }

    protected function failedValidation(Validator $Validator){
        $errors = $Validator->errors()->all();
        throw new HttpResponseException($this->errorResponse($errors,'Validation error',422));
    }
}

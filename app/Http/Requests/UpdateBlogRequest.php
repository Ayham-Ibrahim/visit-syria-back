<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBlogRequest extends FormRequest
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
            'title'                 => ['nullable','string','min:2','max:50'],
            'city_id'               => ['nullable','integer','exists:cities,id','min:1'],
            'content'               => ['nullable','string','min:5'],
            'main_image'            => 'nullable|file|image|mimes:png,jpg,jpeg,jfif|max:10000|mimetypes:image/jpeg,image/png,image/jpg,image/jfif',
            'category'              => ['nullable',
                                        Rule::in('الطبيعة' , 'الاثرية'),
                                    ],
            'images.*'              => 'nullable|file|image|mimes:png,jpg,jpeg,jfif|max:10000|mimetypes:image/jpeg,image/png,image/jpg,image/jfif', // Validate each image individually
            'images'                 => 'array', // Ensure images is an array
        ];
    }
    
    protected function failedValidation(Validator $Validator){
        $errors = $Validator->errors()->all();
        throw new HttpResponseException($this->errorResponse($errors,'Validation error',422));
    }
}

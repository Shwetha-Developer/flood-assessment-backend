<?php
namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photos'            => 'required|array|min:1',
            'photos.*.local_id' => 'required|string',
            'photos.*.base64'   => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'photos.required'            => 'At least one photo is required',
            'photos.array'               => 'Photos must be an array',
            'photos.min'                 => 'At least one photo is required',
            'photos.*.local_id.required' => 'Each photo must have a local ID',
            'photos.*.base64.required'   => 'Each photo must have image data',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}

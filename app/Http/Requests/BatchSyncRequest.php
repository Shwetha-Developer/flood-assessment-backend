<?php
namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BatchSyncRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assessments'                  => 'required|array|min:1',
            'assessments.*.local_id'       => 'required|string',
            'assessments.*.latitude'       => 'required|numeric|between:-90,90',
            'assessments.*.longitude'      => 'required|numeric|between:-180,180',
            'assessments.*.address'        => 'required|string|max:500',
            'assessments.*.condition'      => 'required|in:good,moderate,bad',
            'assessments.*.total_chickens' => 'required|integer|min:0',
            'assessments.*.notes'          => 'nullable|string|max:2000',
            'assessments.*.assessed_at'    => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'assessments.required'                  => 'Assessments data is required',
            'assessments.array'                     => 'Assessments must be an array',
            'assessments.min'                       => 'At least one assessment is required',
            'assessments.*.local_id.required'       => 'Each assessment must have a local ID',
            'assessments.*.latitude.required'       => 'Each assessment must have latitude',
            'assessments.*.latitude.between'        => 'Latitude must be between -90 and 90',
            'assessments.*.longitude.required'      => 'Each assessment must have longitude',
            'assessments.*.longitude.between'       => 'Longitude must be between -180 and 180',
            'assessments.*.address.required'        => 'Each assessment must have an address',
            'assessments.*.condition.required'      => 'Each assessment must have a condition',
            'assessments.*.condition.in'            => 'Condition must be good, moderate or bad',
            'assessments.*.total_chickens.required' => 'Each assessment must have chicken count',
            'assessments.*.total_chickens.min'      => 'Chicken count cannot be negative',
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

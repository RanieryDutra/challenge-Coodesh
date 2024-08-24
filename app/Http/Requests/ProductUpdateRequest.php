<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Validator;

class ProductUpdateRequest extends FormRequest
{
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
            'status'           => 'sometimes|string|in:trash,draft,published',
            'url'              => 'sometimes|string',
            'creator'          => 'sometimes|string',
            'created_t'        => 'sometimes|date',
            'product_name'     => 'sometimes|string',
            'quantity'         => 'sometimes|integer',
            'brands'           => 'sometimes|string',
            'categories'       => 'sometimes|string',
            'labels'           => 'sometimes|string',
            'cities'           => 'sometimes|string',
            'purchase_places'  => 'sometimes|string',
            'stores'           => 'sometimes|string',
            'ingredients_text' => 'sometimes|string',
            'traces'           => 'sometimes|string',
            'serving_size'     => 'sometimes|string',
            'serving_quantity' => 'sometimes|numeric',
            'nutriscore_score' => 'sometimes|integer',
            'nutriscore_grade' => 'sometimes|string',
            'main_category'    => 'sometimes|string',
            'image_url'        => 'sometimes|string|url',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ], 422));
    }
}

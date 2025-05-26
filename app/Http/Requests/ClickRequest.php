<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class ClickRequest extends FormRequest
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
        // Если есть хотя бы один параметр в запросе, применяем правила
        if (!empty($this->all())) {
            return [
                'item' => 'required|string',
                'mp' => 'required|string|in:wb,ozon',
                'utm_campaign' => 'required|string',
                'utm_content' => 'required|string',
                'utm_medium' => 'required|string',
                'utm_source' => 'required|string',
                'seller_id' => 'required|string',
            ];
        }

        return response()->json(['message' => 'Empty request']);
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'item.required' => 'ID товара обязателен',
            'mp.required' => 'Маркетплейс обязателен',
            'mp.in' => 'Маркетплейс должен быть wb или ozon',
            'utm_campaign.required' => 'UTM campaign обязателен',
            'utm_content.required' => 'UTM content обязателен',
            'utm_medium.required' => 'UTM medium обязателен',
            'utm_source.required' => 'UTM source обязателен',
            'seller_id.required' => 'ID продавца обязателен',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'error' => 'Validation failed',
            'messages' => $validator->errors()
        ], 422));
    }
}

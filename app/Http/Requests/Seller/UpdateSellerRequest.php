<?php

declare(strict_types=1);

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSellerRequest extends FormRequest
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
        $seller = $this->route('seller');

        return [
            'seller_id' => [
                'required',
                'integer',
                Rule::unique('sellers', 'seller_id')->ignore($seller->id)
            ],
            'pp_id' => [
                'nullable',
                'integer',
                Rule::unique('sellers', 'pp_id')->ignore($seller->id)->whereNotNull('pp_id')
            ],
            'title' => 'nullable|string|max:255',
            'platform' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'api_key' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'seller_id.required' => 'ID продавца обязателен',
            'seller_id.integer' => 'ID продавца должен быть целым числом',
            'seller_id.unique' => 'ID продавца должен быть уникальным',
            'pp_id.integer' => 'PP ID должен быть целым числом',
            'pp_id.unique' => 'PP ID должен быть уникальным',
            'title.string' => 'Название должно быть строкой',
            'title.max' => 'Название должно быть не более 255 символов',
            'platform.string' => 'Платформа должна быть строкой',
            'platform.max' => 'Платформа должна быть не более 255 символов',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'seller_id' => (int) $this->seller_id,
            'platform' => (string) $this->platform,
            'url' => (string) $this->url,
            'api_key' => (string) $this->api_key,
        ]);
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'required|array',
            'tags.*' => 'integer|exists:tags,id',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Product with the name already exists.',
            'category_id.required' => 'Category ID is required.',
            'tags.required' => 'Tags are required.',
            'tags.*.exists' => 'Some tags do not exist in the database.',
        ];
    }
}

<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MineralRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('mineral')?->id;
        return [
            'name'          => 'required|string|max:150',
            'sku'           => ['required', 'string', 'max:50', Rule::unique('minerals', 'sku')->ignore($id)],
            'category_id'   => 'required|exists:categories,id',
            'supplier_id'   => 'nullable|exists:suppliers,id',
            'description'   => 'nullable|string',
            'image'         => 'nullable|image|max:2048',
            'unit'          => 'required|in:piece,gram,carat,kg',
            'cost_price'    => 'required|numeric|min:0',
            'sale_price'    => 'required|numeric|min:0',
            'stock_qty'     => 'required|integer|min:0',
            'min_stock_qty' => 'required|integer|min:0',
            'is_active'     => 'boolean',
        ];
    }
}
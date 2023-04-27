<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class TransactionFiltersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Check if request has only one filtering flag.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this['correct_filtering'] = !(count($this->request->all()) > 1) ? true : null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'correct_filtering' => ['required']
        ];
    }

}

<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required','string','max:100'],
            'last_name' => ['required','string','max:100'],
            'gender' => ['nullable','in:M,F,O'],
            'birth_date' => ['nullable','date'],
            'death_date' => ['nullable','date','after:birth_date'],
            'document_type' => ['nullable','string','max:20'],
            'document_id' => ['nullable','string','max:50','unique:persons,document_id'],
            'notes' => ['nullable','string'],
        ];
    }
}

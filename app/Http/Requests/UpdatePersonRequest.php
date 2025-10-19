<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = (int) ($this->route('person') ?? $this->route('id'));
        return [
            'first_name' => ['sometimes','required','string','max:100'],
            'last_name' => ['sometimes','required','string','max:100'],
            'gender' => ['sometimes','nullable','in:M,F,O'],
            'birth_date' => ['sometimes','nullable','date'],
            'death_date' => ['sometimes','nullable','date','after:birth_date'],
            'document_type' => ['sometimes','nullable','string','max:20'],
            'document_id' => ['sometimes','nullable','string','max:50','unique:persons,document_id,'.$id],
            'notes' => ['sometimes','nullable','string'],
        ];
    }
}

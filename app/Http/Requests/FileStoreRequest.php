<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileStoreRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'files' => 'required',
            'files.*' => 'max:20480|file'
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'file.required' => 'You need to select the file to download',
        ];
    }
}

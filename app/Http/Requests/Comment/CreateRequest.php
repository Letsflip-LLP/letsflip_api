<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
        $rules = [
            'post_id' => 'required',
            'text' => 'required',
            'files' => 'array'
        ];
        foreach ($this->input('files', []) as $index => $file) {
            $rules['files.' . $index . '.file_path'] = 'required';
            $rules['files.' . $index . '.file_name'] = 'required';
            $rules['files.' . $index . '.file_mime'] = 'required';
        }
        return $rules;
    }
}

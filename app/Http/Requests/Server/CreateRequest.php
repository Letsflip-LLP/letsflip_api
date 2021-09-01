<?php

namespace App\Http\Requests\Server;

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
        $rule = [
            'name' => 'required',
            'description' => 'required'
        ];

        $route_name = $this->route()->action['as'];
        $name = explode('.', $route_name);
        $name = $name[count($name) - 1];

        if ($name == 'edit') {
            $rule['id'] = 'required';
        } else if ($name == 'detail' || $name == 'delete') {
            $rule['id'] = 'required';
            unset($rule['name']);
            unset($rule['description']);
        }
        return $rule;
    }
}

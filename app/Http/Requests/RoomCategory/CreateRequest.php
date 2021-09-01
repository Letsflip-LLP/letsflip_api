<?php

namespace App\Http\Requests\RoomCategory;

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
        $route_name = $this->route()->action['as'];
        $name = explode('.', $route_name);
        $name = $name[count($name) - 1];

        if ($name == 'index') {
            $rule = [
                'server_id' => 'required|exists:server,id'
            ];
        } else if ($name == 'add') {
            $rule = [
                'name' => 'required',
                'description' => 'required',
                'server_id' => 'required|exists:server,id'
            ];
        } else if ($name == 'edit') {
            $rule = [
                'id' => 'required',
                'name' => 'required',
                'description' => 'required',
                'server_id' => 'required|exists:server,id'
            ];
        } else if ($name == 'detail' || $name == 'delete') {
            $rule = [
                'id' => 'required'
            ];
        }
        return $rule;
    }
}

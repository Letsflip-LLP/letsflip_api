<?php

namespace App\Http\Requests\RoomChannel;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
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

        $rule = [];
        if ($name == 'index') {
            $rule = [
                'category_id' => 'required|exists:room_category,id'
            ];
        } else if ($name == 'add' || $name == 'edit') {
            $rule = [
                'category_id' => 'required|exists:room_category,id',
                'name' => 'required',
                'description' => 'required',
            ];
        } else if ($name == 'detail' || $name == 'delete') {
            $rule = [
                'id' => 'required|exists:room_channels,id',
            ];
        }

        return $rule;
    }
}

<?php

namespace App\Http\Requests\RoomMemberType;

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
                'channel_id' => 'required|exists:room_channels,id'
            ];
        } else if ($name == 'add' || $name == 'edit') {
            $rule = [
                'channel_id' => 'required|exists:room_channels,id',
                'name' => 'required',
            ];
            if ($name == 'edit') {
                $rule['id'] = 'required|exists:room_member_types,id';
            }
        } else if ($name == 'detail' || $name == 'delete') {
            $rule = [
                'id' => 'required|exists:room_member_types,id',
            ];
            if ($name == 'delete') {
                $rule['channel_id'] = 'required|exists:room_channels,id';
            }
        }
        return $rule;
    }
}

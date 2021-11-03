<?php

namespace App\Http\Requests\RoomMessage;

use App\Http\Models\SC\RoomMemberModel;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Models\SC\RoomMessageModel;

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
                'channel_id' => [
                    'required',
                    'exists:room_channels,id',
                    function ($attribute, $value, $fail) {
                        $usr = auth('api')->user();
                        $svr = RoomMemberModel::where('user_id', $usr->id)
                            ->where('room_channel_id', $value)
                            ->count();
                        if ($svr < 1) {
                            $fail('You are not channel member');
                        }
                    }
                ],
                'message' => 'required'
            ];
            if (isset($this->parent_id)) {
                $rule['parent_id'] = function ($attribute, $value, $fail) {
                    $val = RoomMessageModel::where('id', $value)
                        ->has('parent')
                        ->count();
                    if ($val !== 0) {
                        $fail('Can`t reply message');
                    }
                };
            }
            if ($name == 'edit') {
                $rule['id'] = 'required|exists:room_channel_message,id';
                unset($rule['channel_id']);
            }
        } else if ($name == 'detail' || $name == 'delete') {
            $rule = [
                'id' => 'required|exists:room_channel_message,id',
            ];
        }
        return $rule;
    }
}

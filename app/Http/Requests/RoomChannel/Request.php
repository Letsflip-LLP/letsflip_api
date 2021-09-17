<?php

namespace App\Http\Requests\RoomChannel;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Models\SC\RoomCategoryModel;

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
                'name' => 'required',
                'description' => 'required',
                'category_id' => [
                    'required',
                    'exists:room_category,id',
                    function ($attribute, $value, $fail) {
                        $usr = auth('api')->user();
                        $svr = RoomCategoryModel::where('user_id', $usr->id)
                            ->where('id', $value)
                            ->count();
                        if ($svr < 1) {
                            $fail('You are not category creator');
                        }
                    }
                ]
            ];
            if ($name == 'edit') {
                $rule['id'] = 'required|exists:room_channels,id';
            }
        } else if ($name == 'detail' || $name == 'delete') {
            $rule = [
                'id' => 'required|exists:room_channels,id',
            ];
        }

        return $rule;
    }
}

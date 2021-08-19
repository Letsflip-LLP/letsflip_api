<?php

namespace App\Http\Controllers\V1\SC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use DB;
use App\Http\Models\User;
use App\Http\Models\SC\UserFriendsModel;
use App\Http\Transformers\ResponseTransformer;
use App\Http\Transformers\V1\SC\UserFriendTransformer;

class FriendsController extends Controller
{

    public function invitation(Request $request)
    {
        try {
            $user = auth('api')->user();
            $data = UserFriendsModel::where('user_id_from', $user->id)
                ->where('status', 2)
                ->paginate($request->input('per_page', 5));
            return (new UserFriendTransformer)->invitation(200, __('message.200'), $data, $user);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $user = auth('api')->user();
            $data = UserFriendsModel::where('user_id_from', $user->id)
                ->where('status', 1)
                ->paginate($request->input('per_page', 5));
            return (new UserFriendTransformer)->list(200, __('message.200'), $data, $user);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function add(Request $request)
    {

        $request->validate([
            'user_id' =>  'required|exists:users,id'
        ]);
        $user = auth('api')->user();
        DB::beginTransaction();
        try {
            UserFriendsModel::firstOrCreate([
                'user_id_from' => $user->id,
                'user_id_to' => $request->user_id,
            ], [
                'id' => Uuid::uuid4(),
                'status' => 2,
            ]);
            // UserFriendsModel::firstOrCreate([
            //     'user_id_from' => $request->user_id,
            //     'user_id_to' => $user->id,
            // ], [
            //     'id' => Uuid::uuid4(),
            //     'status' => 2,
            // ]);
            DB::commit();
            return (new ResponseTransformer)->toJson(200, __('message.200'), true);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function accept(Request $request)
    {
        $request->validate([
            'user_id' =>  'required|exists:users,id'
        ]);
        $user = auth('api')->user();
        DB::beginTransaction();
        try {
            $data = UserFriendsModel::where(function ($q) use ($user, $request) {
                    $q->where([
                        'user_id_from' => $request->user_id,
                        'user_id_to' => $user->id,
                    ]);
                    })->update([
                        'status' => 1
                    ]);

            UserFriendsModel::insert([
                'user_id_from' => $user->id,
                'user_id_to' => $request->user_id,
                'id' => Uuid::uuid4()
            ]);

            DB::commit();
            return (new ResponseTransformer)->toJson(200, __('message.200'), true);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function remove(Request $request)
    {

        $request->validate([
            'user_id' =>  'required|exists:users,id'
        ]);
        $user = auth('api')->user();
        DB::beginTransaction();
        try {
            $data = UserFriendsModel::where(function ($q) use ($user, $request) {
                $q->where([
                    'user_id_from' => $user->id,
                    'user_id_to' => $request->user_id,
                ]);
            })->orWhere(function ($q) use ($user, $request) {
                $q->where([
                    'user_id_from' => $request->user_id,
                    'user_id_to' => $user->id,
                ]);
            })->delete();
            DB::commit();
            return (new ResponseTransformer)->toJson(200, __('message.200'), true);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}

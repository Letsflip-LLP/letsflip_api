<?php

namespace App\Http\Controllers\V1\SC;

use App\Http\Controllers\Controller;

use App\Http\Models\SC\RoomChannelModel;
use App\Http\Models\SC\RoomMemberTypeModel;
use App\Http\Models\SC\RoomMemberModel;

use App\Http\Requests\RoomChannel\Request;
use App\Http\Transformers\V1\SC\RoomChannelTransformer;

use DB;
use Ramsey\Uuid\Uuid;

class RoomChannelController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = auth('api')->user();
            $data = RoomChannelModel::where('category_id', $request->category_id)
                ->whereHas('member', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            $data = $data->orderBy('created_at', 'desc')
                ->paginate($request->input('per_page', 5));
            return (new RoomChannelTransformer)->list(200, __('message.200'), $data);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function detail(Request $request)
    {
        try {
            $user = auth('api')->user();
            $data = RoomChannelModel::where('id', $request->id)
                // ->whereHas('member', function ($q) use ($user) {
                //     $q->where('user_id', $user->id);
                // })
                ->firstOrFail();

            return (new RoomChannelTransformer)->detail(200, __('message.200'), $data);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function add(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = auth('api')->user();

            $data = RoomChannelModel::create([
                'id' => Uuid::uuid4(),
                'user_id' => $user->id,
                'category_id' => $request->category_id,
                'name' => $request->name,
                'text' => $request->description,
            ]);
            // ADMIN MEMBER TYPE
            $admin_type = RoomMemberTypeModel::firstOrCreate([
                'name' => 'Administrator',
                'channel_id' => $data->id,
                'type' => 1,
            ], [
                'id' => Uuid::uuid4(),
                'user_id' => $user->id
            ]);

            // PARTICIPANT MEMBER TYPE
            $participant_type = RoomMemberTypeModel::firstOrCreate([
                'name' => 'Participant',
                'channel_id' => $data->id,
                'type' => 2,
            ], [
                'id' => Uuid::uuid4(),
                'user_id' => $user->id
            ]);


            RoomMemberModel::firstOrCreate([
                'id' => Uuid::uuid4(),
                'room_member_type_id' => $admin_type->id,
                'user_id' => $user->id,
                'room_channel_id' => $data->id
            ]);
            DB::commit();
            return (new RoomChannelTransformer)->detail(200, __('message.200'), $data);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function edit(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $data = RoomChannelModel::where('id', $request->id)
                ->whereHas('member', function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->whereHas('type', function ($q1) {
                            $q1->where('type', 1);
                        });
                })
                ->firstOrFail();
            $data->update([
                'name' => $request->name,
                'text' => $request->description,
                'category_id' => $request->category_id
            ]);

            DB::commit();
            return (new RoomChannelTransformer)->detail(200, __('message.200'), $data);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(Request $request)
    {

        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $data = RoomChannelModel::where('id', $request->id)
                ->whereHas('member', function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->whereHas('type', function ($q1) {
                            $q1->where('type', 1);
                        });
                })
                ->firstOrFail();

            $data->memberType()->delete();
            $data->member()->delete();

            $data->delete();

            DB::commit();
            return (new RoomChannelTransformer)->detail(200, __('message.200'), $data);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

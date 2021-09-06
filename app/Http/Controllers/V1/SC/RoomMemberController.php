<?php

namespace App\Http\Controllers\V1\SC;

use App\Http\Controllers\Controller;
use App\Http\Models\SC\RoomMemberModel;
use App\Http\Requests\RoomMember\Request;
use App\Http\Transformers\V1\SC\RoomMemberTransformer;

use DB;
use Ramsey\Uuid\Uuid;

class RoomMemberController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = RoomMemberModel::where('room_channel_id', $request->channel_id);
            $data = $data->orderBy('created_at', 'desc')
                ->paginate($request->input('per_page', 5));
            $data->withPath(route('room.channel.member.index'));
            return (new RoomMemberTransformer)->list(200, __('message.200'), $data);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function detail(Request $request)
    {
        try {
            $user = auth('api')->user();
            $data = RoomMemberModel::where('id', $request->id)
                ->firstOrFail();

            return (new RoomMemberTransformer)->detail(200, __('message.200'), $data);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function add(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $this->isCan($user, $request);
            $data = RoomMemberModel::updateOrCreate([
                'user_id' => $request->user_id,
                'room_channel_id' => $request->channel_id,
            ], [
                'room_member_type_id' => $request->room_member_type_id,
                'id' => Uuid::uuid4(),
            ]);
            DB::commit();
            return $this->index($request);
            // return (new RoomMemberTransformer)->detail(200, __('message.200'), $data);
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
            $this->isCan($user, $request);

            $data = RoomMemberModel::where('id', $request->id)
                ->firstOrFail();

            $data->update([
                'room_member_type_id' => $request->room_member_type_id,
                'room_channel_id' => $request->channel_id,
            ]);

            DB::commit();
            return $this->index($request);
            // return (new RoomMemberTransformer)->detail(200, __('message.200'), $data);
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
            $this->isCan($user, $request);
            $data = RoomMemberModel::where('id', $request->id)
                ->firstOrFail();

            $data->delete();

            DB::commit();
            return (new RoomMemberTransformer)->detail(200, __('message.200'), $data);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    private function isCan($user, $request)
    {
        $member = RoomMemberModel::where('user_id', $user->id)
            ->when(isset($request->channel_id), function ($q) use ($request) {
                $q->where('room_channel_id', $request->channel_id);
            })
            ->whereHas('type', function ($q) {
                $q->where('type', 1);
            })->first();
        if (!isset($member)) {
            throw new \Exception('User not channel administrator');
        }
        return true;
    }
}

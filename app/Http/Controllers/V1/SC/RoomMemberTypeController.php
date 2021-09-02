<?php

namespace App\Http\Controllers\V1\SC;

use App\Http\Controllers\Controller;

use App\Http\Models\SC\RoomMemberTypeModel;
use App\Http\Models\SC\RoomMemberModel;
use App\Http\Requests\RoomMemberType\Request;
use App\Http\Transformers\V1\SC\RoomMemberTypeTransformer;

use DB;
use Ramsey\Uuid\Uuid;

class RoomMemberTypeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = RoomMemberTypeModel::where('channel_id', $request->channel_id);
            $data = $data->orderBy('created_at', 'desc')
                ->paginate($request->input('per_page', 5));
            return (new RoomMemberTypeTransformer)->list(200, __('message.200'), $data);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function detail(Request $request)
    {
        try {
            $data = RoomMemberTypeModel::where('id', $request->id)
                ->firstOrFail();

            return (new RoomMemberTypeTransformer)->detail(200, __('message.200'), $data);
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
            $data = RoomMemberTypeModel::create([
                'id' => Uuid::uuid4(),
                'user_id' => $user->id,
                'channel_id' => $request->channel_id,
                'name' => $request->name,
            ]);
            DB::commit();
            return $this->index($request);
            // return (new RoomMemberTypeTransformer)->detail(200, __('message.200'), $data);
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
            $data = RoomMemberTypeModel::where('id', $request->id)
                ->firstOrFail();
            $data->update([
                'channel_id' => $request->channel_id,
                'name' => $request->name,
            ]);

            DB::commit();
            return $this->index($request);
            // return (new RoomMemberTypeTransformer)->detail(200, __('message.200'), $data);
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
            $data = RoomMemberTypeModel::where('id', $request->id)
                ->firstOrFail();

            $data->delete();

            DB::commit();
            return (new RoomMemberTypeTransformer)->detail(200, __('message.200'), $data);
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

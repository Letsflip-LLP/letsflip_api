<?php

namespace App\Http\Controllers\V1\SC;

use App\Http\Controllers\Controller;
use App\Http\Models\SC\RoomMessageModel;
use App\Http\Models\SC\RoomMessageContentModel;
use App\Http\Models\SC\RoomChannelModel;
use App\Http\Requests\RoomMessage\Request;
use App\Http\Transformers\V1\SC\RoomMessageTransformer;
use App\Http\Transformers\V1\SC\RoomChannelTransformer;
use App\Http\Libraries\RedisSocket\RedisSocketManager;

use DB;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

class RoomMessageController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = auth('api')->user();
            RoomMemberModel::where('channel_id', $request->channel_id)
                ->where('user_id', $user->id)
                ->update([
                    'last_seen' => Carbon::now()
                ]);

            $data = RoomMessageModel::withTrashed()
                ->whereNull('parent_id')
                ->where('room_channel_id', $request->channel_id);
            $data = $data->orderBy('created_at', 'desc')
                ->paginate($request->input('per_page', 5));
            $data->withPath(route('room.channel.message.index'));
            return (new RoomMessageTransformer)->list(200, __('message.200'), $data);
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function add(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $member_check = $this->isCan($user, $request);
            RoomChannelModel::where('id', $request->channel_id)->update([
                'last_message_time' => Carbon::now()
            ]);
            $channel_detail = (new RoomChannelModel)->where('id', $request->channel_id)->first();
            $data = RoomMessageModel::create([
                'user_id' => $user->id,
                'id' => Uuid::uuid4(),
                'text' => $request->message,
                'parent_id' => isset($request->parent_id) ? $request->parent_id : NULL,
                'room_channel_id' => $request->channel_id
            ]);
            if ($request->filled('files')) {
                foreach ($request->input('files') as $file) {
                    $data->content()->create([
                        'id' => Uuid::uuid4(),
                        'file_path' => $file['file_path'],
                        'file_name' => $file['file_name'],
                        'file_mime' => $file['file_mime'],
                    ]);
                }
            }
            DB::commit();
            // return $this->index($request);
            // SEND FOR MESSAGE
            $message_live = (new RedisSocketManager)->publishRedisSocket($request->channel_id, "CHANNEL_CHATS", "CREATE", (new RoomMessageTransformer)->item($data));

            // SEND FOR SERVER
            $socket_server_data = (object)[
                "type" => "channel",
                "channel_id" => $request->channel_id,
                "category_id" => $member_check->Category->id,
                "data"        => (new RoomChannelTransformer)->item($channel_detail)
            ];
            $server_update = (new RedisSocketManager)->publishRedisSocket($member_check->Category->server_id, "SERVER_UPDATE", "CREATE", $socket_server_data);

            return (new RoomMessageTransformer)->detail(200, __('message.200'), $data);
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

            $data = RoomMessageModel::where('id', $request->id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            $data->update([
                'text' => $request->message,
            ]);
            $request->request->add(['channel_id' => $data->room_channel_id]);
            DB::commit();
            return $this->index($request);
            // return (new RoomMessageTransformer)->detail(200, __('message.200'), $data);
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
            $data = RoomMessageModel::where('id', $request->id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            $data->delete();
            $request->request->add(['channel_id' => $data->room_channel_id]);
            DB::commit();
            return $this->index($request);
            // return (new RoomMessageTransformer)->detail(200, __('message.200'), $data);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function isCan($user, $request)
    {
        $member = RoomChannelModel::where('id', $request->channel_id)
            ->whereHas('member', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->first();

        if (!isset($member)) {
            throw new \Exception('User not in channel');
        }
        return $member;
    }
}

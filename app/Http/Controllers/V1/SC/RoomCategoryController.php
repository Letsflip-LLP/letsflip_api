<?php

namespace App\Http\Controllers\V1\SC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Models\SC\RoomCategoryModel;
use App\Http\Requests\RoomCategory\CreateRequest;
use App\Http\Transformers\V1\SC\RoomCategoryTransformer;

use DB;
use Ramsey\Uuid\Uuid;

class RoomCategoryController extends Controller
{
    public function index(CreateRequest $request)
    {
        try {
            $user = auth('api')->user();
            $data = RoomCategoryModel::where('server_id', $request->server_id)
                ->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->orWhereHas('channels.member', function ($q) use ($user) {
                            $q->where('user_id', $user->id);
                        });
                });

            $data = $data->orderBy('created_at', 'desc')->get();
            // ->paginate($request->input('per_page', 5));
            return (new RoomCategoryTransformer)->list(200, __('message.200'), $data);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function detail(CreateRequest $request)
    {
        try {
            $user = auth('api')->user();
            $data = RoomCategoryModel::where('id', $request->id)
                // ->whereHas('channels.member', function ($q) use ($user) {
                //     $q->where('user_id', $user->id);
                // })
                // ->where('user_id', $user->id)
                ->firstOrFail();

            return (new RoomCategoryTransformer)->detail(200, __('message.200'), $data);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function add(CreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $data = RoomCategoryModel::create([
                'id' => Uuid::uuid4(),
                'user_id' => $user->id,
                'name' => $request->name,
                'text' => $request->description,
                'server_id' => $request->server_id
            ]);
            DB::commit();
            return (new RoomCategoryTransformer)->detail(200, __('message.200'), $data);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function edit(CreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $data = RoomCategoryModel::where('id', $request->id)
                ->where('user_id', $user->id)
                ->firstOrFail();
            $data->update([
                'name' => $request->name,
                'text' => $request->description
            ]);

            DB::commit();
            return (new RoomCategoryTransformer)->detail(200, __('message.200'), $data);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(CreateRequest $request)
    {

        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $data = RoomCategoryModel::where('id', $request->id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            $data->delete();

            DB::commit();
            return (new RoomCategoryTransformer)->detail(200, __('message.200'), $data);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

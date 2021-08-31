<?php

namespace App\Http\Controllers\V1\SC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Transformers\V1\SC\ServerTransformer;
use App\Http\Requests\Server\CreateRequest;

use App\Http\Models\SC\ServerModel;
use DB;
use Ramsey\Uuid\Uuid;

class ServerController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = auth('api')->user();
            $data = ServerModel::where('user_id', $user->id);
            $data = $data->orderBy('created_at', 'desc')
                ->paginate($request->input('per_page', 5));
            return (new ServerTransformer)->list(200, __('message.200'), $data);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function detail(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required'
            ]);
            $user = auth('api')->user();
            $data = ServerModel::where('id', $request->id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            return (new ServerTransformer)->detail(200, __('message.200'), $data);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function add(CreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $data = ServerModel::create([
                'id' => Uuid::uuid4(),
                'user_id' => $user->id,
                'name' => $request->name,
                'text' => $request->description,
            ]);

            DB::commit();
            return (new ServerTransformer)->detail(200, __('message.200'), $data);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function edit(CreateRequest $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $data = ServerModel::where('id', $request->id)
                ->where('user_id', $user->id)
                ->firstOrFail();
            $data->update([
                'name' => $request->name,
                'text' => $request->description
            ]);

            DB::commit();
            return (new ServerTransformer)->detail(200, __('message.200'), $data);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(Request $request)
    {

        $request->validate([
            'id' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $data = ServerModel::where('id', $request->id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            $data->delete();

            DB::commit();
            return (new ServerTransformer)->detail(200, __('message.200'), $data);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
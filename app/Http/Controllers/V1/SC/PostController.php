<?php

namespace App\Http\Controllers\V1\SC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Post\CreateRequest;
use App\Http\Models\SC\PostModel;
use Ramsey\Uuid\Uuid;
use App\Http\Transformers\V1\SC\PostTransformer;
use DB;
use App\Http\Transformers\ResponseTransformer;

class PostController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = auth('api')->user();
            $data = PostModel::where(['user_id' => $user->id])->paginate(5);
            return (new PostTransformer)->list(200, __('message.200'), $data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function createPost(CreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $data = PostModel::create([
                'id' => Uuid::uuid4(),
                'user_id' => $user->id,
                'text' => $request->text
            ]);
            DB::commit();
            return (new PostTransformer)->detail(200, __('message.200'), $data);
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function updatePost(CreateRequest $request, $post_id)
    {
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $data = PostModel::where(['id' =>  $post_id, 'user_id' => $user->id])->first();
            if (!isset($data)) {
                throw new \Exception('Data Not Found');
            }
            $data->update([
                'text' => $request->text
            ]);
            DB::commit();
            return (new PostTransformer)->detail(200, __('message.200'), $data);
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function deletePost($post_id)
    {
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $data = PostModel::where([
                'user_id' => $user->id,
                'id' => $post_id
            ])->first();
            if (!isset($data)) {
                throw new \Exception('Data Not Found');
            }
            $data->comments()->delete();
            $data->delete();
            DB::commit();
            return (new ResponseTransformer)->toJson(200, __('messages.200'), 'delete');
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }
}

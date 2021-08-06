<?php

namespace App\Http\Controllers\V1\SC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\SC\CommentModel;
use App\Http\Transformers\V1\SC\CommentTransformer;
use App\Http\Requests\Comment\CreateRequest;
use DB;
use Ramsey\Uuid\Uuid;
use App\Http\Transformers\ResponseTransformer;

class CommentController extends Controller
{
    public function index(Request $request, $post_id)
    {
        try {
            $data = CommentModel::where([
                'post_id' => $post_id
            ])->with(['replies'])
                ->withTrashed()
                ->whereNull('parent_id')
                ->orderBy('created_at', 'asc')
                ->paginate(5);
            return (new CommentTransformer)->list(200, __('message.200'), $data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function createComment(CreateRequest $request, $post_id)
    {
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $fill = [
                'id' => Uuid::uuid4(),
                'user_id' => $user->id,
                'post_id' => $post_id,
                'text' => $request->text
            ];
            if ($request->filled('parent_id')) {
                $fill['parent_id'] = $request->parent_id;
            }
            $data = CommentModel::create($fill);
            DB::commit();
            return (new CommentTransformer)->detail(200, __('message.200'), $data);
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function updateComment(CreateRequest $request, $post_id, $comment_id)
    {
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $data = CommentModel::where([
                'id' =>  $comment_id,
                'post_id' => $post_id,
                'user_id' => $user->id
            ])->first();
            if (!isset($data)) {
                throw new \Exception('Data Not Found');
            }
            $data->update([
                'text' => $request->text
            ]);
            DB::commit();
            return (new CommentTransformer)->detail(200, __('message.200'), $data);
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteComment($post_id, $comment_id)
    {
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $data = CommentModel::where([
                'id' =>  $comment_id,
                'post_id' => $post_id,
                'user_id' => $user->id
            ])->with('replies')->first();
            if (!isset($data)) {
                throw new \Exception('Data Not Found');
            }
            $data->replies()->delete();
            $data->delete();
            DB::commit();
            return (new ResponseTransformer)->toJson(200, __('messages.200'), 'delete');
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }
}

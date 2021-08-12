<?php

namespace App\Http\Controllers\V1\SC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\SC\CommentModel;
use App\Http\Transformers\V1\SC\CommentTransformer;
use App\Http\Requests\Comment\CreateRequest;
use App\Http\Requests\Comment\UpdateRequest;
use DB;
use Ramsey\Uuid\Uuid;
use App\Http\Transformers\ResponseTransformer;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'post_id' => 'required'
        ]);
        try {
            $data = CommentModel::where([
                'post_id' => $request->post_id
            ])->with('Replies')
                ->withTrashed()
                ->whereNull('parent_id')
                ->orderBy('created_at', 'desc')
                ->paginate(5);
            return (new CommentTransformer)->list(200, __('message.200'), $data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function createComment(CreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $fill = [
                'id' => Uuid::uuid4(),
                'user_id' => $user->id,
                'post_id' => $request->post_id,
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

    public function updateComment(UpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $data = CommentModel::where([
                'id' =>  $request->comment_id,
                'post_id' => $request->post_id,
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

    public function deleteComment(Request $request)
    {
        $request->validate([
            'post_id' => 'required',
            'comment_id' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $data = CommentModel::where([
                'id' =>  $request->comment_id,
                'post_id' => $request->post_id,
                'user_id' => $user->id
            ])->with('Replies')->first();
            if (!isset($data)) {
                throw new \Exception('Data Not Found');
            }
            $data->Replies()->delete();
            $data->delete();
            DB::commit();
            return (new ResponseTransformer)->toJson(200, __('messages.200'), 'delete');
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }
}

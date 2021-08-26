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
use App\Http\Models\User;
use App\Http\Models\SC\UserFriendsModel;

class PostController extends Controller
{

    public function index(Request $request)
    {
        try {
            $user = auth('api')->user();
            if ($request->filled('user_id')) {
                $user = User::where('id', $request->user_id)->first();
                if (!isset($user)) {
                    throw new \Exception('User not found');
                }
            }
            $ids[] = $user->id;
            if ($request->filled('with_friend_post')) {
                $f_ids = UserFriendsModel::where('user_id_from', $user->id)
                    ->orWhere('user_id_to', $user->id)
                    ->get();

                $f_ids = $f_ids->transform(function ($v) use ($user) {
                    return $v->user_id_from;
                })->values()
                    ->toArray();
                $ids = array_merge($ids, $f_ids);
            }
            $data = new PostModel;
            if (count($ids) > 0) {
                $data = $data->whereIn('user_id', $ids);
            }
            $data = $data->orderBy('created_at', 'desc')
                ->paginate($request->input('per_page', 5));
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
            if ($request->filled('id')) {
                $data = PostModel::where(['id' =>  $request->id, 'user_id' => $user->id])->first();
                if (!isset($data)) {
                    throw new \Exception('Data Not Found');
                }
                $data->update([
                    'text' => $request->text
                ]);
            } else {
                $data = PostModel::create([
                    'id' => Uuid::uuid4(),
                    'user_id' => $user->id,
                    'text' => $request->text
                ]);
            }
            if ($request->filled('files')) { 
                foreach ($request->input('files') as $file) {
                    $data->Content()->create([
                        'id' => Uuid::uuid4(),
                        'file_path' => $file['file_path'],
                        'file_name' => $file['file_name'],
                        'file_mime' => $file['file_mime'],
                    ]);
                }
            }
            DB::commit();
            return (new PostTransformer)->detail(200, __('message.200'), $data);
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function updatePost(CreateRequest $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $data = PostModel::where(['id' =>  $request->id, 'user_id' => $user->id])->first();
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

    public function deletePost(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $data = PostModel::where([
                'user_id' => $user->id,
                'id' => $request->id
            ])->first();
            if (!isset($data)) {
                throw new \Exception('Data Not Found');
            }
            $data->Comments()->delete();
            $data->delete();
            DB::commit();
            return (new ResponseTransformer)->toJson(200, __('messages.200'), 'delete');
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    // public function home(Request $request)
    // {
    //     try {
    //         $user = auth('api')->user();
    //         $ids = array_column($user->Follower->pluck('id')->toArray(), 'id');
    //         $ids[] = $user->id;
    //         $data = PostModel::with('user')->whereIn('user_id', $ids)
    //             ->orderBy('created_at', 'desc')
    //             ->paginate(5);
    //         return (new PostTransformer)->list(200, __('message.200'), $data);
    //     } catch (\Exception $e) {
    //         throw new \Exception($e->getMessage());
    //     }
    // }
}

<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\LikesCollection;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Jobs\SendNewPostNotification;
use App\Models\Post;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * GET - return list of posts
     * @param Request $request
     */
    public function index(Request $request)
    {
        $posts = Post::query();
        if(isset($request->order_by) && $request->order_by == 'asc'){
            $posts->orderBy('created_at', 'ASC');
        }else{
            $posts->latest('created_at');
        }
        $posts = $posts->paginate(10);
        return response(new PostCollection($posts), 200);
    }

    /**
     * POST - store new post
     * @param PostRequest $request
     */
    public function store(PostRequest $request)
    {
        $postData = $request->validated();

        $fileName = uniqid().'.'.File::extension($request->image->getClientOriginalName());
        Storage::disk('local')->put('public/images/'.$fileName, file_get_contents($request->image));
        $postData['image'] = $fileName;
        $postData['user_id'] = auth()->user()->id;

        $post = Post::create($postData);
        $this->dispatch(new SendNewPostNotification($post));
        return response([
            'status' => true,
            'message' => 'Post created successfully',
            'post' => PostResource::make($post)
        ], 200);
    }

    /**
     * DELETE - Post
     * @param Request $request
     * @param Post $post
     */
    public function delete(Request $request, Post $post)
    {
        if(!Gate::allows('delete-post', $post)){
           abort(403, 'Unauthorized action');
        }

        $imageFile = storage_path('/app/public/images/'.$post->image);
        if(File::exists($imageFile)){
            File::delete($imageFile);
        }
        $post->likes()->delete();
        $post->delete();
        return response(['status' => true, 'message'=> 'Your post deleted successfully'], 200);
    }

    /**
     * POST - Like a post
     * @param Request $request
     * @param Post $post
     */
    public function like(Request $request, Post $post)
    {
        $request->user()->like($post);
        return response(['status' => true, 'likes' => $post->likes()->count()]);
    }

    /**
     * POST - Unlike a post
     * @param Request $request
     * @param Post $post
     */
    public function unlike(Request $request, Post $post)
    {
        $request->user()->unlike($post);
        return response(['status' => true, 'likes' => $post->likes()->count()]);
    }

    /**
     * POST - See top likes of post
     * @param Request $request
     * @param Post $post
     */
    public function likes(Request $request, Post $post)
    {
        $likes = $post->likes()->latest('created_at')->get();
        $likeCollection = new LikesCollection($likes);
        $likeCollection->setPost($post);
        return response($likeCollection,200);
    }

}

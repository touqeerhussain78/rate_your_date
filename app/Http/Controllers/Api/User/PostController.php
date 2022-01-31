<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Interaction;
// use Multicaret\Acquaintances\Interaction;
use Multicaret\Acquaintances\Models\InteractionRelation;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Post;
use App\Models\PortionBodyTarget;
use App\Models\Meal;
use App\Models\Exercise;
use App\Models\AgeGroup;
use App\Models\WeightRange;
use App\Models\HeightRange;
use Illuminate\Http\Request;

class PostController extends BaseController
{

    public function getNewsFeedPosts()
    {
        $posts = Post::with('likers')->withCount('comments', 'likers')->latest()->get();

        return $this->sendResponse($posts, __('Newsfeeds posts feteched successfully.'));

    }

    public function getPostDetail($id)
    {
        $posts = Post::whereId($id)->with('likers')->withCount('comments', 'likers')->first();

        return $this->sendResponse($posts, __('Post details feteched successfully.'));

    }

    public function createPost(Request $request)
    {

        $request->validate([
            'description' => 'required',
        ]);
        $user = $request->user();
        $post = $user->postable()->save(new Post([
            'description' => $request->description,
        ]));

        if($request->has('files')){
            foreach($request->file('files') as $file)
                $post->addMedia($file)->toMediaCollection("post-{$post->id}-media");
        }

        $post->load('media');

        return $this->sendResponse($post, __('Post created successfully.'));
    }

    public function updatePost(Request $request, Post $post){


        $request->validate([
            'description' => 'required',
        ]);

        $post->update([
            'description' => $request->description,
        ]);

        if($request->has('files')){
            foreach($request->file('files') as $file)
                $post->addMedia($file)->toMediaCollection("post-{$post->id}-media");
        }

        $post->load('media');

        return $this->sendResponse($post, __('Post updated successfully.'));
    }

    public function getLikes(Request $request, Post $post)
    {
        $likes = $post->likers()->get();

        return $this->sendResponse($likes, __('Likes feteched successfully.'));

    }

    public function LikePost(Request $request, Post $post){

        $request->user()->like($post);

        $like = $request->user()->likes(Post::class)->first();

        $post->fresh();

        return $this->sendResponse($post, __('Post liked successfully.'));

    }

    public function deletePost(Request $request, Post $post){

        $post->comments()->delete();
        Interaction::whereSubjectId($post->id)->delete();
        $post->delete();

        return $this->sendResponse($post, __('Post deleted successfully.'));

    }

    public function unLikePost(Request $request, Post $post){

        $result = $request->user()->unlike($post);
        $post->fresh();

        return $this->sendResponse($post, __('Post unliked successfully.'));
    }

    public function getComments(Request $request,  Post $post){

        $comments = $post->commentable()->latest()->get();

        return $this->sendResponse($comments, __('Comments feteched successfully.'));
    }

    public function saveComment(Request $request, Post $post){

        $comment = $post->comments()->save(new Comment([
            "comment" => $request->comment,
            "commentaddable_type" => get_class($request->user()),
            "commentaddable_id" => $request->user()->id,
        ]));
        $comment->load('commentaddable');
        return $this->sendResponse($comment, __('Comment saved successfully.'));
    }

    public function updatePostComment(Request $request, $id)
    {
        $comment = Comment::whereId($id)->first();
        $comment->update([
            "comment" => $request->comment,
        ]);

        return $this->sendResponse($comment, __('Comment updated successfully.'));
    }

    public function deletePostComment($id)
    {

        Comment::whereId($id)->delete();

        return $this->sendResponse(null, __('Comment deleted successfully.'));

    }


    public function customData()
    {
        $data['portion_body_targets'] = PortionBodyTarget::all();

        $data['exercises'] = Exercise::all();

        $data['meals'] = Meal::all();

        $data['age_groups'] = AgeGroup::all();

        $data['height_ranges'] = HeightRange::all();

        $data['weight_ranges'] = WeightRange::all();


        return $this->sendResponse($data, __('Data feteched successfully.'));
    }
}

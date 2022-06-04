<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt-check');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return  DB::table('comments')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->join('posts', 'comments.post_id', '=', 'posts.id')
            ->select('users.username', "comments.comment", "comments.post_id", "comments.user_id")
            ->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "comment" => "required",
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $err = array(
                "comment" => $errors->first("comment"),
            );
            return response()
                ->json(array(
                    "message" => "Can't process request. Check your input",
                    "errors" => $err
                ), 422);
        }
        $comment = new Comment;
        $comment->user_id = auth()->user()->id;
        $comment->post_id =  $request->input('post_id');
        $comment->comment = $request->input('comment');
        $comment->save();

        return response()->json(array(
            "message" => "Comment Success",
            "comments" => $comment
        ), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show($comment)
    {

        return response()->json(array(

            DB::table('comments')
                ->join('users', 'comments.user_id', '=', 'users.id')
                ->join('posts', 'comments.post_id', '=', 'posts.id')
                ->select('users.username', "comments.comment", "comments.post_id", "comments.user_id")
                ->where("comments.post_id", "=", $comment)->paginate(10)

        ), 200);
        // return  DB::table('comments')
        //     ->join('users', 'comments.user_id', '=', 'users.id')
        //     ->join('posts', 'comments.post_id', '=', 'posts.id')
        //     ->select('users.username', "comments.comment", "comments.post_id", "comments.user_id")
        //     ->where("compost_id", "=", $comment)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}

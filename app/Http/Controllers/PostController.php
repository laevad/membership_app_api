<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
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
        return  DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('users.username', "posts.post", "posts.id")
            ->paginate(10);
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
            "post" => "required",
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $err = array(
                "post" => $errors->first("post"),
            );
            return response()
                ->json(array(
                    "message" => "Can't process request. Check your input",
                    "errors" => $err
                ), 422);
        }
        $post = new Post;
        $post->user_id = auth()->user()->id;
        $post->post = $request->input('post');
        $post->save();

        return response()->json(array(
            "message" => "Successfully Posted",
            "posts" => $post
        ), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if ($post == NULL) {
            return  response()->json(["message" => 'Post is not Found'], 404);
        }
        DB::table('comments')->where('post_id', $id)->delete();
        $post->delete();
        return response()->json(array("message" => "Post successfully deleted"));
    }
}

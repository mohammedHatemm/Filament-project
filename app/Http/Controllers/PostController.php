<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller implements HasMiddleware

    {
        public static function middleware()
        {
            return [
                new Middleware('auth:sanctum' , except:['index','show'])
            ];
        }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
       $storeMethod= $request->validate([
            'title' => 'required|max:255',
            'body' => 'required'
        ]);
        $post = $request->user()->post()->create($storeMethod);

          return  $post;
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
        return  $post ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
        Gate::authorize('modify',$post);
        $storeMethod=$request -> validate([
            'title' =>'required|max:255',
            'body' =>'required'
        ]);
        $post->update($storeMethod);
        return  $post;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
        Gate::authorize('modify',$post);

        $post->delete();
        return  ["massage" => "the post daleted"];
    }
}

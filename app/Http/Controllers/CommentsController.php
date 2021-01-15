<?php

namespace App\Http\Controllers;

use App\CommentReply;
use App\DesignComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$design)
    {
        $comment=DesignComment::create(['user_id'=>Auth::id(),
            'design_id'=>$request->design_id,
            'body'=>$request->comment_body
        ]);
        $comment->{'user_image'}=$comment->user->image;
        $comment->{'user_name'}=$comment->user->name;
        $comment->{'replies'}=$comment->replies;
         return response()->json([
            'comment' => $comment
        ]);
    }
    

    public function commentReply(Request $request,$id)
    {
        $body=$request->Reply_body;
        $user_id=Auth::id();
        $reply=CommentReply::create(['body' => $body,
        'user_id'=>$user_id,
        'comment_id' => $id
        ]);
        $user=Auth::user();
        return response()->json([
            'reply' => $reply,
            'user' => $user
        ]);
    }
    

   
}

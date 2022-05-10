<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Gate;
use Laravelista\Comments\Comment;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CommentController extends Controller
{
    public function index()
    {
        if (!Gate::allows('comment_access')) {
            throw UnauthorizedException::forPermissions([]);
        }

        return view('backend.comments.index');
    }

    /**
     * Update the Comment status the specified resource.
     *
     * @param  \Laravelista\Comments\Comment $comment
     * @param int $status
     * @return \Illuminate\Http\Response
     */
    public function status(Comment $comment, $status)
    {
        $comment->approved = $status;
        $comment->save();
        return redirect()->back();
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->back();
    }
}

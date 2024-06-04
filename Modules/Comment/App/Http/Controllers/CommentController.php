<?php

namespace Modules\Comment\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Comment\App\Http\Requests\{StoreCommentRequest, UpdateCommentRequest};
use Modules\Comment\App\Models\Comment;
use Modules\User\App\Services\UserService;
use Ramsey\Uuid\Uuid;

class CommentController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(StoreCommentRequest $request)
    {
        $validateData = $request->validated();

        $userAuth = $this->userService->userAuth();

        Comment::create([
            'uuid' => Uuid::uuid4(),
            'user_uuid' => $userAuth->uuid,
            'username' => $userAuth->username,
            'comment' => $validateData['comment'],
        ]);

        return redirect('/#comment')->with('success', 'Success create comment, Thank you!');
    }

    public function viewCommentList()
    {
        $comments = Comment::latest()->get();

        return view('comment::layouts.list', [
            'comments' => $comments,
        ]);
    }

    public function viewEdit($saveUuidFromCall)
    {
        if (! preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/comment/list')->with(['error' => 'Invalid comment data!']);
        }

        $comment = Comment::where('uuid', $saveUuidFromCall)->first();

        if (! $comment) {
            return redirect('/comment/list')->with(['error' => 'Comment not found!']);
        }

        return view('comment::layouts.edit', [
            'comment' => $comment,
        ]);
    }

    public function update(UpdateCommentRequest $request, $saveUuidFromCall)
    {
        $validateData = $request->validated();

        if (! preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/comment/list')->with(['error' => 'Invalid comment data!']);
        }

        $comment = Comment::where('uuid', $saveUuidFromCall)->first();

        if (! $comment) {
            return redirect('/comment/list')->with(['error' => 'Comment not found!']);
        }

        $comment->update([
            'comment' => $validateData['comment'],
        ]);

        return redirect('/comment/list')->with(['success' => 'Comment success updated status!']);
    }

    public function delete($saveUuidFromCall)
    {
        if (! preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/comment/list')->with(['error' => 'Invalid comment data!']);
        }

        $comment = Comment::where('uuid', $saveUuidFromCall)->first();

        if (! $comment) {
            return redirect('/comment/list')->with(['error' => 'Comment not found!']);
        }

        $comment->delete();

        return redirect('/comment/list')->with(['success' => 'Comment success deleted!']);
    }
}

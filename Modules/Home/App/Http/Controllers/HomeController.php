<?php

namespace Modules\Home\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Comment\App\Models\Comment;

class HomeController extends Controller
{
    public function viewHome()
    {
        $comments = Comment::latest()->get();

        return view('home::layouts.index', [
            'comments' => $comments,
        ]);
    }
}

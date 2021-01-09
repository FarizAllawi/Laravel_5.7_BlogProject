<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;

class AdminController extends Controller
{
    public function addTag(Request $request) { 
        // Validate 
        return $create = Tag::create([
            'tagName' => $request->tagName,
        ]);
    }

    public function getTags() {
        return Tag::orderBy('id', 'desc')->get(); 
    }
}

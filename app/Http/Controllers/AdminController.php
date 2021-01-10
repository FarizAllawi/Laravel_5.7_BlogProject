<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;

class AdminController extends Controller
{

    public function getTags() {
        return Tag::orderBy('id', 'desc')->get(); 
    }

    public function addTag(Request $request) { 
        // Validate request
        $this->validate($request, [
            'tagName' => 'required|'
        ]);

        return $create = Tag::create([
            'tagName' => $request->tagName,
        ]);
    }

    public function editTag(Request $request) {
         // Validate request
         $this->validate($request, [
            'tagName' => 'required|',
            'id'      => 'required|'
        ]);

        return Tag::where('id', $request->id)->update([
            'tagName' => $request->tagName
        ]);
    }

    public function deleteTag(Request $request) {
        // validate request
        $this->validate($request , [
            'id' => 'required|'
        ]);
        return Tag::where('id' , $request->id)->delete();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use App\Category;

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

    // Method to handle upload file
    public function upload(Request $request) {
        // Validation for file upload
        $this->validate($request, [
            'file' => 'required|mimes:jpeg,jpg,png',
        ]);
        $picName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads'),$picName);
        return $picName;
    }

    // Method to handle delete upload file
    public function deleteUpload(Request $request) {
        $fileName = $request->imageName;
        $this->deleteFileFromServer($fileName);
        return 'done';
    }

    public function deleteFileFromServer($fileName) {
        $filePath = public_path().$fileName;
        return file_exists($filePath) ? @unlink($filePath) : 0;
    }

    // Category Method
    public function getCategory() {
        return Category::orderBy('id', 'desc')->get(); 
    }

    public function addCategory(Request $request) {
        // Validate request
        $this->validate($request, [
            'categoryName' => 'required|',
            'iconImage' => 'required|'
        ]);

        return $create = Category::create([
            'categoryName' => $request->categoryName,
            'iconImage'    => "/uploads/".$request->iconImage
        ]);
    }

    public function editCategory(Request $request) {
        // Validate request
        $this->validate($request, [
            'id'           => 'required|',
            'categoryName' => 'required|',
            'iconImage'    => 'required|'
        ]);

        return Category::where('id', $request->id)->update([
            'categoryName' => $request->categoryName,
            'iconImage'    => $request->iconImage
        ]);
    }
}

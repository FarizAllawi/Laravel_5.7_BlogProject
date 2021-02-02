<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Tag;
use App\Category;
use App\User;
use App\Role;

class AdminController extends Controller
{
    public function index(Request $request) {
        // First Check if yoar login and admin user
        if (!Auth::check() && $request->path() != 'login'){
            return redirect('/login');
        }

        if (!Auth::check() && $request->path() == 'login'){   
            return view('welcome');
        }

        // You already login... so check for if you are admin user
        $user = Auth::user();
        if ($user->userType == 'User') {
            return redirect('/login');
        }

        if ($request->path() == 'login') {
            return redirect('/');
        }
        return view('welcome');
    }

    public function logout() {
        Auth::logout();
        return redirect('/login');
    }

    public function adminLogin(Request $request) { 
        $this->validate($request, [
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6'
        ]);

        $credential_data = [
            'email' => $request->email,
            'password' => $request->password 
        ];

        if (Auth::attempt($credential_data)) {
            $user = Auth::user();

            if ($user->userType == 'User') {
                Auth::logout();
                return response()->json([
                    'msg' => 'Incorrect Login details'
                ],401);
            }

            redirect('/');
            return response()->json([ 
                'msg' => 'You are logged in',
                'user' => $user
            ]);
        } else {
            return response()->json([
                'msg' => 'Incorrect Login details'
            ],401);
        }
    }

    /**
     * Tags Method
     */
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

    /**
     * Category Method
     */
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

    public function deleteCategory(Request $request) {
        $this->validate($request , [
            'id' => 'required',
            'iconImage' => 'required'
        ]);
        $deleteCategory = Category::where('id' , $request->id)->delete();
        if ($deleteCategory){
            $this->deleteFileFromServer($request->iconImage);
            return 'done';
        } 
        else return 0;
    }
    

    /**
     * Admin User Method
     */
    public function getUsers() {
        return User::where('userType', '!=' , 'User')->get(); 
    }

    public function addUsers(Request $request) {
        // Validate request
        $this->validate($request, [
            'fullName'     => 'required|',
            'email'        => 'bail|required|email',
            'password'     => 'bail|required|min:6',
            'userType'     => 'required',
        ]);
        return $create = User::create([
            'fullName' => $request->fullName,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'userType' => $request->userType
        ]);
    }

    public function editUsers(Request $request) {
        // Validate request
        $this->validate($request, [
            'fullName'     => 'required|',
            'email'        => "bail|required|email|unique:users,email,$request->id",
            'password'     => 'min:6',
            'userType'     => 'required',
        ]);
        $data  = [
            'fullName'     => $request->fullName,
            'email'        => $request->email,
            'userType'     => $request->userType,
        ];
        if ($request->password) $data['password'] = bcrypt($request->password);
        return $update = User::where('id', $request->id)->update($data);
    }

    public function deleteUsers(Request $request) {
        // validate request
        $this->validate($request , [
            'id' => 'required|'
        ]);
        return User::where('id' , $request->id)->delete();
    }

    /**
     * Role User Management 
     */
    public function getRoles(Request $request) {
        return Role::orderBy('id', 'desc')->get(); 
    }

    public function addRoles(Request $request) {
        $this->validate($request , [
            'roleName' => 'required',
        ]);
        return Role::create([
            'roleName' => $request->roleName,
        ]);
    }

    public function editRole(Request $request) {
        $this->validate($request , [
            'roleName' => 'required',
        ]);
        $data = [
            'roleName' => $request->roleName
        ];
        return Role::where('id' , $request->id)->update($data);
    }

    public function deleteRole(Request $request) {
        // validate request
        $this->validate($request , [
            'id' => 'required|'
        ]);
        return Role::where('id' , $request->id)->delete();
    }

}

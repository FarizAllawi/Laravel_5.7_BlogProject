<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function controllerMethod() {
    //    return view('welcome');
        return response()->json([
            'msg' => 'We should return only json'
        ]);
    }

    public function test() {
        return response()->json([
            'msg' => 'Some Error Occured'
        ], 422);
    }
}

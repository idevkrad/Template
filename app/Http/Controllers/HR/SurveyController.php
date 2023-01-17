<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index(Request $request){
        return inertia('HR/Survey/Index');
    }

    public function show(Request $request){
        if($request->survey == 'staff'){
            return inertia('HR/Survey/Admin');
        }
    }
}

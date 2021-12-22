<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EverigoController extends Controller
{
    public function everigo(){
        return view('everigo.index');
    }

    public function webbasic(){
        return view('everigo.webbasic');
    }
    public function programbasic(){
        return view('everigo.programbasic');
    }

    public function feedback(){
        return view('everigo.feedback');
    }

}

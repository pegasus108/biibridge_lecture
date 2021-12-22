<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\InfoRepository;
use Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\Contactmail;

class InfoController extends Controller
{
    protected $info_repository;

    public function __construct(InfoRepository $info_repository)
    {
        $this->info_repository = $info_repository;
    }

    public function index()
    {
        $info_list = $this->info_repository->getInfoList(limit: 15, use_paginate: true);
        return view('info.index', ['info_list' => $info_list]);
    }

    public function detail($info_no)
    {
        $info = $this->info_repository->getInfoDetail($info_no);
        if (is_null($info)) {
            abort(404);
        }
        return view('info.detail', ['info' => $info]);
    }

    public function contact(){
        return view('info.contact');
    }

    public function confirm(Request $request){
        $request->validate([
            'email' => 'email',
            'title' => 'min:3|max:10',
            'value' => 'min:3|max:250'
        ]);
        return view('info.confirm',['contact_data' => $request]);
    }

    public function send(Request $request){
        DB::table('contact')->insert([
            'email' => $request->email,
            'name' => $request->name,
            'title' => $request->title,
            'value' => $request->value,
        ]);
        Mail::to(config('address.to'))->send(new ContactMail($request));
        $request->session()->regenerateToken();
        return view('info.send',['contact_data' => $request]);
    }
}

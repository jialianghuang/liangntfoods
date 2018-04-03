<?php

namespace inventory\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
   public function index(){
    return view('module');
   }


}

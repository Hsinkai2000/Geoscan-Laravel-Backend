<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Libearies\GeoscanLib;

class PagesController extends Controller
{
    public function input()
    {
        return response()->json(["message" => "INPUT CALLED"]);
    }
}
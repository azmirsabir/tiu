<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BaseController extends Controller
{
    public function index(Request  $request){
        try {
            return view('base.base',['nav_item'=>$request->get('nav_item')]);

        }catch (\Exception $e){
            Log::error(__FUNCTION__." : ". __CLASS__ .' : '. $e->getMessage().' : '.$e->getLine());
        }
    }
}

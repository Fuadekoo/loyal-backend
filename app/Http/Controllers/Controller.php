<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\URL;
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function saveImage($image,$path = 'public'){
        if($image){
            return null;
        }
        $filename =time().'.png';
        //save image
        \storage::disk($path)->put($filename,based64_decoder($image));

        //return the path
        //url is the base
        return URL::to('/').'/storage/'.$path.'/'.$filename;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function massage($status, $en, $ar)
    {
        if (app()->getLocale() == 'en') {
            session()->flash($status, $en);
        } elseif (app()->getLocale() == 'ar') {
            session()->flash($status, $ar);
        }
    }
}

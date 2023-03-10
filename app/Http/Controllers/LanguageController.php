<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config as FacadesConfig;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;


class LanguageController extends Controller
{

    public function index(Request $request)
    {
        $lang = $request->get('code');

        if (in_array($lang, config('app.locales'))) {

            config()->set('app.locale_prefix', $lang);
            session()->put('locale', $lang);
            app()->setLocale($lang);
        }

        return redirect(config('app.prefix', 'admin'));
    }


    public function switchLang($lang)
    {
        if (in_array($lang, config('app.locales'))) {
            config()->set('app.locale_prefix', $lang);
            session()->put('locale', $lang);
            app()->setLocale($lang);
        }
        return Redirect::back();
    }
}

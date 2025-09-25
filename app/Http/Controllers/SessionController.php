<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SessionController extends Controller
{
    public function setSession(Request $request)
    {
        $_request = $request->all();
        $key = array_keys($_request)[1];
        $value = array_values($_request)[1];

        session()->put((string)$key, (string)$value);
        session()->save();

        echo $key;
    }
}

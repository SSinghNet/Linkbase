<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class YourBaseController extends Controller
{
    public function __invoke(): View
    {
        return view('yourbase');
    }
}

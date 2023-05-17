<?php

namespace App\Http\Controllers;

use App\CommonData;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function show() {
        return Inertia::render('Home', CommonData::get());
    }
}

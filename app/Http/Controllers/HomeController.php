<?php

namespace App\Http\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    /**
     * Display the home page
     *
     * @return void
     */
    public function index()
    {
        view('pages.homepage', array('title' => "Home"));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display the about us page.
     */
    public function index()
    {
        return view('about.index');
    }

    /**
     * Display the contact us page.
     */
    public function contact()
    {
        return view('about.contact');
    }

    /**
     * Display the privacy policy page.
     */
    public function privacy()
    {
        return view('about.privacy');
    }

    /**
     * Display the terms of service page.
     */
    public function terms()
    {
        return view('about.terms');
    }
}

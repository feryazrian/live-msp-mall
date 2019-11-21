<?php

namespace Marketplace\Http\Controllers;

class LuckyDrawController extends Controller
{
    public function index()
    {
        return view('lucky_draw')->with([
            'pageTitle' => 'Lucky Draw',
            'seo_title' => '',
            'seo_description' => ''
        ]);
    }
}
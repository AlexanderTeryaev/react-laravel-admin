<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SpaController extends Controller
{
    /**
     * Get the SPA view.
     *
     * @return View
     */
    public function __invoke()
    {
        $config = [
            'images_url' => env('IMAGES_URL'),
            'api_base_url' => env('API_URL')
        ];
        return view('spa', compact('config'));
    }
}
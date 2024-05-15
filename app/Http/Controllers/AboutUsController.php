<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function index()
    {
        $aboutUsContent = [
            'company_info' => [
                ['title' => 'Our Company', 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'],
                ['title' => 'Mission', 'description' => 'Deliver exceptional services to our clients.'],
            ]
        ];

        return $this->success($aboutUsContent);
    }
}

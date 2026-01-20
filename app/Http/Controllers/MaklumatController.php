<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MaklumatController extends Controller
{
    //return page
    public function index(): Response
    {
        return Inertia::render('profile/maklumat');

    }
    
}

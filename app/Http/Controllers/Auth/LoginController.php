<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    //return page
    public function index(): Response
    {
        return Inertia::render('loginpage/daftar');

    }
    
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\crimes;

class CrimeController extends Controller
{
    public function index() {
        return crimes::all();
    }
}

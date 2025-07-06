<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // On hérite du Controller principal

class AdminController extends Controller
{
    public function index()
    {
        return view('dashboard.admin');
    }
}
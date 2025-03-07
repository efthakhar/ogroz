<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountingDashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('accounting.dashboard');
    }
}

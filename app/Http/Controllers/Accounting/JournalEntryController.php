<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Account;
use Illuminate\Http\Request;

class JournalEntryController extends Controller
{
    public function index(Request $request) {}

    public function datatable(Request $request) {}

    public function show($id) {}

    public function create(Request $request)
    {
        $accounts = Account::pluck('name', 'id');
        return view('accounting.journal-entries.create', compact('accounts'));
    }

    public function store(Request $request) {}

    public function edit($id) {}

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}

<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Account;
use App\Models\Accounting\JournalEntry;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function store(Request $request)
    {

        try {
            DB::beginTransaction();
            $journalEntry = JournalEntry::create($request->only('date', 'description'));

            $journalEntryLines = [];
            foreach ($request->journalEntryLines as $line) {
                $journalEntryLines[] =
                    [
                        'account_id' => $line['account_id'],
                        'debit' => $line['debit'] ?? 0,
                        'credit' => $line['credit'] ?? 0,
                    ];
            }
            $journalEntry->journalEntryLines()->createMany($journalEntryLines);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function edit($id) {}

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}

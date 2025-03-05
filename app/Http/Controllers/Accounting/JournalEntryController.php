<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Account;
use App\Models\Accounting\JournalEntry;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class JournalEntryController extends Controller
{
    public function index(Request $request) {}

    public function datatable(Request $request) {}

    public function show($id) {}

    public function create(Request $request)
    {
        return view('accounting.journal-entries.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'selected_accounts' => Account::whereIn('id', [...array_column($request->input('journalEntryLines'), 'account_id')])
                ->pluck('name', 'id')
        ]);

        $validator = Validator::make($request->all(), ['date' => 'required', 'description' => 'nullable|max:3']);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

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
            return redirect()->route('journal-entries.index')->with('success', 'Journal Entry Created Successfully');
     
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $journalEntry = JournalEntry::with('journalEntryLines')->find($id);
        $selected_accounts = Account::whereIn('id', [...$journalEntry->journalEntryLines->pluck('account_id')])
            ->pluck('name', 'id');
        return view('accounting.journal-entries.create', compact('journalEntry', 'selected_accounts'));
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'selected_accounts' => Account::whereIn('id', [...array_column($request->input('journalEntryLines'), 'account_id')])->pluck('name', 'id')
        ]);

        $validator = Validator::make(
            $request->all(),
            [
                'date' => 'required',
                'description' => 'nullable',
                'journalEntryLines.*.account_id' => 'required',
                'journalEntryLines.*' => [
                    function ($attribute, $value, $fail) {
                        $debit = $value['debit'] ?? null;
                        $credit = $value['credit'] ?? null;

                        if (empty($debit) && empty($credit)) {
                            $fail("Either debit or credit must be provided.");
                        }
                    }
                ],
                'journalEntryLines.*.debit' => ['numeric', 'nullable'],
                'journalEntryLines.*.credit' => ['numeric', 'nullable'],
            ],
            [
                'date.required' => 'Please enter the journal date.',
                'journalEntryLines.*.account_id.required' => 'Please select an account',
                'journalEntryLines.*.debit.numeric' => 'Invalid Number ',
                'journalEntryLines.*.credit.numeric' => 'Invalid Number ',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            $journalEntry = JournalEntry::find($id);
            $journalEntry->update($request->only('date', 'description'));
            $journalEntryLines = [];
            foreach ($request->journalEntryLines as $line) {
                $journalEntryLines[] =
                    [
                        'account_id' => $line['account_id'],
                        'debit' => $line['debit'] ?? 0,
                        'credit' => $line['credit'] ?? 0,
                    ];
            }
            $journalEntry->journalEntryLines()->delete();
            $journalEntry->journalEntryLines()->createMany($journalEntryLines);
            DB::commit();
            return redirect()->route('journal-entries.index')->with('success', 'Journal Entry Updated Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id) {}
}

<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Account;
use App\Models\Accounting\JournalEntry;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class JournalEntryController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('view journal entry');
        return view('accounting.journal-entries.index');
    }

    public function datatable(Request $request)
    {
        $this->authorize('view journal entry');

        $search = $request->search['value'];
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $page = (int)$start > 0 ? ($start / $length) + 1 : 1;
        $perPage = (int)$length > 0 ? $length : 10;

        $query =  JournalEntry::with(['journalEntryLines.account']) 
            ->with(['journalEntryLines' => function($query) {
                $query->orderByRaw('debit IS NULL, debit ASC')
              ->orderByRaw('credit IS NULL, credit ASC');
            }]);

        $query
            ->when(!empty($request->date), function ($q) use ($request) {
                $q->where('date', $request->date);
            })
            ->when(!empty($request->account_id), function ($q) use ($request) {
                $q->whereHas('journalEntryLines.account', function($q) use($request){
                    return $q->whereIn('account_id', ...$request->account_id);
                });   
            });

        $order = $request->get('order');

        if (!empty($order)) {
            $columnIndex = $order[0]['column'];
            $columnName = $request->get('columns')[$columnIndex]['data'];
            $columnSortOrder = $order[0]['dir'];
            $query->orderBy($columnName, $columnSortOrder);
        } else {
            $query->orderBy('date', 'desc');
        }

        $items = $query->paginate($perPage, ["*"], 'page', $page);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $items->total(),
            'recordsFiltered' => $items->total(),
            'data' => $items->getCollection()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'date' => date('d M Y', strtotime($item->date)),
                    'description' => $item->description,
                    'particulars' => $item->journalEntryLines->map(function($particular){
                        return $particular->account->name;
                    }),
                    'debit' => $item->journalEntryLines->map(function($particular){
                        return $particular->debit ? number_format($particular->debit) : null;
                    }),
                    'credit' => $item->journalEntryLines->map(function($particular){
                        return $particular->credit ? number_format($particular->credit) : null;
                    }),
                    'account_id' => $item->journalEntryLines->map(function($particular){
                        return $particular->account_id;
                    }),
                ];
            }),
        ]);
    }


    public function show($id)
    {
        $this->authorize('view journal entry');
    }

    public function create(Request $request)
    {
        $this->authorize('create journal entry');
        return view('accounting.journal-entries.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create journal entry');

        $request->merge([
            'selected_accounts' => Account::whereIn('id', [...array_column($request->input('journalEntryLines'), 'account_id')])
                ->pluck('name', 'id')
        ]);

        $validator = Validator::make($request->all(), ['date' => 'required', 'description' => 'nullable']);

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
                        'debit' => $line['debit'] ,
                        'credit' => $line['credit'] ,
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
        $this->authorize('edit journal entry');

        $journalEntry = JournalEntry::with('journalEntryLines')->find($id);
        $selected_accounts = Account::whereIn('id', [...$journalEntry->journalEntryLines->pluck('account_id')])
            ->pluck('name', 'id');
        return view('accounting.journal-entries.create', compact('journalEntry', 'selected_accounts'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('edit journal entry');

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
                        'debit' => $line['debit'] ,
                        'credit' => $line['credit'] ,
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

    public function destroy(Request $request, $id)
    {
        $this->authorize('delete journal entry');
        $ids = explode(',', $id);

        try {

            DB::beginTransaction();
            foreach ($ids as $id) {
                $journalEntry = JournalEntry::find($id);
                $journalEntry->journalEntryLines()->delete();
                $journalEntry->delete();
            }

            DB::commit();
            $message = 'Account Deleted Successfully';

            return $request->ajax()
                ? response()->json(['message' => $message], 200)
                : redirect()->route('accounts.index')->with('success', $message);
        } catch (Exception $e) {

            DB::rollBack();
            $errorMessage = $e->getMessage();
            return $request->ajax()
                ? response()->json(['message' => $errorMessage], 500)
                : redirect()->route('accounts.index')->withInput()->with('error', $errorMessage);
        }
    }
}

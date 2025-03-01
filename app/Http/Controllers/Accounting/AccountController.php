<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Account;
use App\Models\Accounting\AccountGroup;
use App\Services\Accounting\AccountGroupService;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('view account');
        $accountGroups = AccountGroup::get();
        return view('accounting.accounts.index', compact('accountGroups'));
    }

    public function datatable(Request $request)
    {
        $this->authorize('view account');

        $search = $request->search['value'];
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $page = (int)$start > 0 ? ($start / $length) + 1 : 1;
        $perPage = (int)$length > 0 ? $length : 10;

        $query =  Account::query();

        $query
            ->when(!empty($request->name), function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->name . '%');
            })
            ->when(!empty($request->account_group_id), function ($q) use ($request) {
                $q->whereIn(
                    'account_group_id',
                    [
                        $request->account_group_id,
                        ...AccountGroupService::getAllNestedChildrenIds($request->account_group_id)
                    ]
                );
            });

        $order = $request->get('order');

        if (!empty($order)) {
            $columnIndex = $order[0]['column'];
            $columnName = $request->get('columns')[$columnIndex]['data'];
            $columnSortOrder = $order[0]['dir'];
            $query->orderBy($columnName, $columnSortOrder);
        } else {
            $query->orderBy('accounts.id', 'desc');
        }

        $items = $query->paginate($perPage, ["*"], 'page', $page);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $items->total(),
            'recordsFiltered' => $items->total(),
            'data' => $items->getCollection()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'number' => $item->number,
                    'under' => $item->accountGroup?->name,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            }),
        ]);
    }

    public function dropdown(Request $request)
    {
        return Account::when($request->name, function ($q) use ($request) {
            $q->where('name', "LIKE", "%" . $request->name . "%");
        })
            ->pluck(DB::raw('select( concat("name", "-", "number") as name'), 'id');
    }

    public function show($id)
    {
        $this->authorize('view account');
        $account = Account::with('accountGroup')->find($id);
        return view('accounting.accounts.show', compact('account'));
    }

    public function create(Request $request)
    {
        $this->authorize('create account');
        $types = accountGroupTypesForDropdown();
        return view('accounting.account-groups.create', compact('types'));
    }


    public function store(Request $request)
    {
        $this->authorize('create account');

        $request->validate([
            'name' => ['required', 'string', Rule::unique('account_groups')],
            'type' => ['required', Rule::in(accountGroupTypes())],
        ]);

        try {
            DB::transaction(function () use ($request) {
                AccountGroup::create($request->only('name', 'type', 'parent_account_group_id'));
            });
            return redirect()->route('account-groups.index')->with('success', 'Account group created successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function edit(Request $request, $id)
    {
        $this->authorize('edit account');
        $types = accountGroupTypesForDropdown();
        $accountGroup = AccountGroup::find($id);
        return view('accounting.account-groups.create', compact('types', 'accountGroup'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('edit account');

        $request->validate([
            'name' => ['required', 'string', Rule::unique('account_groups')->ignore($id)],
            'type' => ['required', Rule::in(accountGroupTypes())],
            'parent_account_group_id' => [Rule::notIn([$id])]
        ], [
            'parent_account_group_id.not_in' => "This account can not be keept under itself"
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                AccountGroup::findOrFail($id)
                    ->update($request->only('name', 'type', 'parent_account_group_id'));
            });
            return redirect()->route('account-groups.index')->with('success', 'Account group updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        $this->authorize('delete account');
        $ids = explode(',', $id);

        try {

            /**
             * Before Delete Ensure
             * - total nested children == 0
             * - total nested account == 0
             */
            DB::beginTransaction();
            foreach ($ids as $id) {
                $accountGroup = AccountGroup::find($id);
                $accountGroup->delete();
            }

            DB::commit();
            $message = 'Account Group Deleted Successfully';

            return $request->ajax()
                ? response()->json(['message' => $message], 200)
                : redirect()->route('account-groups.index')->with('success', $message);
        } catch (Exception $e) {

            DB::rollBack();
            $errorMessage = $e->getMessage();
            return $request->ajax()
                ? response()->json(['message' => $errorMessage], 500)
                : redirect()->route('account-groups.index')->withInput()->with('error', $errorMessage);
        }
    }
}

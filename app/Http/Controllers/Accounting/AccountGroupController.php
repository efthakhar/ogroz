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

class AccountGroupController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('view account group');
        $types = accountGroupTypes();
        $accountGroups = AccountGroup::get();
        return view('accounting.account-groups.index', compact('types', 'accountGroups'));
    }

    public function datatable(Request $request)
    {
        $this->authorize('view account group');

        $search = $request->search['value'];
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $page = (int)$start > 0 ? ($start / $length) + 1 : 1;
        $perPage = (int)$length > 0 ? $length : 10;

        $query =  AccountGroup::query();
        $query
            ->when(!empty($request->name), function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->name . '%');
            })
            ->when(!empty($request->type), function ($q) use ($request) {
                $q->where('type', '=', $request->type);
            })
            ->when(!empty($request->parent_account_group_id), function ($q) use ($request) {
                $q->whereIn('id', [...AccountGroupService::getAllNestedChildrenIds($request->parent_account_group_id)]);
            });

        $order = $request->get('order');

        if (!empty($order)) {
            $columnIndex = $order[0]['column'];
            $columnName = $request->get('columns')[$columnIndex]['data'];
            $columnSortOrder = $order[0]['dir'];
            $query->orderBy($columnName, $columnSortOrder);
        } else {
            $query->orderBy('account_groups.id', 'desc');
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
                    'type' => $item->type,
                    'under' => $item->parent?->name,
                ];
            }),
        ]);
    }

    public function dropdown(Request $request)
    {
        return AccountGroup::when($request->type, function ($q) use ($request) {
            $q->where('type', $request->type);
        })->when($request->omit, function ($q) use ($request) {
            $q->whereNotIn('id', [$request->omit, ...AccountGroupService::getAllNestedChildrenIds($request->omit)]);
        })->get();
    }

    public function show($id)
    {
        $this->authorize('view account group');
        $accountGroup = AccountGroup::with('parent')->find($id);
        return view('accounting.account-groups.show', compact('accountGroup'));
    }

    public function create(Request $request)
    {
        $this->authorize('create account group');
        $types = accountGroupTypesForDropdown();
        return view('accounting.account-groups.create', compact('types'));
    }


    public function store(Request $request)
    {
        $this->authorize('create account group');

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
        $this->authorize('edit account group');
        $types = accountGroupTypesForDropdown();
        $accountGroup = AccountGroup::find($id);
        return view('accounting.account-groups.create', compact('types', 'accountGroup'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('edit account group');

        $request->validate([
            'name' => ['required', 'string', Rule::unique('account_groups')->ignore($id)],
            'type' => ['required', Rule::in(accountGroupTypes())],
            'parent_account_group_id' => [Rule::notIn([$id])]
        ], [
            'parent_account_group_id.not_in' => "This account group can not be keept under itself"
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
        $this->authorize('delete account group');
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

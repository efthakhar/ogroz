<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('view role');
        return view('setting.role.index');
    }

    public function datatable(Request $request)
    {
        $this->authorize('view role');

        $search = $request->search['value'];
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $page = (int)$start > 0 ? ($start / $length) + 1 : 1;
        $perPage = (int)$length > 0 ? $length : 10;

        $query =  Role::query();

        $query
            ->when(!empty($search), function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            });

        $order = $request->get('order');

        if (!empty($order)) {
            $columnIndex = $order[0]['column'];
            $columnName = $request->get('columns')[$columnIndex]['data'];
            $columnSortOrder = $order[0]['dir'];
            $query->orderBy($columnName, $columnSortOrder);
        } else {
            $query->orderBy('roles.id', 'desc');
        }

        $companies = $query->paginate($perPage, ["*"], 'page', $page);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $companies->total(),
            'recordsFiltered' => $companies->total(),
            'data' => $companies->getCollection()->map(function ($company) {
                return [
                    'id' => $company->id,
                    'name' => $company->name
                ];
            }),
        ]);
    }

    public function show($id)
    {
        $this->authorize('view role');
        $role = Role::with('permissions')->find($id);
        return view('setting.role.show', compact('role'));
    }

    public function create(Request $request)
    {
        $this->authorize('create role');
        $permissionGroups = include_once(base_path('database/data/config/permissions.php'));
        return view('setting.role.create', compact('permissionGroups'));
    }

    public function store(Request $request)
    {
        $this->authorize('create role');

        $validatedData = $request->validate([
            'name' => 'required|string|unique:roles',
            'permissions' => 'required|array|min:1',
        ]);

        try {
            DB::beginTransaction();
            $role = Role::create(['name' => $validatedData['name']]);
            $role->givePermissionTo(...$request->permissions);
            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Role Created Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('roles.create')->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->authorize('edit role');
        $permissionGroups = include_once(base_path('database/data/config/permissions.php'));
        $role = Role::with('permissions')->find($id);
        $assignedPermissions = $role->permissions->pluck('name')->toArray() ?? [];
        return view('setting.role.create', compact('permissionGroups', 'role', 'assignedPermissions'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('edit role');

        $validatedData = $request->validate([
            'name' => ['required', 'string', Rule::unique('roles')->ignore($id)],
            'permissions' => 'required|array|min:1',
        ]);

        try {
            DB::beginTransaction();
            $role = Role::find($id);
            $role->name = $validatedData['name'];
            $role->syncPermissions([]);
            $role->givePermissionTo(...$request->permissions);
            $role->save();
            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Role Updated Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        $this->authorize('delete role');
        $ids = explode(',', $id);

        try {
            DB::beginTransaction();
            foreach ($ids as $id) {
                $role = Role::find($id);
                $role->name == 'Super Admin'
                    ? throw new Exception("Role: $role->name Cann't be Deleted")
                    : $role->delete();
            }
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
            DB::commit();
            $message = 'Role Deleted Successfully';

            return $request->ajax()
                ? response()->json(['message' => $message], 200)
                : redirect()->route('roles.index')->with('success', $message);
        } catch (Exception $e) {
            DB::rollBack();
            $errorMessage = $e->getMessage();
            return $request->ajax()
                ? response()->json(['message' => $errorMessage], 500)
                : redirect()->route('roles.index')->withInput()->with('error', $errorMessage);
        }
    }
}

<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\User;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use AuthorizesRequests;


    public function index()
    {
        $this->authorize('view user');
        return view('setting.user.index');
    }


    public function datatable(Request $request)
    {
        $this->authorize('view user');

        $search = $request->search['value'];
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $page = (int)$start > 0 ? ($start / $length) + 1 : 1;
        $perPage = (int)$length > 0 ? $length : 10;

        $query =  User::query();

        $query
            ->when(!empty($search), function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%');
            });

        $order = $request->get('order');

        if (!empty($order)) {
            $columnIndex = $order[0]['column'];
            $columnName = $request->get('columns')[$columnIndex]['data'];
            $columnSortOrder = $order[0]['dir'];
            $query->orderBy($columnName, $columnSortOrder);
        } else {
            $query->orderBy('users.id', 'desc');
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
                    'email' => $item->email,
                    'active' => $item->active,
                ];
            }),
        ]);
    }


    public function show($id)
    {
        $this->authorize('view user');
        $user = User::with('roles')->find($id);
        return view('setting.user.show', compact('user'));
    }

    public function create(Request $request)
    {
        $this->authorize('create user');
        $roles = Role::pluck('name', 'id');
        return view('setting.user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->authorize('create user');

        $validatedData = $request->validate([
            'email' => ['required', 'string', Rule::unique('users')],
            'name' => ['required', 'string'],
            'active' => ['nullable', Rule::in([0, 1])],
            'password' => ['required', 'min:5'],
            'roles' => [Rule::array()]
        ]);

        try {

            DB::beginTransaction();

            $user = new User();
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->active = $validatedData['active'];
            $user->password = Hash::make($validatedData['password']);
            $user->save();

            $roles = Role::whereIn('id', $validatedData['roles'])->get();
            $user->syncRoles($roles);

            DB::commit();

            return redirect()->route('users.index')->with('success', 'User Created Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('users.create')->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        $this->authorize('create user');
        $roles = Role::pluck('name', 'id');
        $user = User::with('roles')->find($id);
        return view('setting.user.create', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('edit user');

        $validatedData = $request->validate([
            'email' => ['required', 'string', Rule::unique('users')->ignore($id)],
            'name' => ['required', 'string'],
            'active' => ['nullable', Rule::in([0, 1])],
            'password' => ['nullable', 'min:5'],
            'roles' => ['nullable', 'array']
        ]);

        try {

            DB::beginTransaction();

            $user = User::find($id);
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->active = $validatedData['active'];

            if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }

            $user->save();

            $roles = Role::whereIn('id', $validatedData['roles'] ?? [])->get();
            $user->syncRoles($roles);

            DB::commit();

            return redirect()->route('users.index')->with('success', 'User Updated Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('users.create')->withInput()->with('error', $e->getMessage());
        }
    }


    public function destroy(Request $request, $id)
    {
        $this->authorize('delete user');
        $ids = explode(',', $id);

        try {
            DB::beginTransaction();
            foreach ($ids as $id) {
                $user = User::find($id);
                $user->hasRole('Super Admin') && User::role('Super Admin')->count() == 1
                    ? throw new Exception("User Cann't be Deleted. Because after deleting this user there will be no super admin for this system.")
                    : $user->delete();
            }

            DB::commit();
            $message = 'User Deleted Successfully';

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



    /**
     * Return form to see & update authenticated user's data
     */
    public function profile()
    {
        $user = User::find(auth()->user()->id);
        return view('setting.user.profile', compact('user'));
    }

    /**
     * Handle authenticated user's submitted profile data
     */
    public function profileSubmit(Request $request)
    {
        try {

            $request->validate([
                'old_password' => 'required_with:new_password',
                'new_password' => 'nullable|min:6',
            ]);


            $user = auth()->user();


            if ($request->filled('name')) {
                $user->name = $request->name;
            }


            if ($request->filled('new_password')) {
                if (!Hash::check($request->old_password, $user->password)) {
                    throw new Exception("Old password did not match.");
                }

                $user->password = Hash::make($request->new_password);
            }


            $user->save();

            return redirect()->back()->with('success', 'Profile updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}

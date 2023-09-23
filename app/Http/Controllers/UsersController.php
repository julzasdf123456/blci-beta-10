<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUsersRequest;
use App\Http\Requests\UpdateUsersRequest;
use App\Repositories\UsersRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\Users;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Flash;
use Response;

class UsersController extends AppBaseController
{
    /** @var  UsersRepository */
    private $usersRepository;

    public function __construct(UsersRepository $usersRepo)
    {
        $this->middleware('auth');
        $this->usersRepository = $usersRepo;
    }

    /**
     * Display a listing of the Users.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if (auth()->user()->can('view user')) {
            $users = $this->usersRepository->all();

            return view('users.index')
                ->with('users', $users);
        } else {
            abort(403);
        }
        
    }

    /**
     * Show the form for creating a new Users.
     *
     * @return Response
     */
    public function create()
    {
        if (auth()->user()->can('create user')) {
            return view('users.create');
        } else {
            abort(403);
        }        
    }

    /**
     * Store a newly created Users in storage.
     *
     * @param CreateUsersRequest $request
     *
     * @return Response
     */
    public function store(CreateUsersRequest $request)
    {
        $input = $request->all();

        $users = $this->usersRepository->create($input);

        Flash::success('Users saved successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Display the specified Users.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $users = $this->usersRepository->find($id);

        $userModel = User::find($id);

        $userPermissions = $userModel->getAllPermissions();

        if (empty($users)) {
            Flash::error('Users not found');

            return redirect(route('users.index'));
        }

        return view('users.show', ['users' => $users, 'permissions' => $userPermissions]);
    }

    /**
     * Show the form for editing the specified Users.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $users = $this->usersRepository->find($id);

        if (empty($users)) {
            Flash::error('Users not found');

            return redirect(route('users.index'));
        }

        return view('users.edit')->with('users', $users);
    }

    /**
     * Update the specified Users in storage.
     *
     * @param int $id
     * @param UpdateUsersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUsersRequest $request)
    {
        $users = $this->usersRepository->find($id);

        if (empty($users)) {
            Flash::error('Users not found');

            return redirect(route('users.index'));
        }

        $users = $this->usersRepository->update($request->all(), $id);

        Flash::success('Users updated successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified Users from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $users = $this->usersRepository->find($id);

        if (empty($users)) {
            Flash::error('Users not found');

            return redirect(route('users.index'));
        }

        $this->usersRepository->delete($id);

        Flash::success('Users deleted successfully.');

        return redirect(route('users.index'));
    }

    public function addUserRoles($id) {
        $user = User::find($id);

        $roles = Role::all();

        return view('users.add_user_roles', ['user' => $user, 'roles' => $roles]);
    }

    public function createUserRoles(Request $request) {
        $user = User::find($request->userId);

        $user->syncRoles($request->input('roles', []));

        // return redirect(route('users.add_user_permissions', ['id' => $user->id, 'roles' => $request->input('roles', [])]));
        return redirect('users/' . $request->userId);
    }

    public function addUserPermissions($id) {
        $user = User::find($id);

        $permissions = Permission::all();

        return view('users.add_user_permissions', ['user' => $user, 'permissions' => $permissions]);
    }

    public function createUserPermissions(Request $request) {
        $user = User::find($request->userId);

        $user->syncPermissions($request->input('permissions', []));

        return redirect('users/' . $request->userId);
    }

    public function removePermission($id, $permission) {
        $user = User::find($id);

        $user->revokePermissionTo($permission);

        return redirect('users/' . $id);
    }

    public function clearRoles($id) {
        $user = User::find($id);

        $user->syncRoles([]);
        $user->syncPermissions([]);

        return redirect('users/' . $id);
    }

    public function authenticate(Request $request) {
        $users = User::where('username', $request['username'])->first();
        if ($users != null) {
            if (Hash::check($request['password'], $users->password)) {
                if ($users->can($request['permission'])) {
                    return response()->json(['res' => 'ok'], 200);
                } else {
                    return response()->json(['res' => 'error'], 406);
                }              
            } else {
                return response()->json(['res' => 'error'], 405);
            }
        } else {
            return response()->json(['res' => 'error'], 404);
        }        
    } 
}

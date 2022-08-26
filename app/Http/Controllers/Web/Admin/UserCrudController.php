<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Filters\QueryFilters;
use App\Filters\UserFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class UserCrudController extends Controller
{
    public function index()
    {
        $users = UserResource::collection(User::filter(app(UserFilters::class))->paginate(request()->perPage ?? 10));
        return Inertia::render('Admin/User/Index', [
            "users" => $users,
        ]);
    }
    public function create()
    {
        return Inertia::render('Admin/User/Create', [

        ]);
    }
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        return redirect()->route('admin.users.index')->with('successMessage', 'Succssfully Created');
    }
    public function edit(User $user)
    {
        $user = new UserResource($user);
        return Inertia::render('Admin/User/Edit', [
            "user" => $user,
        ]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);
        $user->name = $request['name'];
        $user->email = $request['email'];
        if($request['password']) {
            $user->password = Hash::make($request['password']);
        }
        $user->save();
        return redirect()->route('admin.users.index')->with('successMessage', "Successfully Updated");
    }
    public function destroy($id)
    {
        $user = User::find($id);
        if($user->hasRole('admin')) {
            return back()->with('errorMessage', "You cannot delete super user");
        }
        $user->delete();
        return back()->with('succesMessage', 'Successfully Deleted');
    }
}

<?php

namespace App\Http\Controllers;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $entries = $request->input('number_of_entries', 10); // Default: 10 per page

        $users = User::query();

        if ($search) {
            $users->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                      ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }

        $users = $users->paginate($entries)->appends($request->except('page'));

        return view('users.index', compact('users', 'search', 'entries'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|string',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            Alert::error('Error', $error)->persistent('Dismiss');
            return back()->withInput();
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = $request->role;
        $user->status = 'Active';
        $user->save();

        Alert::success('Success', 'User successfully saved!')->persistent('Dismiss');
        return back();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return array(
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status,
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'email|unique:users,email,' . $id
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->status = $request->status;
        $user->save();

        Alert::success('Successfully Update')->persistent('Dismiss');
        return back();
    }

    public function userChangePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'confirmed|min:6',
        ]);

        $user = User::findOrFail($id);
        $user->password = bcrypt($request->password);
        $user->save();

        Alert::success('Successfully Change Password')->persistent('Dismiss');
        return back();
    }
}

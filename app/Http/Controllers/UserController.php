<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

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
        $request->validate([
            'password' => 'confirmed|min:6',
            'email' => 'email|unique:users,email',
        ]);

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();

    }
    
}

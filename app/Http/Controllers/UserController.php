<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = DB::table('users')
            ->when($request->input('name'), function ($query, $name) {
                return $query->where('name', 'like', '%' . $name . '%');
            })
            ->paginate(10);

        return view('pages.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
            'password' => ['required', 'min:6'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['nullable'],
            'phone_number' => ['nullable']
        ]);

        $data['password'] = Hash::make($request->password);

        \App\Models\User::create($data);

        return redirect()->route('user.index')->with('success', 'User successfully created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = \App\Models\User::findOrFail($id);

        return view('pages.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => ['nullable'],
            'email' => ['nullable', 'email', 'unique:users,email,' . $id . ',id'],
            'password' => ['nullable'],
            'phone_number' => ['nullable'],
            'role' => ['nullable'],
        ]);

        $user = User::findOrFail($id);

        if ($request['password']) {
            $data['password'] = Hash::make($request['password']);
        } else {
            $data['password'] = $user->password;
        }

        $user->update($data);

        return redirect(route('user.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        $user->delete();
        return redirect()->route('user.index')->with('success', 'User successfully deleted');
    }
}

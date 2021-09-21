<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use jeremykenedy\LaravelRoles\Models\Role;

class UserController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth');
        $this->middleware('permission:view.users')->only(['index', 'show']);
        $this->middleware('permission:create.users')->only(['create', 'store']);
        $this->middleware('permission:edit.users')->only(['edit', 'update']);
        $this->middleware('permission:delete.users')->only('destroy');
        // $this->middleware('subscribed')->except('store');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = User::all();
        
        return view('users.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('level', '<=', Auth::user()->level())->get();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            "name" => "required|min:3|string",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:6"
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        if(isset($request->role)) {
            $role = config('roles.models.role')::where('name', '=', $request->role)->first();  //choose the default role upon user creation.
            $user->attachRole($role);
        }

        return redirect()->route('users.index')->with('success','User created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = User::find($id);
        $roles = Role::all();

        return view('users.view', compact('item', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = User::find($id);
        $roles = Role::where('level', '<=', Auth::user()->level())->get();

        return view('users.edit', compact('item', 'roles'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validated = $this->validate($request, [
            "name" => "min:3|string",
            "email" => "email|unique:users,email,".$id,
            "password" => "nullable|min:6"
        ]);

        $user = User::find($id);

        $updateData = [
            "name" => $request->name,
            "email" => $request->email,
        ];

        if(isset($request['password'])) {
            $request->password = Hash::make($request->password);

            $updateData['password'] = $request->password;
        }
        $user->update($updateData);

        $user->detachAllRoles();
        if(isset($request->role)) {
            $role = config('roles.models.role')::where('name', '=', $request->role)->first();  //choose the default role upon user creation.
            $user->attachRole($role);
        }

        return redirect()->route('users.index')->with('success','User updated successfully!');;;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id)->delete();

        return redirect()->route('users.index')->with('success','User deleted successfully!');
    }
}

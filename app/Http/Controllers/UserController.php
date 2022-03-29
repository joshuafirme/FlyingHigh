<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Hash;
use Utils;
use Auth;

class UserController extends Controller
{
    private $page = "System Users";

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (User::isPermitted($this->page)) { return $next($request); }
            return abort(401);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $users = User::select('users.*', 'user_role.name as role')
            ->leftJoin('user_role', 'user_role.id', '=', 'users.access_level')->paginate(10);

        $page_title = "Users";
        $roles = Role::all();
        return view('users.index', compact('page_title', 'users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    public function search()
    {
        $key = isset(request()->key) ? request()->key : "";
        $users = User::where('name', 'LIKE', '%' . $key . '%')
                    ->orWhere('email', 'LIKE', '%' . $key . '%')
                    ->paginate(5);
        $page_title = "Users | Pamilya Muna Party List";
        return view('users.index', compact('page_title', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['password'] = Hash::make($request->password);
        //$request['status'] = 1;
        User::create($request->all());
        return redirect()->back()->with('success', 'User was successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $except_values = ['_token', 'firstname', 'lastname', 'middlename'];

        if ($request->password && !empty($request->password)) {
            $request['password'] = Hash::make($request->password);
        }
        else {
            array_push($except_values, 'password');
        }

        User::where('id',$id)->update($request->except($except_values));
        return redirect()->back()->with('success', 'User was updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->delete()) {
            return response()->json([
                'status' =>  'success',
                'message' => 'user was deleted.'
            ], 200);
        }

        return response()->json([
            'status' =>  'error',
            'message' => 'Deleting user failed.'
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use Cache;

class RoleController extends Controller
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
    public function index(Role $role) { 
        $page_title = "Role | ";
        $role = Role::paginate(5);
        return view('role.index', compact('page_title', 'role'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($this->isRoleExists($request->name)) { 
            return redirect()->back()->with('danger', 'Role is already exists.');
        }

        $request['permission'] = implode(', ', $request->permission);

        Role::create($request->all());

        return redirect()->back()->with('success', 'Data was saved successfully.');
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
        $request['permission'] = implode(', ', $request->permission);
        Role::where('id',$id)->update($request->except('_token'));

        return redirect()->back()->with('success', 'Data was updated successfully.');
    }

    public function isRoleExists($name) {
        $res = Role::where('name', $name)->value('id');
        return $res ? true : false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if ($role->delete()) {
            return response()->json([
                'status' =>  'success',
                'message' => 'Data was deleted.'
            ], 200);
        }

        return response()->json([
            'status' =>  'error',
            'message' => 'Deleting data failed.'
        ], 200);
    }
}

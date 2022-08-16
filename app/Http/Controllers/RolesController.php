<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $roles = RoleResource::collection(Role::where('slug', '!=', 'super_admin')->get());
            $count = count(Role::all());

            return response()->json(['success' => true, 'data' => ['role' => $roles, 'count' => $count,]], 200);
        } catch (\Exception $th) {
            $this->exceptionHandler($th);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'title' => 'required:|unique:roles',

                'slug' => 'required|unique:roles',

            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' =>  $validator->errors()]);
            }


            $role = new Role();
            $role->title = $request->title;
            $role->slug = $request->slug;
            $role->save();
            $role->permissions()->sync($request->permission, []);
            return response()->json(['success' => true, 'message' => 'role ' . $role->title . ' Added']);
        } catch (\Exception $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $role = Role::findorFail($id);
            return response()->json(['success' => true, 'data' => ['role' => $role]]);
        } catch (\Exception $th) {
            $this->exceptionHandler($th);
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $role=Role::findorFail($id);
            $role->delete();
            return response()->json(['success'=>true,'message'=>'Role '.$role->name.'deleted ','data'=>$role]);
        } catch (\Exception $th) {
           return $this->exceptionHandler($th);
        }
    }

    public function exceptionHandler($th)
    {

        return response()->json(['success' => false, 'error' => $th->getMessage()]);
    }
}

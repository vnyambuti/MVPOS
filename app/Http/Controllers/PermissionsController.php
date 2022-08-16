<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $permission = Permission::where('slug', '!=', 'access_configuration')->where('slug', '!=', 'manage_permissions')->orderBy('created_at', 'DESC')->take(10)->get();
            $count = count(Permission::all());
            return response()->json(['success' => true, 'data' => ['permissions' => $permission, 'count' => $count]]);
        } catch (\Exception $th) {
            $this->exceptionhandler($th);
        }
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
        try {
            $rules = [
                'title' => 'required:|unique:permissions',

                'slug' => 'required|unique:permissions',

            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' =>  $validator->errors()]);
            }


            $permission = new Permission();
            $permission->title = $request->title;
            $permission->slug = $request->slug;
            $permission->save();
            return response()->json(['success' => true, 'message' => 'permission ' . $permission->title . ' Added']);
        } catch (\Exception $th) {
            $this->exceptionhandler($th);
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
            $permission = Permission::findorFail($id);
            return response()->json(['success' => true, 'data' => ['permission' => $permission]]);
        } catch (\Exception $th) {
           $this->exceptionhandler($th);
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
        try {
            $rules = [
                "title" => "required",
                "slug" => "required"
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' =>  $validator->errors()]);
            }

            $permission = Permission::findorFail($id);
            $permission->title = $request->title;
            $permission->slug = $request->slug;
            $permission->save();

            return response()->json(['success' => true, 'message' => 'Permission ' . $permission->title . ' Updated', 'data' => ['permission' => $permission]]);
        } catch (\Exception $th) {
            $this->exceptionhandler($th);
        }
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
            $permission = Permission::findorFail($id);
            $permission->delete();
            return response()->json(['success' => true, 'message' => 'permission ' . $permission->title . ' deleted', 'data' => ['role' => $permission]]);
        } catch (\Exception $th) {
            $this->exceptionhandler($th);
        }
    }

    public function exceptionhandler($th)
    {
        return response()->json(['success' => false, 'error' => $th->getMessage()]);
    }
}

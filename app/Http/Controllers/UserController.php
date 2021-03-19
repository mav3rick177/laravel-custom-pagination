<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // Form field names
        $fields = ['sort', 'perPage', 'from', 'to']; 

        // Cache field values
        $cache = init_paginator_cache($fields);
        

        $columns = ['id','name','email', 'dob']; 

        // field to use as a cursor (Database Table field)
        $cursor = ['dob']; 

        $sort = isset($cache['sort']) ? $cache['sort'] : '>';
        $perPage = isset($cache['perPage']) ? $cache['perPage'] : 10;
        $from = isset($cache['from']) ? $cache['from'] : null;
        $to = isset($cache['to']) ? $cache['to'] : null;

        //Get Users Where Birth Date of Birth is between '1968-02-07 13:45:00' and '1970-02-07 21:45:00' 
        $query = User::select($columns)
                    ->whereBetween('dob', [$from, $to]);
                   // ->where('email', 'like', '%example.net%');
        
        

        // Fonction 
        $result = custom_paginator($query, $cursor, $cache, $sort, $perPage);
        

        return view('users')->with($result);
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
        //
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
        //
    }
}

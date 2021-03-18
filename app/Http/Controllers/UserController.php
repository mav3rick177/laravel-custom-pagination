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
        // Cursor is used to navigate to the next and previous pages
        $cursor = null;
        // Get 'Next' or 'Previous' 'date of birth' cursor parameter from Url
        if(request()->id){
            //Add Parameters to the cursor array
            $cursor = [
                "id" => request()->id
            ];
        }
        //dd($cursor);
        //Get Users Where Birth Date of Birth is between '1967-02-07 21:45:42' and '1976-02-07 21:45:42' 
        //$query= User::whereBetween('id', ['1967-02-07 21:45:42', '1970-02-07 21:45:42'])
        // fonction 
        $paginator = User::whereBetween('id', ['1967-02-07 21:45:42', '1970-02-07 21:45:42'])
                    ->lampager()
                    ->limit(10); // 10 Per Page
        
        // Handle 'Next' Button Click
        if(request()->direction == "next" || request()->direction == null){
            // Order by Date Of Birth 
            $paginator = $paginator->orderByDesc('id')
                    ->seekable(); // To get 'Previous Cursor'
            // If the cursor is not empty we gonna use it to go to the next page
            if($cursor != null){
                $paginator = $paginator
                    ->paginate($cursor); 
            }
            // Else return the same data
            else{
                $paginator = $paginator
                    ->paginate();
            }
        } 
        // Handle 'Previous' Button Click
        else{
            $paginator = $paginator->backward() // Use backward method to change the direction of the navigation
                    ->orderByDesc('id') // Order by Date Of Birth 
                    ->seekable(); // To get 'Previous Cursor'
            // If the cursor is not empty we gonna use it to go to the previous page
            if($cursor != null){
                $paginator = $paginator
                    ->paginate($cursor);
            }
            else{
                $paginator = $paginator
                    ->paginate();
            }
        }  
        /*
        ** Router options 
        ** ['prev', $paginator->previousCursor['field1'], $paginator->previousCursor['field2'], ...]
        ** ['next', $paginator->nextCursor['field1'], $paginator->nextCursor['field2'], ...]
        */
        $prev_btn_router_options = ['prev', $paginator->previousCursor['id']];  
        $next_btn_router_options = ['next', $paginator->nextCursor['id']];   
        $route = 'users.list';
        //dd($prev_btn_router_options, $next_btn_router_options, $route);
        // Return paginator
        return view('users')->with([
                'users' => $paginator,
                'route' => $route,
                'prev_btn_router_options' => $prev_btn_router_options,
                'next_btn_router_options' => $next_btn_router_options
            ]);
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

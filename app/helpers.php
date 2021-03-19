<?php
use Illuminate\Support\Facades\Route;

if(!function_exists('custom_paginator')){
    
    /* Custom pagination System Based on Lampager package
    * @param  Query     $query
    * @param  Array     $cursor
    * @param  Char      $sort 
    * @param  Integer   $sort
    * @param  Boolean   $seekable
    * @return Array     $result
    */
    function custom_paginator($query, $cursor = null, $sort = '>', $perPage = 10, $seekable = true)
    {
        // Cursor is used to navigate to the next or previous 'pages'
        $newCursor = null;
        // Get cursor parameters from HTTP Request
        foreach($cursor as $parameter){
            //dd($parameter);
            if(request($parameter)){
                //Add parameter to the cursor array
                $newCursor[$parameter] = request($parameter);
            }
        }
        //dd($newCursor);
        
        // Create a new pagination
        $paginator = $query->lampager()
                    ->limit($perPage); // Set Number of elements Per Page (default=10)
        
        // ...
        foreach($cursor as $parameter){
            if($sort == '>')
                $paginator = $paginator->orderBy($parameter);
            else
                $paginator = $paginator->orderByDesc($parameter);
        }

        if($seekable)
            $paginator = $paginator->seekable(); // Get 'Previous Cursor' to be able to navigate backwards

        // Handle 'Next' Button Click
        if(request()->direction == "next" || request()->direction == null){
            $paginator = $paginator->forward(); // Use forward method to change the direction of the navigation
        } 
        // Handle 'Previous' Button Click
        else{
            $paginator = $paginator->backward(); // Use backward method to change the direction of the navigation
        }

        if($newCursor != null){
            $paginator = $paginator
                ->paginate($newCursor);
        }
        else{
            $paginator = $paginator
                ->paginate();
        }

        /*
        ** Router options 
        ** ['prev', $paginator->previousCursor['field1'], $paginator->previousCursor['field2'], ...]
        ** ['next', $paginator->nextCursor['field1'], $paginator->nextCursor['field2'], ...]
        */
        $prev_btn_router_options = ['prev'];
        foreach($cursor as $parameter){
            array_push($prev_btn_router_options, $paginator->previousCursor[$parameter]);
        }
    
        $next_btn_router_options = ['next'];
        foreach($cursor as $parameter){
            array_push($next_btn_router_options, $paginator->nextCursor[$parameter]);
        }
        
        // Get current route name...
        $route = Route::currentRouteName();
        
        $result = [
            'items' => $paginator,
            'route' => $route,
            'prev_btn_router_options' => $prev_btn_router_options,
            'next_btn_router_options' => $next_btn_router_options
        ];

        return $result;
    }
}
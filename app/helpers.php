<?php
use Illuminate\Support\Facades\Route;

if(!function_exists('init_paginator_cache')){
    function init_paginator_cache($fields = null){
        //init cache
        $cache = count($fields) > 0 ? [] : null;
        
        // If form is submitted...
        if (request()->isMethod('POST')) {
            // To do
            foreach ($fields as $fieldName) {
                $cache[$fieldName] = request($fieldName);
            }
            //dd($cache);
        }
        else //Retrieve old cache
        {
            $state_array = null;
            // Decode State
            if(request('state')){
                $state_base64 = request('state');
                $state_decoded = base64_decode($state_base64);
                $state_array = json_decode($state_decoded,true);
            }
            if(isset($state_array['cache'])){
                foreach ($state_array['cache'] as $key => $value) {
                    $cache[$key] = $value;
                }
            }
        }

        return $cache;
    }
}
if(!function_exists('custom_paginator')){
    /* Custom pagination System Based on Lampager package
    * @param  Query     $query
    * @param  Array     $cursor
    * @param  Char      $sort 
    * @param  Integer   $sort
    * @param  Boolean   $seekable
    * @return Array     $result
    */
    function custom_paginator($query, $cursor = null, $cache = null, $sort = '>', $perPage = 10, $seekable = true)
    {
        if($cache == [] || $cache == null){
            $cache['sort'] = '>';
            $cache['perPage'] = 10;
        }
            //dd("hmmm cache problem");
        $state_array = null;
        // Decode State
        if(request('state')){
            $state_base64 = request('state');
            $state_decoded = base64_decode($state_base64);
            $state_array = json_decode($state_decoded,true);
        }
        //dd($state_array);

        // Cursor is used to navigate to the next or previous 'pages'
        $newCursor = null;
        // Prepare Cursors
        foreach($cursor as $parameter){
            //dd($parameter);
            if($state_array['cursor'][$parameter]){
                //Add parameter to the cursor array
                $newCursor[$parameter] = $state_array['cursor'][$parameter];
            }
        }
        
        //dd($newCursor);
        if($perPage == null)
            $perPage = 10;

        // Create a new pagination
        $paginator = $query->lampager()
                    ->limit($perPage); // Set Number of elements Per Page (default=10)
        
        // Sort Options
        foreach($cursor as $parameter){
            if($sort == '>' || $sort == null)
                $paginator = $paginator->orderBy($parameter);
            else
                $paginator = $paginator->orderByDesc($parameter);
        }

        // Get 'Previous Cursor' to be able to navigate backwards
        if($seekable)
            $paginator = $paginator->seekable(); 

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
        
        // Extract cursors from paginator
        $cursors = (array)$paginator;
        unset($cursors['records']);
        //dd($cursors);

        // Get current route name...
        $route = Route::currentRouteName();

        // Next Btn State...
        $state_next = [
            'cursor' => $cursors['nextCursor'],
            'cache' => $cache
        ];

        // Previous Btn State...
        $state_prev = [
            'cursor' => $cursors['previousCursor'],
            'cache' => $cache
        ];

        //dd($state);
        // Encode States
        $base64_next_state = base64_encode(json_encode($state_next));
        $base64_prev_state = base64_encode(json_encode($state_prev));
        
        /*
        ** Router options 
        ** ['prev', $state]
        ** ['next', $state]
        */
        $prev_btn_router_options = ['prev', $base64_prev_state];
        $next_btn_router_options = ['next', $base64_next_state];

        $result = [
            'items' => $paginator,
            'route' => $route,
            'prev_btn_router_options' => $prev_btn_router_options,
            'next_btn_router_options' => $next_btn_router_options,
            'cache' => $cache
        ];
        //dd($result);
        return $result;
    }
}
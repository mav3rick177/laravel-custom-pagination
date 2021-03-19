<?php
use Illuminate\Support\Facades\Route;

if(!function_exists('init_paginator_cache')){
    /* This function is supposed to cache 'Form' values...
    *
    * @param  Array     $fields
    * @return Array     $result
    *
    */
    function init_paginator_cache($fields = null){
        //init cache
        $cache = count($fields) > 0 ? [] : null;
        
        // If form is submitted...
        // Cache Form values
        if (request()->isMethod('POST')) {
            foreach ($fields as $fieldName) {
                if(request($fieldName)){
                    $cache[$fieldName] = request($fieldName);
                }
            }
        }
        else // else we have to retrieve old cache from the state 
        {
            $state_array = null;

            // Decode The State
            if(request('state')){
                $state_base64 = request('state');
                $state_decoded = base64_decode($state_base64);
                $state_array = json_decode($state_decoded,true);
            }

            // Append the state cache key/value pairs to the new cache...
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
    * @param  Array     $field
    * @param  Char      $sort 
    * @param  Integer   $sort
    * @param  Boolean   $seekable
    * @return Array     $result
    */
    function custom_paginator($query, $field = 'id', $cache = null, $sort = '>', $perPage = 10, $seekable = true)
    {
        /*
        ** Setup Default values
        */
        if($sort == null)
            $sort = '>';

        if($field == null)
            $field = 'id';
        
        if($perPage == null)
            $perPage = 10;
        
        if($cache == [] || $cache == null){
            $cache['sort'] = $sort;
            $cache['perPage'] = $perPage;
        }

        /*
        ** Extract Cursor from the State route parameter
        ** Cursor is used as a reference to navigate to the next or previous 'pages'...
        */

        $state_array = null;

        // Decode the State
        if(request('state')){
            $state_base64 = request('state');
            $state_decoded = base64_decode($state_base64);
            $state_array = json_decode($state_decoded,true);
        }

        $newCursor = null;
        
        // Add cursor from state to the newCursor array
        if($state_array['cursor'][$field]){
            $newCursor[$field] = $state_array['cursor'][$field];
        }
        

        // Create a new paginator
        $paginator = $query->lampager()
                    ->limit($perPage); // Set Number of elements Per Page (default=10)
        
        // Sort by 'field'..
        if($sort == '>' || $sort == null)
            $paginator = $paginator->orderBy($field);
        else
            $paginator = $paginator->orderByDesc($field);
        
        // Get 'Previous Cursor' to be able to navigate backwards
        if($seekable)
            $paginator = $paginator->seekable(); 

        // If 'Next' Button is Clicked
        if(request()->direction == "next" || request()->direction == null){
            $paginator = $paginator->forward(); // Use forward method to change the direction of the navigation
        } 
        // If 'Previous' Button is Clicked
        else{
            $paginator = $paginator->backward(); // Use backward method to change the direction of the navigation
        }

        // Navigation rules
        if($newCursor != null){
            $paginator = $paginator
                ->paginate($newCursor);
        }
        else{
            $paginator = $paginator
                ->paginate();
        }

        /*
        ** Prepare a new State
        */
        
        // Extract cursors from paginator
        $cursors = (array)$paginator;
        unset($cursors['records']); // We don't need to encode records in the state

        // Get current route name...
        $route = Route::currentRouteName();

        // Next and Previous buttons have different cursors that's why we need state for every button

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

        return $result;
    }
}
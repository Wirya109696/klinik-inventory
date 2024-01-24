<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

 
function menus(){
    $array = array();
    $permissions = Auth::user()
    ->getAllPermissions()
    ->where('parent_id', '=', '0')->sortBy('order_line');
    foreach ($permissions as $permission) {
        $children = Auth::user()
        ->getAllPermissions()
        ->where('parent_id', '=', $permission->id)
        ->sortBy('order_line');
        $array_child = [];
        $prev_name   = '';
        foreach ($children as $child) {
            if ($prev_name != $child->menu_name) {
                if (Str::contains($child->name, 'index')) {
                    $array_child[] = [
                        'menu' => $child->menu_name,
                        'icon' => $child->icon,
                        'route_name' => $child->route_name,
                    ];

                    $prev_name = $child->menu_name;
                }
            }
        }
        if (Str::contains($permission->name, 'index')) {
            $array[] = [
                'child' => $permission->has_child,
                'menu' => $permission->menu_name,
                'icon' => $permission->icon,
                'route_name' => $permission->route_name,
                'children' => $array_child
            ];
        }
    }
    return $array;
}
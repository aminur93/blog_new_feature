<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $users = DB::table('users')
                ->select('users.id',
                    'users.name as name',
                    'users.email as email',
                    'roles.name as role_name'
                )
                ->leftJoin('model_has_roles','users.id','=','model_has_roles.model_id')
                ->leftJoin('roles','model_has_roles.role_id','=','roles.id')
                ->get();
        //dd($users);
        
        if (Auth::check() && isset($users['role_name']) == "Admin")
        {
            return $next($request);
        }else{
            return redirect()->route('login');
        }
        
        //return $next($request);
    }
}

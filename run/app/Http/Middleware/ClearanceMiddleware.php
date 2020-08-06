<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ClearanceMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (Auth::user()->hasPermissionTo('Administer roles & permissions'))
        {
            return $next($request);  // 管理員具備所有權限
        }

        if ($request->is('users/create')) // 使用者許可權
        {
            if (!Auth::user()->hasPermissionTo('Create User'))
            {
                abort('401');
            }
            else {
                return $next($request);
            }
        }

        if ($request->is('users/*/edit')) // 文章編輯許可權
        {
            if (!Auth::user()->hasPermissionTo('Edit User')) {
                abort('401');
            } else {
                return $next($request);
            }
        }

        if ($request->isMethod('Delete')) // 文章刪除許可權
        {
            if (!Auth::user()->hasPermissionTo('Delete User')) {
                abort('401');
            }
            else
            {
                return $next($request);
            }
        }

        return $next($request);
    }
}

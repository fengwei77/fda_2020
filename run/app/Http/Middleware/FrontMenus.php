<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FrontMenus
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \Menu::make('front_menu', function ($menu) {
            $main_menu = $menu->add('企業介紹', "#intro");
            $main_menu->attr(['class' => 'nav-item'])
                ->append('<a></a>')
                ->link->attr('class', 'nav-link  js-scroll-trigger');

            $main_menu = $menu->add('專業設備', "#equipment");
            $main_menu->attr(['class' => 'nav-item'])
                ->append('<a></a>')
                ->link->attr('class', 'nav-link  js-scroll-trigger');

            $main_menu = $menu->add('產品型式', "#product");
            $main_menu->attr(['class' => 'nav-item'])
                ->append('<a></a>')
                ->link->attr('class', 'nav-link  js-scroll-trigger');

            $main_menu = $menu->add('安心認證', "#Certification");
            $main_menu->attr(['class' => 'nav-item'])
                ->append('<a></a>')
                ->link->attr('class', 'nav-link  js-scroll-trigger');

            $main_menu = $menu->add('與我聯繫', "#Contact");
            $main_menu->attr(['class' => 'nav-item'])
                ->append('<a></a>')
                ->link->attr('class', 'nav-link  js-scroll-trigger');

            $main_menu = $menu->add('會員專區', Auth::guard('customer')->check() ? "member/data" : "login");
            $main_menu->attr(['class' => 'nav-item'])
                ->append('<a></a>')
                ->link->attr('class', 'nav-link  js-scroll-trigger');
        });
        return $next($request);
    }
}


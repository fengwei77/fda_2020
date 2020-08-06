<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CmsMenus
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

        \Menu::make('menu', function ($menu) {
            //home
            if (Auth::user()) {
                $home = $menu->add('控制台', ['route' => 'cms.dashboard.index'])->active('');
                $home->attr(['class' => 'nav-item has-treeview', 'data-toggle' => ''])
                    ->append('<p></p>')
                    ->prepend('<i class="nav-icon fas fa-tachometer-alt"></i> ')
                    ->link->attr('class', 'main-nav nav-link ');
                $home->active('/cms/dashboard');

                //管理員
                if (Auth::user()->hasRole('isAdmin')) {
                    $manager = $menu->add('權限管理', "");
                    $manager->attr(['class' => 'nav-item has-treeview', 'data-toggle' => ''])
                        ->append('<p><i class="right fas fa-angle-left"></i></p>')
                        ->prepend('<i class="nav-icon fas fa-users-cog"></i> ')
                        ->link->attr('class', 'main-nav nav-link');

                    $users = $manager->add('使用者', ['route' => 'cms.users.index', 'class' => 'sub_li nav-item']);
                    $users->attr(['class' => 'sub_li nav-item', 'data-toggle' => ''])
                        ->append('<p></p>')
                        ->prepend('<i class="far fa-circle nav-icon"></i> ')
                        ->link->attr('class', 'nav-link');
                    $manager->active('/cms/users/*');
//
                    $permissions = $manager->add('權限管理', ['route' => 'cms.permissions.index', 'class' => 'sub_li nav-item']);
                    $permissions->attr(['class' => 'sub_li nav-item', 'data-toggle' => ''])
                        ->append('<p></p>')
                        ->prepend('<i class="far fa-circle nav-icon"></i> ')
                        ->link->attr('class', 'nav-link');
                    $manager->active('/cms/permissions/*');

                    $roles = $manager->add('角色管理', ['route' => 'cms.roles.index', 'class' => 'sub_li nav-item']);
                    $roles->attr(['class' => 'sub_li nav-item', 'data-toggle' => ''])
                        ->append('<p></p>')
                        ->prepend('<i class="far fa-circle nav-icon"></i> ')
                        ->link->attr('class', 'nav-link');
                    $manager->active('/cms/roles/*');
                }

                //參加人員
                $player = $menu->add('玩家', ['route' => 'cms.player.index'])->active('');
                $player->attr(['class' => 'nav-item has-treeview', 'data-toggle' => ''])
                    ->append('<p></p>')
                    ->prepend('<i class="nav-icon fas fa-user"></i> ')
                    ->link->attr('class', 'main-nav nav-link ');
                $player->active('/cms/player/*');

                //發票
                $invoice = $menu->add('發票', ['route' => 'cms.player_invoice.index'])->active('');
                $invoice->attr(['class' => 'nav-item has-treeview', 'data-toggle' => ''])
                    ->append('<p></p>')
                    ->prepend('<i class="nav-icon fas  fa-barcode"></i> ')
                    ->link->attr('class', 'main-nav nav-link ');
                $invoice->active('/cms/player_invoice/*');

                //獎項
                $prize = $menu->add('獎項', ['route' => 'cms.prize.index'])->active('');
                $prize->attr(['class' => 'nav-item has-treeview', 'data-toggle' => ''])
                    ->append('<p></p>')
                    ->prepend('<i class="nav-icon fas fa-scroll"></i> ')
                    ->link->attr('class', 'main-nav nav-link ');
                $prize->active('/cms/prize/*');


                //電子報管理
//                if (Auth::user()->hasRole(['isAdmin', 'general'])) {
//                    $newsletter_mng = $menu->add('電子報管理', '')->active('');
//                    $newsletter_mng->attr(['class' => 'nav-item has-treeview', 'data-toggle' => ''])
//                        ->append('<p><i class="right fas fa-angle-left"></i></p>')
//                        ->prepend('<i class="nav-icon fas fa-newspaper"></i> ')
//                        ->link->attr('class', 'main-nav nav-link ');
//                    $newsletter_mng->active('/cms/subscribers/*');
//                    $newsletter_mng->active('/cms/newsletters/*');
//                    $subscribers = $newsletter_mng->add('訂閱者', ['route' => 'cms.subscribers.index', 'class' => 'sub_li nav-item'])->nickname('cms/subscribers');
//                    $subscribers->attr(['class' => 'sub_li nav-item', 'data-toggle' => ''])
//                        ->append('<p></p>')
//                        ->prepend('<i class="far fa-circle nav-icon"></i> ')
//                        ->link->attr('class', 'nav-link');
//                    if (str_is($subscribers->nickname . '*', \Request::path())) {
//                        $subscribers->link->active('');
//                    }
//                    $subscribers->active('/cms/subscribers/*');
//                    $newsletters = $newsletter_mng->add('電子報', ['route' => 'cms.newsletters.index', 'class' => 'sub_li nav-item'])->nickname('cms/newsletters');
//                    $newsletters->attr(['class' => 'sub_li nav-item', 'data-toggle' => ''])
//                        ->append('<p></p>')
//                        ->prepend('<i class="far fa-circle nav-icon"></i> ')
//                        ->link->attr('class', 'nav-link');
//                    if (str_is($newsletters->nickname . '*', \Request::path())) {
//                        $newsletters->link->active('');
//                    }
//                    $newsletters->active('/cms/newsletters/*');
//                }

            }
        });
        return $next($request);
    }
}

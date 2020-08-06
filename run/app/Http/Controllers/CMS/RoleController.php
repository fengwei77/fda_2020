<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Session;

class RoleController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'isAdmin']); // isAdmin 中介軟體讓具備指定許可權的用戶才能訪問該資源
    }

    /**
     * 顯示角色清單
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $roles = Role::where('name', '<>', 'isAdmin')->get();// 獲取所有角色
        $page_title = '角色管理';
        return view('cms.roles.index', compact('page_title', 'roles'));
    }

    /**
     * 顯示創建角色表單
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $permissions = Permission::all();// 獲取所有權限

        $page_title = '新增角色資料';
        return view('cms.roles.create', compact('page_title', 'permissions'));
    }

    /**
     * 保存新創建的角色
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //驗證 name 和 permissions 欄位
        $this->validate($request, [
                'name'=>'required|unique:roles|max:10',
                'permissions' =>'required',
            ]
        );

        $name = $request['name'];
        $role = new Role();
        $role->name = $name;

        $permissions = $request['permissions'];

        $role->save();
        // 遍歷選擇的許可權
        foreach ($permissions as $permission) {
            $p = Permission::where('id', '=', $permission)->firstOrFail();
            // 獲取新創建的角色並分配許可權
            $role = Role::where('name', '=', $name)->first();
            $role->givePermissionTo($p);
        }

        return redirect()->route('cms.roles.index')
            ->with('flash_message',
                'Role'. $role->name.' added!');
    }

    /**
     * 顯示指定角色
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return redirect('roles');
    }

    /**
     * 顯示編輯角色表單
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $page_title = '編輯角色資料';

        return view('cms.roles.edit', compact('page_title', 'role', 'permissions'));
    }

    /**
     * 更新角色
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $role = Role::findOrFail($id); // 通過給定id獲取角色
        // 驗證 name 和 permission 欄位
        $this->validate($request, [
            'name'=>'required|max:10|unique:roles,name,'.$id,
            'permissions' =>'required',
        ]);

        $input = $request->except(['permissions']);
        $permissions = $request['permissions'];
        $role->fill($input)->save();

        $p_all = Permission::all();//獲取所有權限

        foreach ($p_all as $p) {
            $role->revokePermissionTo($p); // 移除與角色關聯的所有權限
        }

        foreach ($permissions as $permission) {
            $p = Permission::where('id', '=', $permission)->firstOrFail(); //從資料庫中獲取相應許可權
            $role->givePermissionTo($p);  // 分配許可權到角色
        }

        return redirect()->route('cms.roles.index')
            ->with('flash_message',
                'Role'. $role->name.' updated!');
    }

    /**
     * 刪除指定許可權
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        if ($role) {
            return json_encode(['result' => '01', 'message' => 'success']);
        } else {
            return json_encode(['result' => '00', 'message' => 'fail']);
        }
//        return redirect()->route('cms.roles.index')
//            ->with('flash_message',
//                'Role deleted!');

    }
}

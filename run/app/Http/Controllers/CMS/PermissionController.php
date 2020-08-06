<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Session;

class PermissionController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'isAdmin']); // isAdmin 中介軟體讓具備指定許可權的用戶才能訪問該資源
    }

    /**
     * 顯示許可權清單
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $permissions = Permission::where('name', '<>', 'Administer roles & permissions')->get();// 獲取所有權限
        $page_title = '權限管理';

        return view('cms.permissions.index', compact('page_title', 'permissions'));

    }

    /**
     * 顯示創建許可權表單
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $roles = Role::get(); // 獲取所有角色

        $page_title = '新增權限資料';
        return view('cms.permissions.create', compact('page_title', 'roles'));
    }

    /**
     * 保存新創建的許可權
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name'=>'required|max:40',
        ]);

        $name = $request['name'];
        $permission = new Permission();
        $permission->name = $name;

        $roles = $request['roles'];

        $permission->save();

        if (!empty($request['roles'])) { // 如果選擇了角色
            foreach ($roles as $role) {
                $r = Role::where('id', '=', $role)->firstOrFail(); // 將輸入角色和資料庫記錄進行匹配

                $permission = Permission::where('name', '=', $name)->first(); // 將輸入許可權與資料庫記錄進行匹配
                $r->givePermissionTo($permission);
            }
        }

        return redirect()->route('cms.permissions.index')
            ->with('flash_message',
                'Permission'. $permission->name.' added!');

    }

    /**
     * 顯示給定許可權
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return redirect('permissions');
    }

    /**
     * 顯示編輯許可權表單
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $permission = Permission::findOrFail($id);

        $page_title = '編輯權限資料';
        return view('cms.permissions.edit', compact('page_title', 'permission'));
    }

    /**
     * 更新指定許可權
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $permission = Permission::findOrFail($id);
        $this->validate($request, [
            'name'=>'required|max:40',
        ]);
        $input = $request->all();
        $permission->fill($input)->save();

        return redirect()->route('cms.permissions.index')
            ->with('flash_message',
                'Permission'. $permission->name.' updated!');

    }

    /**
     * 刪除給定許可權
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $permission = Permission::findOrFail($id);

        // 讓特定許可權無法刪除
        if ($permission->name == "Administer roles & permissions") {
            return redirect()->route('cms.permissions.index')
                ->with('flash_message',
                    'Cannot delete this Permission!');
        }
        $permission->delete();
        if ($permission) {
            return json_encode(['result' => '01', 'message' => 'success']);
        } else {
            return json_encode(['result' => '00', 'message' => 'fail']);
        }
//        return redirect()->route('cms.permissions.index')
//            ->with('flash_message',
//                'Permission deleted!');

    }
}

<?php

namespace App\Http\Controllers\CMS;
use App\Models\User;
use App\Forms\UserForm;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Kris\LaravelFormBuilder\Field;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Webpatser\Uuid\Uuid;
use Auth;
// 引入 laravel-permission 模型
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    use FormBuilderTrait;

    public function __construct()
    {
        $this->middleware(['auth', 'isAdmin']); // isAdmin 中间件让具备指定权限的用户才能访问该资源
    }

    public function index()
    {
        $page_title = '使用者管理';
//       $result = DB::table('construction_companies')->paginate(2);
        $users = User::paginate(15);
        return view('cms.users.index', compact('page_title', 'users' ));
    }


    //新增使用者
    public function create(FormBuilder $formBuilder)
    {
        $page_title = '新增使用者資料';

        $form = $formBuilder->create('App\Forms\UserForm', [
            'method' => 'POST',
            'url' => route('cms.users.store')
        ]);
        //權限
        $roles = Role::get();

        return view('cms.users.create', compact('page_title', 'form', 'roles'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id); //通过 id = $id 查找文章

        return json_encode($user);
    }

    //編輯資料
    public function edit(FormBuilder $formBuilder, $id)
    {
        if (!isset($id)) {
            return Redirect::to('cms/dashboard')
                ->withErrors(['fail' => '無ID!']);
        }

        $page_title = '編輯使用者資料';
        $user = User::findOrFail($id); // 通过id获取给定角色
        $roles = Role::get(); // 取得所有角色資料

        $form = $formBuilder->create('App\Forms\UserForm', [
            'method' => 'PUT',
            'url' => route('cms.users.update', $id)
        ]);

        $form
            ->modify('id', Field::HIDDEN, ['value' => $id])
            ->modify('name', Field::TEXT, ['value' => $user['name']])
            ->modify('account', Field::TEXT, ['disabled' => 'disabled', 'value' => $user['account']])
            ->modify('password', Field::TEXT, ['rules' => [], 'attr' => ['placeholder'=> '如不修改密碼,此欄位請留空白。']])
            ->modify('item_status', Field::CHECKBOX, ['checked' => ($user['item_status'] == 1 ? true : false)]);


        return view('cms.users.edit', compact('page_title', 'form', 'user', 'roles'));
    }

    //新增至資料庫

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $form = $this->form(UserForm::class);


        if ($form->isValid()) {

            $query_count = User::where('account', $request->input('account', ''))->count();
            if ($query_count != 0) {
                return redirect()->back()->with('failed', '此帳號已被使用')->withInput();
            }
            $email = Uuid::generate()->string;
            $email ='';
            $user = User::create([
                'name' => $this->check_null($request->input('name', '')),
                'account' => $this->check_null($request->input('account', '')),
                'password' => $this->check_null($request->input('password', '')),
                'email' => $email,
                'email_verified_at' => now(),
                'remember_token' => '',
                'item_status' => $this->check_null($request->input('item_status', '0')),
            ]);

            $roles = $request['roles']; // 取得全部角色資料
            print_r($roles);
            // 檢查角色ROLE是否被選到
            if (isset($roles)) {
                foreach ($roles as $role) {
                    $role_r = Role::where('id', '=', $role)->firstOrFail();
                    $user->assignRole($role_r); //Assigning role to user
                }
            }

        } else {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        //Post::create($form->getFieldValues());
        return redirect()->route('cms.users.index');
    }


    public function update(Request $request, $id)
    {
            $user = User::findOrFail($id);
            if (strlen($request->input('password', '')) > 2) {
                $input = $request->only(['name', 'account', 'password', 'item_status']); // 获取 name, email 和 password 字段

            } else {
                $input = $request->only(['name', 'account', 'item_status']); // 获取 name, email 和 password 字段

            }
            $roles = $request['roles']; // 取得全部角色資料
            $user->fill($input)->save();

            if (isset($roles)) {
                $user->roles()->sync($roles);  // 如果有角色选中与用户关联则更新用户角色
            } else {
                $user->roles()->detach(); // 如果没有选择任何与用户关联的角色则将之前关联角色解除
            }
             return redirect()->route('cms.users.edit', ['user' => $id])->with('success', '更新完成!');
    }

    public function destroy($id)
    {
//        $result = User::where('id', $id)->forceDelete();
        $users = User::findOrFail($id);
        $users->delete();
        if ($users) {
            return json_encode(['result' => '01', 'message' => 'success']);
        } else {
            return json_encode(['result' => '00', 'message' => 'fail']);
        }
    }

    function check_null($val = '')
    {
        return is_null($val) ? '' : $val;
    }
}


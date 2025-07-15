<?php

namespace App\Http\Controllers;

use App\Models\ManagerModel;
use App\Models\ManagerLogModel;
use App\Repositories\ManagerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{   
    /**
     * @var ManagerRepository
     */
    protected $managerRepository;

    public function __construct(ManagerRepository $managerRepository)
    {
        $this->managerRepository = $managerRepository;
    }
    //管理員頁面 列表
    public function managerList(){
        $manager = $this->managerRepository->all();
        return view('manager', ['managers' => $manager]);
    }
    
    //管理員頁面 新增
    public function addManager(Request $request){
        $data = $request->validate([
                    'manager_name'=>'required',
                    'account'=>'required',
                    'password'=>'required|min:6',
                ]);
        // 檢查帳號是否已存在
        if($this->managerRepository->getByFirst(['account' => $request->account])){
            return redirect()->back()->withErrors(['account' => '帳號已存在']);
        } 

        $this->managerRepository->createManager($data);
        // $this->addLog($user['userAccount'],'新增管理員',$newID);
        return redirect()->route('manager.list')->with('success', '資料管理員成功！');
    }

    //管理員頁面 修改密碼
    public function updatePasswordView($id){
        $manager = $this->managerRepository->getById($id);
        if(!$manager){
            return redirect()->back()->withErrors(['error' => '找不到該管理員']);
        }
        return view('updatePassword', compact('manager'));
    }
    
    //管理員頁面 更新密碼
    public function updatePassword(Request $request, $id){
        $request->validate([
            'account'=>'required',
            'old_password'=>'nullable',
            'new_password'=>'required|min:6',
        ]);
        $manager = $this->managerRepository->getByFirst(['account' => $request->account]);
        if(!$manager){
            return redirect()->back()->withErrors(['account' => '帳號不正確']);
        }
        else if(!Hash::check($request->old_password, $manager->password)){
            return redirect()->back()->withErrors(['old_password' => '舊密碼不正確']);
        }
        $this->managerRepository->updateManager($id, $request->only('new_password'));
        return redirect()->route('manager.list')->with('success', '更改密碼成功！');
    }

    //管理員頁面 刪除
    public function delete($id){
        $managerID = $id;
        // 不能自己刪除自己
        // if($managerID != Auth::user()->manager_id){
        //     $delete = ManagerModel::where('id',$managerID)->delete();
        //     if($delete){
        //         $this->addLog(Auth::user()->account,'刪除管理員',$managerID);
        //         return response()->json(['success' => '管理員已刪除']);
        //     }
        //     return response()->json(['error' => '找不到該內容'], 404);
        // }
        $this->managerRepository->deleteManager($managerID);
        return redirect()->route('manager.list')->with('success', '管理員已刪除');
    }

    //登入
    public function loginForm(){
        return view('loginForm');
    }
    
    public function login(Request $request){
        $request->validate([
            'signin_account'=>'required',
            'signin_password'=>'required'
        ]);
        // $user = ManagerModel::where('account',$request->signin_account)->first();

        if(Auth::attempt(['account'=>$request->signin_account,'password'=>$request->signin_password])){
            $request->session()->regenerate(); //要 regenerate才會存到auth
            session()->put('user',['userID'=>$request->id,'userAccount'=>$request->account,'userName'=>$request->manager_name]);
         
            // $this->addLog(Auth::user()->account,'帳號登入',-1);
            return redirect()->route('message.contentView')->with('success','登入成功');
        }
        return back()->withErrors(['login' => '帳號或密碼錯誤']);
    }

    //登出
    public function logout(){
        // $this->addLog(Auth::user()->account,'帳號登出',-1);
        Auth::logout();
        return redirect('/')->with('success','已登出');
    }

    //管理員操作
    public function addLog(string $account,string $action,int $dataNum){
        ManagerLogModel::create([
            'account'=>$account,
            'action'=>$action,
            'dataNum'=>$dataNum
        ]);
    }
}
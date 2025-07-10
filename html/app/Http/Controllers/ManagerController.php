<?php

namespace App\Http\Controllers;

use App\Models\ManagerModel;
use App\Models\ManagerLogModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{   public function managerView(){
        return view('manager');
    }
    //管理員頁面 列表
    public function managerData(){
        $manager = ManagerModel::all()->toArray();
        return response()->json($manager);
    }
    //管理員頁面 新增
    public function addManager(Request $request){
        $request->validate([
            'manager_name'=>'required',
            'account'=>'required',
            'password'=>'required|min:6',
        ]);
        $hashPassword = Hash::make($request->password);
        ManagerModel::create([
            'manager_name'=>$request->manager_name,
            'account'=>$request->account,
            'password'=>$hashPassword
        ]);
        $newID = ManagerModel::orderByDesc('manager_id')->value('manager_id');
        $user = session('user');
        $this->addLog($user['userAccount'],'新增管理員',$newID);
        return redirect()->route('manager.view')->with('success', '資料新增成功！');
    }
    //管理員頁面 刪除
    public function delete(Request $request){
        $managerID = $request->input('manager_id');
        if($managerID != Auth::user()->manager_id){
            $delete = ManagerModel::where('manager_id',$managerID)->delete();
            if($delete){
                $this->addLog(Auth::user()->account,'刪除管理員',$managerID);
                return response()->json(['success' => '管理員已刪除']);
            }
            return response()->json(['error' => '找不到該內容'], 404);
        }
        return response()->json(['error' => ' 無法刪除',404]);
    }

    public function loginForm(){
        return view('loginForm');
    }
    //登入
    public function login(Request $request){
        $request->validate([
            'signin_account'=>'required',
            'signin_password'=>'required'
        ]);
        $user = ManagerModel::where('account',$request->signin_account)->first();
        if(Auth::attempt(['account'=>$request->signin_account,'password'=>$request->signin_password])){
            $request->session()->regenerate();//要 regenerate才會存到auth
            session()->put('user',['userID'=>$user->manager_id,'userAccount'=>$user->account,'userName'=>$user->manager_name]);
            $this->addLog(Auth::user()->account,'帳號登入',-1);
            return redirect('/message/content')->with('success','登入成功');
        }
        return back()->withErrors(['login' => '帳號或密碼錯誤']);
    }
    //登出
    public function logout(){
        $this->addLog(Auth::user()->account,'帳號登出',-1);
        Auth::logout();
        return redirect('/message/loginForm')->with('success','已登出');
    }
    //檢查登入狀態
    public function checkSession(){
        if(Auth::check()){
             return response()->json([
                'isLogin'=>session()->has('user'),
                'auth'=>Auth::user()
            ]);
        }
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
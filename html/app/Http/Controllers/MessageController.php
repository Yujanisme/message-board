<?php

namespace App\Http\Controllers;

use App\Models\ContentModel;
use App\Models\ManagerLogModel;
use COM;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;

class MessageController extends Controller
{
    //前台畫面
    public function index()
    {
        return view('message');
    }
    //顯示所有留言
    public function lists()
    {
        $contents = ContentModel::orderBy('content_num','DESC')->paginate(10);     //把all content 轉成 array

        return response()->json($contents);
    }
    //新增留言
    public function create(Request $request){
        $request -> validate([
            'nickname'=>'required',
            'message_content'=>'required',
            'created_at'=>Carbon::now()->toDateString(),
        ]);

        ContentModel::create([
            'user_nickname'=>$request->nickname,
            'content'=>$request->message_content
        ]);
        return redirect()->route('message.view')->with('success', '資料新增成功！');
    }

    //管理留言畫面
    public function contentView()
    {
        return view('content');
    }

    //條件篩選留言
    public function select(Request $request){
        $request->validate([
            'start'=>'nullable|date',
            'end'=>'nullable|date|after_or_equal:start',
            'keyword'=>'nullable'
        ]);
        
        $startTime = $request->start;
        $endTime = $request->end;
        $key = $request->keyword;
        if($startTime != null &&  $endTime != null && $key != null){
            $result = ContentModel::whereBetween('created_at',[$startTime,$endTime])
                                    ->where('content','like',"%{$key}%")->orderBy('content_num','DESC')->paginate(10);
        }
        elseif($startTime != null &&  $endTime != null){
            $result = ContentModel::whereBetween('created_at',[$startTime,$endTime])->orderBy('content_num','DESC')->paginate(10);
        }
        elseif($key != null){
            $result = ContentModel::where('content','like',"%{$key}%")->orderBy('content_num','DESC')->paginate(10);
        }else{
            return response()->json(['message' => '找不到該留言'], 404);
        }
        if($result->isEmpty()){
            return response()->json(['message' => '查無資料'],200);
        }
        return response()->json($result);
    }
    
    //回覆留言
    public function reply(Request $request){
        $user = session('user');
        $request->validate([
            'content_num'=>'required',
            'reply'=>'required',
        ]);
        $id = $request->content_num;
        $reply = $request->reply;
        $update = ContentModel::where('content_num',$id)->update(['reply'=>$reply]);
        $this->addLog(Auth::user()->account,'回覆留言',$id);
        if ($update) {
            return response()->json($update);
        } else {
            return response()->json(['message' => '找不到該留言'], 404);
        }
    }
    //編輯回覆
    public function edit_reply(Request $request){
        $user = session('user');
        $request->validate([
            'ed_content_num'=>'required',
            'ed_reply'=>'required'
        ]);

        $id = $request->ed_content_num;
        $edit = $request->ed_reply;
        $this->addLog(Auth::user()->account,'編輯回覆',$id);
        $update = ContentModel::where('content_num',$id)->update(['reply'=>$edit]);
        if ($update) {
            return response()->json($update);
        } else {
            return response()->json(['message' => '找不到該留言'], 404);
        }
    }
    //刪除留言
    public function delete(Request $request){
        $user = session('user');
        $content_num = $request->input('content_num');
           
        $delete = ContentModel::where('content_num',$content_num)->delete();
        $this->addLog(Auth::user()->account,'刪除留言',$content_num);
        if($delete){
            return response()->json(['success' => '內容已刪除']);
        }
        return response()->json(['error' => '找不到該內容'], 404);
    }
    public function addLog(string $account,string $action,int $dataNum){
        ManagerLogModel::create([
            'account'=>$account,
            'action'=>$action,
            'dataNum'=>$dataNum
        ]);
    }
}

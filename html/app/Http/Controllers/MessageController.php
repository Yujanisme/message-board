<?php

namespace App\Http\Controllers;

use App\Models\MessageModel;
use App\Models\ManagerLogModel;
use App\Repositories\MessageRepository;
use COM;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;

class MessageController extends Controller
{
    /**
     * @var MessageRepository
     */
    protected $messageRepository;
    
    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    //前台畫面
    public function index()
    {
        return view('message');
    }

    //顯示所有留言
    public function messageList()
    {
        $contents = MessageModel::orderBy('id','DESC')->paginate(10);     //把all content 轉成 array

        return response()->json($contents);
    }

    //新增留言
    public function create(Request $request){
        $request -> validate([
            'nickname'=>'required',
            'message_content'=>'required'
        ]);

        $this->messageRepository->create([
            'user_nickname' => $request->nickname,
            'content' => $request->message_content,
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);

        return redirect()->route('message.view')->with('success', '資料新增成功！');
    }

    //管理留言畫面
    public function contentView()
    {
        $contents = $this->messageRepository->all(); // 取得所有留言並按時間排序
        return view('content', ['contents' => $contents]);
    }

    //條件篩選留言
    public function query(Request $request){
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'keyword' => 'nullable'
        ]);

        $request = $request->except('_token');
        
        $result = $this->messageRepository->getBetweenWhere('created_at', $request['start'], $request['end'],[['content', 'like', '%' . $request['keyword'] . '%']]);

        return response()->json($result);
    }
    
    //回覆留言
    public function reply(Request $request){
        // $user = session('user');
        $request->validate([
            'content_num'=>'required',
            'reply'=>'required',
        ]);

        $this->messageRepository->updateById($request->content_num, ['reply' => $request->reply, 'updated_at' => Carbon::now()->toDateTimeString()]);
        // $this->addLog(Auth::user()->account,'回覆留言',$id);
        // if ($update) {
        //     return response()->json($update);
        // } else {
        //     return response()->json(['message' => '找不到該留言'], 404);
        // }
        return redirect()->route('message.contentView')->with('success', '回覆留言成功！');
    }

    //刪除留言
    public function delete($id){
        // $user = session('user');

        $delete = $this->messageRepository->deleteById($id);
        // $this->addLog(Auth::user()->account,'刪除留言',$content_num);
        if($delete){
            return redirect()->route('message.contentView')->with('success', '內容已刪除');
        }
        return redirect()->route('message.contentView')->with('error', '刪除過程出錯，請稍後再試');
    }

    public function addLog(string $account,string $action,int $dataNum){
        ManagerLogModel::create([
            'account'=>$account,
            'action'=>$action,
            'dataNum'=>$dataNum
        ]);
    }
}

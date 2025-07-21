<?php

namespace App\Http\Controllers;

use App\Models\MessageModel;
use App\Models\ManagerLogModel;
use App\Repositories\MessageRepository;
use App\Repositories\ManagerLogRepository;
use COM;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Yajra\DataTables\Facades\DataTables;

class MessageController extends Controller
{
    /**
     * @var MessageRepository
     */
    protected $messageRepository;
    
    /**
     * ManagerLogRepository
     */
    protected $managerLogRepository;
    public function __construct(
        MessageRepository $messageRepository,
        ManagerLogRepository $managerLogRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->managerLogRepository = $managerLogRepository;
    }

    //前台畫面
    public function index()
    {
        $message = $this->messageRepository->all(); // 取得所有留言並按時間排序
        return view('message', ['messages' => $message]);
    }

    //顯示所有留言
    public function messageList(Request $request)
    {
        $query = $this->query($request);
       
        $dataTable = DataTables::of($query)
                        ->addColumn('action', function ($row) {
                            $id = $row->id;
                            
                            if(is_null($row->reply)){
                                $replyButton = '<button class="btn btn-primary reply" data-bs-toggle="modal" data-bs-target="#replyModal" data-id="'.$row->id.'" data-user-nickname="'.$row->user_nickname.'" data-content="'.$row->content.'" data-reply="'.$row->reply.'">回覆</button>';
                            } else {
                                $replyButton = '<button class="btn btn-primary reply" data-bs-toggle="modal" data-bs-target="#replyModal" data-id="'.$row->id.'" data-user-nickname="'.$row->user_nickname.'" data-content="'.$row->content.'" data-reply="'.$row->reply.'">編輯回覆</button>';
                            }
                            $buttons = <<<HTML
                                    {$replyButton}
                                    <button class="btn btn-danger delete-btn" data-id="{$id}">刪除</button>
                            HTML;
                            return $buttons;
                        })
                        ->rawColumns(['action']);
         return $dataTable->make(true);
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
        $end = Carbon::parse($request['end'])->endOfDay();
        if (isset($request->keyword)) {
            $result = $this->messageRepository->getBetweenWhere('created_at', $request['start'], $end,[['content', 'like', '%' . $request['keyword'] . '%']]);
        } else {
            $result = $this->messageRepository->getBetween('created_at', $request['start'], $end);
        }
        return $result;
    }
    
    //回覆留言
    public function reply(Request $request){
        $request->validate([
            'content_num'=>'required',
            'reply'=>'required',
        ]);

        $this->messageRepository->updateById($request->content_num, ['reply' => $request->reply, 'updated_at' => Carbon::now()->toDateTimeString()]);

        $this->managerLogRepository->addLog(Auth::user()->account, '回覆留言', '留言ID：'.$request->content_num.' 回覆內容：'.$request->reply);
        return redirect()->route('message.contentView')->with('success', '回覆留言成功！');
    }

    //刪除留言
    public function delete($id){
        $message = $this->messageRepository->getById($id);
        $delete = $this->messageRepository->deleteById($id);
        if($delete){
            $this->managerLogRepository->addLog(Auth::user()->account, '刪除留言', '刪除留言內容：'.$message['content']);
            return redirect()->route('message.contentView')->with('success', '內容已刪除');
        }
        return redirect()->route('message.contentView')->with('error', '刪除過程出錯，請稍後再試');
    }
}
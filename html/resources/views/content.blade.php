@extends('layout.main')

<html>    
    <head>
        <title>留言板管理</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/purecss@3.0.0/build/pure-min.css" integrity="sha384-X38yfunGUhNzHpBaEBsWLO+A0HDYOQi8ufWDkZ0k9e0eXz/tH3II7uKZ9msv++Ls" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
    </head>
    <style>
        p{
            font-size: 26px;
            display: inline; 
        }
        span{
            font-size: 22px;
        }
        #messages {
            align-items: center;
        }
        #messages thead th {
            padding: 10px;
            background-color: #f6f4f4;
            border: 1px solid #ddd;
        }
        #messages tbody td {
            padding: 10px;
            background-color: #f4f2f2;
            border: 1px solid #ddd;
        }
    </style>
    <script
        src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous">
    </script>

    <body>
        <div id="manager" name="manager">
            <h3 style="display: inline-block;">管理者:</h3>
            <p id="managerName" >{{ Auth::user()->manager_name }}</p>
            <form id="logout" method="POST" action="{{ route('logout') }}" style="display: inline-block; margin-left: 20px;">
                @csrf
                <button class="btn btn-outline-primary" type="submit">登出</button>
            </form>
            <br>
            <button class="btn btn-secondary" onclick="location.href = '{{ route('manager.list') }}';">管理員列表</button>
        </div>
        <div style="width: 1200px; margin:auto">
            <h1 style="text-align: center;">留言列表</h1>
        </div>

        <div class="content container-md" style="text-align: center;">
            <div id="all" style="display: block; text-align: center;" >
                <div>
                    <form id="search" method="post" action="{{ route('message.query') }}" style="display: inline-block; margin-bottom: 20px;">
                        @csrf
                        <label>開始時間</label>
                        <input type="date" id="start" name="start" value="{{ request('start', date('Y-m-01')) }}">
                        <label>結束時間</label>
                        <input type="date" id="end" name="end" value="{{ request('end', date('Y-m-d')) }}">
                        <label>關鍵字</label>
                        <input type="text" id="keyword" name="keyword">
                        <button class="btn btn-outline-dark" type="submit">搜尋</button>
                    </form>
                    <table class="table table-striped" id="messages" ,border="1" style="margin: auto;">
                        <button id="delete_multiple"  class="btn btn-outline-dark" onclick="delete_multiple()">刪除勾選</button> 
                        <br>
                    </table>
                    <div id="pagination"></div>
                </div>
            </div>

            <!-- list -->
            <div id="list" style="text-align: center;">
                <table class="table table-striped" id="messages" ,border="1" style="margin: auto;">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select_all" onchange="select_all()">全選</th>
                            <th>暱稱</th>
                            <th>留言內容</th>
                            <th>留言時間</th>
                            <th>管理員回覆</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contents as $content)
                            <tr>
                                <td><input type="checkbox" class="checkbox" id="checkNum-{{ $content->id }}"></td>
                                <td>{{ $content->user_nickname }}</td>
                                <td>{{ $content->content }}</td>
                                <td>{{ $content->created_at }}</td>
                                <td>{{ $content->reply ?? '無回覆' }}</td>
                                <td>
                                    @if($content->reply)
                                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#replyModal" data-info='{"id": "{{ $content->id }}", "userNickname": "{{ $content->user_nickname }}", "content": "{{ $content->content }}", "reply": "{{ $content->reply }}"}'>編輯回覆</button>
                                    @else
                                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#replyModal" data-info='{"id": "{{ $content->id }}", "userNickname": "{{ $content->user_nickname }}", "content": "{{ $content->content }}", "reply": ""}'>回覆</button>
                                    @endif
                                    <form id="deleteMessage" method="POST" action="{{ route('message.delete', $content->id) }}" style="display:contents">
                                        @csrf
                                        <button class="btn btn-danger delete-btn" data-id="{{ $content->id }}">刪除</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- reply -->
            <div class="modal fade" id="replyModal" data-bs-backdrop="static" data-bs-keyboard="false"tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="replyModalLabel">回覆留言</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="replyForm" method="POST" action="{{ route('message.reply') }}" style="text-align: center;">
                                @csrf
                                <input type="hidden" name="content_num" id="content_num">
                                <div class="mb-3">
                                    <label for="user_nickname" class="col-form-label">暱稱:</label>
                                    <p id="user_nickname"></p><br>
                                </div>
                                <div class="mb-3">
                                    <label for="content" class="col-form-label">留言內容:</label>
                                    <p id="content"></p>
                                </div>
                                <div class="mb-3">
                                    <label for="reply" class="col-form-label">回覆留言:</label>
                                    <textarea class="form-control" name="reply" id="reply"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="close" type="button" onclick="closeForm()">關閉</button>
                            <button type="suubmit" class="btn btn-primary" name="reply_submit" id="reply" form="replyForm">回覆</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>


<script>
    // 回覆、編輯回覆資料
    $('.modal').on('show.bs.modal', function (event) {
        let dataInfo = $(event.relatedTarget).data('info');
        console.log(dataInfo);
        $('#content_num').val(dataInfo.id);
        $('#user_nickname').text(dataInfo.userNickname);
        $('#content').text(dataInfo.content);
        $('#reply').val(dataInfo.reply || '');
    })
    
    // 刪除留言
    $('.delete-btn').on('click',function(){
        let checkid = $(this).data('id');
        console.log(checkid);
    });

    // 關掉modal時清除表單
    $('.modal').on('hidden.bs.modal', function () {
        location.reload();
    });
</script>
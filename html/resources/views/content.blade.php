@extends('layout.main')

@section('title', '管理員列表')

@section('content')
    <div id="manager" name="manager">
        <h3 style="display: inline-block;">管理者:{{ Auth::user()->manager_name }}</h3>
        <form id="logout" method="POST" action="{{ route('logout') }}" style="display: inline-block; margin-left: 20px;">
            @csrf
            <button class="btn btn-outline-primary" type="submit">登出</button>
        </form>
        <br>
        <button class="btn btn-secondary" onclick="location.href = '{{ route('manager.view') }}';">管理員列表</button>
    </div>
    <div style="width: 1200px; margin:auto">
        <h1 style="text-align: center;">留言列表</h1>
    </div>

    <div class="content" style="text-align: center;">
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
                <div id="pagination"></div>
            </div>
        </div>

        <!-- list -->
        <div class="container-md">
            <table class="table table-striped" id="messages" ,border="1" style="margin: auto;">
                    <thead>
                        <tr>
                            <th>暱稱</th>
                            <th>留言內容</th>
                            <th>留言時間</th>
                            <th>管理員回覆</th>
                            <th>操作</th>
                        </tr>
                    </thead> 
            </table>
            <tbody></tbody>
            <div id="pagination"></div>
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
@endsection

@section('scripts')
<script>
 
    $(document).ready(function () {
        // 留言列表
        const columns = [
            { data: 'user_nickname' },
            { data: 'content' },
            { data: 'created_at' },
            { data: 'reply' },
            { data: 'action', orderable: false, searchable: false }
        ];

        const table = initDataTable('#messages', "{{ route('message.list') }}", columns);

        // 回覆、編輯回覆資料
        $('#replyModal').on('show.bs.modal', function (event) {
            let dataInfo = $(event.relatedTarget);
            $('#content_num').val(dataInfo.data('id'));
            $('#user_nickname').text(dataInfo.data('userNickname'));
            $('#content').text(dataInfo.data('content'));
            $('#reply').val(dataInfo.data('reply'));
        })
    });

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
@endsection
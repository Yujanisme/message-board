@extends('layout.main')

@section('title', '留言板')

@section('content')
    <div style="text-align: center;">
        <div style="text-align: center; margin: 22px;"> 
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewMessage" style="font-size:24px">我要留言</button>
        </div>
        <div id="all" style="display: block; text-align: center;" >
            <div>
                <label>開始時間</label>
                <input type="date" id="start" name="start" value="{{ request('start', date('Y-m-01')) }}">
                <label>結束時間</label>
                <input type="date" id="end" name="end" value="{{ request('end', date('Y-m-d')) }}">
                <button class="btn btn-outline-dark" type="submit" id="search">搜尋</button>
            </div>
        </div>
        <div class="container-md">
            <h1 style="text-align: center;">留言板</h1>
            <table class="table table-striped" id="messages" ,border="1" style="margin: auto;">
                    <thead>
                        <tr>
                            <th>暱稱</th>
                            <th>留言內容</th>
                            <th>留言時間</th>
                            <th>管理員回覆</th>
                        </tr>
                    </thead> 
            </table>
            <div id="pagination"></div>
        </div>
        <!-- 新增留言 -->
        <div class="modal fade" id="addNewMessage" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addNewMessage">新增留言</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="pure-form" id="addForm" method="POST" action="{{ route('message.create') }}" style="text-align: center;">
                            @csrf
                            <label class="bigfont">暱稱</label>
                            <input type="text" name="nickname" id="nickname" placeholder="請輸入暱稱" style="margin: 10px; font-size: 20px;">
                            <br>
                            <label class="bigfont">留言內容</label>
                            <input type="text" name="message_content" id="message_content" placeholder="請輸入留言內容.." style="margin: 10px; font-size: 20px;">
                        </form> 
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="close" type="button" onclick="closeForm()">關閉</button>
                        <button type="submit" class="btn btn-primary" name="addMessage" id="addMessage" form="addForm">送出</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        const columns = [
            { data: 'user_nickname' },
            { data: 'content' },
            { data: 'created_at' },
            { data: 'reply' }
        ];

        const extraParams = {
            start: () => $('#start').val(),
            end: () => $('#end').val()
        }
        const table = initDataTable('#messages', "{{ route('message.list') }}", columns, extraParams);

        $('#search').on('click', function(){
            table.ajax.reload();
        })
    });
</script>
@endsection

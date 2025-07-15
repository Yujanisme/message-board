<!DOCTYPE html>
<html>
    
    <head>
        <title>留言板管理</title>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/purecss@3.0.0/build/pure-min.css" integrity="sha384-X38yfunGUhNzHpBaEBsWLO+A0HDYOQi8ufWDkZ0k9e0eXz/tH3II7uKZ9msv++Ls" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <script>
        window.onload = function(){
            checklogin();
            list_content();
        };
        function checklogin(){
            $.getJSON('checkSession',function(data){
                if(data.isLogin == true){
                    $('#managerName').text(data.auth.manager_name);
                }
                else{
                    window.location.assign('/message/loginForm');
                }
            })
        }
        //fetch all message
        function list_content(page = 1){
            $.getJSON('/message/lists?page='+page,function(response){
                console.log(response);
                const table = $('#messages');
                table.html(`
                    <thead>
                        <tr>
                            <th></th>
                            <th>暱稱</th>
                            <th>留言內容</th>
                            <th>留言時間</th>
                            <th>管理員回覆</th>
                            <th>管理</th>
                        </tr>
                    </thead>
                    <tbody></body>
                `)
                const tbody = table.find('tbody');
                response.data.forEach(content => {
                    data_table(content,tbody)
                })
                $('#pagination').empty();
                let paginationHtml = "";
                let current = parseInt(response.current_page,10)
                let last = parseInt(response.last_page,10)
                if(current >1){
                    $('#pagination').append(`<button class="btn btn-secondary" onclick="list_content(${current-1})">上一頁</button>`);
                }
                $('#pagination').append(`<p style="display:inline-block">${current}/${last}</p>`);
                if(current < last){
                    console.log("current:",response.current_page," last:",response.last_page);
                    $('#pagination').append(`<button class="btn btn-secondary" onclick="list_content(${current+1})">下一頁</button>`);
                }
            })
        }
        
        //search
        $(document).ready(function(){
             $('#search').submit(function(event){
                select(event);
             });
        })
        function select(event,page = 1){
            if(event)event.preventDefault();
            $.ajax({
                url:'select?page='+page,
                method:'POST',
                data:$('#search').serialize(),
                success:function(response){
                    const table = $('#messages');
                    table.html(`
                        <thead>
                            <tr>
                                <th></th>
                                <th>暱稱</th>
                                <th>留言內容</th>
                                <th>留言時間</th>
                                <th>管理員回覆</th>
                                <th>管理</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    `)
                    const tbody = table.find('tbody');

                    if(response.message){
                        tbody.append(`<tr><td colspan="6" style="text-align=center; ">${response.message}</td></tr>`);
                    }
                    response.data.forEach(content=>{
                        data_table(content,tbody);
                    })
                    let paginationHtml = "";
                    $('#pagination').empty();
                    if(response.current_page >1){
                        $('#pagination').append(`<button class="btn btn-secondary" onclick="select(null,parseInt(${response.current_page})-1)">上一頁</button>`);
                    }
                    $('#pagination').append(`<p>${response.current_page}/${response.last_page}</p>`)
                    if(response.current_page < response.last_page){
                        console.log("current:",response.current_page," last:",response.last_page);
                        $('#pagination').append(`<button class="btn btn-secondary" onclick="select(null,parseInt(${response.current_page})+1)">下一頁</button>`)
                    }
                }
            })
        }
        //data table
        function data_table(content,tbody){
            let isReplyEnable = content.reply ? 'disabled':"";
            let isEditEnable = content.reply ? "":'disabled';
            tbody.append(`
                <tr>
                    <td>
                        <input class="checkbox" id="checkNum-${content.content_num}" type="checkbox">
                    </td>
                    <td>${content.user_nickname}</td>
                    <td>${content.content}</td>
                    <td>${content.created_at}</td>
                    <td>${content.reply || ""}</td>
                    <td>
                        <button type="button" class="reply-btn btn btn-secondary" data-id="${content.content_num}" data-bs-toggle="modal" data-bs-target="#exampleModal"  ${isReplyEnable}>回覆</button>
                        <button type="button" class="edit-btn btn btn-secondary" data-id="${content.content_num}" data-bs-toggle="modal" data-bs-target="#exampleModal2"  ${isEditEnable}>編輯回覆</button>
                        <button class="delete-btn btn btn-secondary" id="delete" data-id="${content.content_num}">刪除</button>
                    </td>
                </tr>
            `);
        }
        $(document).on('click','.reply-btn',function(){
            let row = $(this).closest("tr");
            $('#reply_ui').show();
            $('#edit_reply').hide();
            $('#content_num').val($(this).data("id"));
            $('#user_nickname').text(row.find("td:eq(1)").text());
            $('#replyed_content').text(row.find("td:eq(2)").text());
            console.log($('#content_num').val());
        });
        
        $(document).on('click','.edit-btn',function(){
            let row = $(this).closest("tr");
            $('#reply_ui').hide();
            $('#edit_reply').show();
            $('#exampleModal2').modal('show');
            $('#ed_content_num').val($(this).data("id"));
            $('#ed_user_nickname').text(row.find("td:eq(1)").text());
            $('#ed_content').text(row.find("td:eq(2)").text());
            $('#ed_reply').val(row.find("td:eq(4)").text());
        });

        $(document).on('click','.delete-btn',function(){
            let checkid = $(this).data('id');
            console.log(checkid);
            delete_content(checkid);
        });
        //reply 
        $(document).ready(function(){
            $('#reply_submit').click(function(event){
                event.preventDefault();
                $.ajax({
                    url:'reply',
                    method:'POST',
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data:$('#replyForm').serialize(),
                    success:function(response){
                        showAlert("回覆成功！",'success');
                        $('#exampleModal').modal('hide');
                    },
                    error:function(error){
                        showAlert("回覆失敗！",'error');
                    }
                })
            });
        })
        //edit reply
        $(document).ready(function(){
            $('#edit_submit').click(function(event){
            console.log("編輯留言");
                event.preventDefault();
                $.ajax({
                    url:'edit',
                    method:'POST',
                    data:$('#editForm').serialize(),
                    success:function(response){
                        location.reload();
                    },
                    error:function(error){
                        alert("回覆失敗");
                    }
                })
            });
        })
        //close reply/edit
        function closeForm(){
            $('#reply_ui').hide();
            $('#edit_reply').hide();
        }
        //delet function
        function delete_content(content_num){
            console.log("content_num",content_num);
            $.ajax({
                url:'delete',
                method:'POST',
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type':'application/json',
                },
                contentType:'application/json',
                data:JSON.stringify({ content_num: content_num }),
                success:function(){
                    showAlert("刪除成功！",'success');
                },
                error:function(error){
                    console.error('Error Message:', error);  
                    showAlert("刪除失敗！",'error');
                }
            })
        }
        //select all
        function select_all(){
            let isCheck = $('#select_all').prop("checked"); 
            $('.checkbox').prop("checked",isCheck);
        }
        //delete multiple
        function delete_multiple(){
            let checkIDs = $('.checkbox:checked').map(function(){
                return $(this).attr("id").replace("checkNum-","");
            }).get();
            console
            if(checkIDs == 0){
                alert('請選擇要刪除留言！')
                return;
            }else if(confirm('是否確定要刪除？')){
                checkIDs.forEach(id => console.log(delete_content(id)));
            }
        }
        //登出
        function logout(){
            fetch('/message/logout')
            .then(response =>{
                window.location.href = response.url;
                console.log('登出成功！');
                showAlert('登出成功！','success');
            })
        }
        //alert
        function showAlert(message,type){
            Swal.fire({
                title: message,
                icon:type,
                confirmButtonText:"確認"
            })
            .then(()=>{
                location.reload();
            });
        }
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
            <button class="btn btn-secondary" onclick="location.href = '/message/manager';">管理員列表</button>
        </div>
        <div style="width: 1200px; margin:auto">
            <div >
                <h1 style="text-align: center;">留言板</h1>
            </div>
            <div style="text-align: center;">
                <nav>
                    <div  class="pure-button-group" role="group" aria-label="...">
                        <button class="btn btn-outline-dark" onclick="list_content()">查看所有留言</button>
                    </div>
                </nav>
            </div>
            
        </div>

        <div class="content" style="text-align: center;">
            <div id="all" style="display: block; text-align: center;" >
                <h1>所有留言</h1>
                <div>
                    <form id="search" method="post">
                        @csrf
                        <label>開始時間</label>
                        <input type="date" id="start" name="start">
                        <label>結束時間</label>
                        <input type="date" id="end" name="end">
                        <label>關鍵字</label>
                        <input type="text" id="keyword" name="keyword">
                        <input class="btn btn-outline-dark" type="submit" value="搜尋">
                    </form>
                    <table class="table table-striped" id="messages" ,border="1" style="margin: auto;">
                    <input type="checkbox" id="select_all" onchange="select_all()">全選</input>   
                    <button id="delete_multiple"  class="btn btn-outline-dark" onclick="delete_multiple()">刪除勾選</button> 
                    <br>
                    </table>
                    <div id="pagination"></div>
                </div>
            </div>
            <!-- reply -->
            <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false"tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">回覆留言</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="replyForm">
                                @csrf
                                <input type="hidden" name="content_num" id="content_num">
                                <div class="mb-3">
                                    <label for="user_nickname" class="col-form-label">暱稱:</label>
                                    <p id="user_nickname"></p><br>
                                    </div>
                                <div class="mb-3">
                                    <label for="replyed_content" class="col-form-label">留言內容:</label>
                                    <p id="replyed_content"></p>
                                </div>
                                <div class="mb-3">
                                    <label for="reply" class="col-form-label">回覆留言:</label>
                                    <textarea class="form-control" name="reply" id="reply"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="close" type="button" onclick="closeForm()">關閉</button>
                            <button type="button" class="btn btn-primary" name="reply_submit" id="reply_submit" value="送出">回覆</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- edit reply -->
            <div class="modal fade" id="exampleModal2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">編輯回覆</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editForm">
                                @csrf
                                <input type="hidden" name="ed_content_num" id="ed_content_num">
                                <div class="mb-3">
                                    <label for="ed_user_nickname" class="col-form-label">暱稱:</label>
                                    <p id="ed_user_nickname"></p><br>
                                    </div>
                                <div class="mb-3">
                                    <label for="ed_content" class="col-form-label">留言內容:</label>
                                    <p id="ed_content"></p>
                                </div>
                                <div class="mb-3">
                                    <label for="ed_reply" class="col-form-label">修改留言:</label>
                                    <textarea class="form-control" id="ed_reply" name="ed_reply" ></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="close" type="button" onclick="closeForm()">關閉</button>
                            <button type="button" class="btn btn-primary" name="edit_submit" id="edit_submit" value="送出">回覆</button>
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
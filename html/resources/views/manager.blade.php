<!DOCTYPE html>
<html>
    <head>
        <title>管理者</title>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/purecss@3.0.0/build/pure-min.css" integrity="sha384-X38yfunGUhNzHpBaEBsWLO+A0HDYOQi8ufWDkZ0k9e0eXz/tH3II7uKZ9msv++Ls" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <style>
        th,td,p{
            font-size: 24px;
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
            list_manager();
        }
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
        //管理員列表
        function list_manager(){
            $.getJSON('/message/managerList',function(data){
                console.log(data);
                const table = $('#managerTable');
                table.html(`
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>管理員名字</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody></tbody>`
                );
                const tbody = table.find('tbody');
                data.forEach(content => {
                    data_table(content,tbody);
                });
            })
        }
        //刪除管理員
        function delete_manager(manager_id){
            $.ajax({
                url:'deleteManager',
                method:'POST',
                headers:{
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                contentType:'application/json',
                data:JSON.stringify({ manager_id: manager_id }),
                success:function(response){
                    if(response.success){
                        showAlert("刪除成功！","success");
                    }else{
                        showAlert("刪除失敗！","error");
                    }
                    
                },
                error: function(error) {
                    showAlert("刪除失敗！","error");
                    console.error("Error:", error);
                }
            })
        }
        $(document).on('click','.delete-btn',function(){
            let manager_id = $(this).data('id');
            console.log('manager_id',manager_id);
            delete_manager(manager_id);
        }) 
        function data_table(content,tbody){
            tbody.append(`
                <tr>
                    <td>${content.manager_id}</td>
                    <td>${content.manager_name}</td>
                    <td>
                        <button class="delete-btn btn btn-secondary" data-id="${content.manager_id}">刪除</button>
                    </td>
                </tr>
            `);
        }
        //新增管理員
        function add_manager_window(){
            $('#add').show();
        }
        $(document).ready(function(){
            $('#add').click(function(event){
                event.preventDefault();
                console.log($('#addForm').serialize());
                $.ajax({
                    url:'addManager',
                    method:'POST',
                    headers:{
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    data:$('#addForm').serialize(),
                    success: function(response) {
                        showAlert("新增成功！","success");
                        
                    },
                    error: function(error) {
                        showAlert("新增失敗！","error");
                    }
                })
            })
        })
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
        <h1>管理員列表</h1>
        <div>
            <p style="display:inline-block">目前登入管理員：{{ Auth::user()->manager_name }}</p>
            <form id="logout" method="POST" action="{{ route('logout') }}" style="display: inline-block; margin-left: 20px;">
                @csrf
                <button class="btn btn-outline-primary" type="submit">登出</button>
            </form>
            <br>
            <button class="btn btn-secondary" onclick="location.href = '{{ route('message.contentView') }}';">查看所有留言</button>
        </div>
       
        <div id="manager" style="text-align: center;">
            <table class="table table-striped"id="managerTable"> 
            </table>
        </div>
        <button type="button" class="reply-btn btn btn-secondary" data-id="${content.content_num}" data-bs-toggle="modal" data-bs-target="#exampleModal"  ${isReplyEnable}>新增管理員</button>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">新增管理員</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form id="addForm" method="POST">
                        @csrf
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="managerName" class="col-form-label">請輸入管理員姓名</label>
                            </div>
                            <div class="col-auto">
                                <input type="text" id="managerName"name="manager_name" class="form-control">
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="managerAccount" class="col-form-label">請輸入管理員帳號</label>
                            </div>
                            <div class="col-auto">
                                <input type="text" id="managerAccount" name="account" class="form-control">
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="managerPassword" class="col-form-label">請輸入管理員密碼</label>
                            </div>
                            <div class="col-auto">
                                <input type="password" id="managerPassword"  name="password" class="form-control">
                            </div>
                        </div>
                    </form> 
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="close" type="button" onclick="closeForm()">關閉</button>
                        <button type="button" class="btn btn-primary" name="add" id="add" value="送出">提交</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
@if ($errors->any())
    <script>
        var myModal = new bootstrap.Modal(document.getElementById('addManager'));
        myModal.show();
    </script>
@endif
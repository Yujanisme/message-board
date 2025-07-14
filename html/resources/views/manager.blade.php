@extends('layout.main')
<html>
    <head>
        <title>管理者</title>
        <meta charset="utf-8">
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

    <body>
        <h1>管理員列表</h1>
        <div>
            <p style="display:inline-block">目前登入管理員：</p>
            <p style="display:inline-block" id="managerName"></p>
            <br>
            <button class="btn btn-secondary" onclick="location.href = '/message/content';">查看所有留言</button>
        </div>
       
        <div id="manager" style="text-align: center;">
            <table class="table table-striped"id="managerTable"> 
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>管理員名字</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($managers as $manager)
                        <tr>
                            <td>{{ $manager->id }}</td>
                            <td>{{ $manager->manager_name }}</td>
                            <td>
                                <form id="deleteManager" method="POST" action="{{ route('manager.delete', $manager->id) }}" style="display:contents">
                                    @csrf
                                    <button class="btn btn-danger delete-btn" data-id="{{ $manager->id }}">刪除</button>
                                </form>
                                    <button class="btn btn-info" onclick="location.href='{{ route('manager.updatePasswordView', ['id' => $manager->id]) }}'">更改密碼</button>
                            </td>
                        </td>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- 新增管理員 -->
        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addManager" >新增管理員</button>

        <div class="modal fade" id="addManager" tabindex="-1" aria-labelledby="addManager" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addManager">新增管理員</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addForm" method="POST" action=" {{ route('manager.add') }}">
                            @csrf
                            <div class="row g-3 align-items-center">
                                <div class="col-auto">
                                    <label for="managerName" class="col-form-label me-2">請輸入管理員姓名</label>
                                    <input type="text" id="managerName" name="manager_name" class="form-control d-inline-block" style="width:auto;" value="{{ old('manager_name') }}"> 
                                    @error('manager_name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3 align-items-center">
                                <div class="col-auto">
                                    <label for="managerAccount" class="col-form-label me-2">請輸入管理員帳號</label>
                                    <input type="text" id="managerAccount" name="account" class="form-control d-inline-block" style="width:auto;" value="{{ old('account') }}">
                                    @error('account')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3 align-items-center">
                                <div class="col-auto">
                                    <label for="managerPassword" class="col-form-label me-2">請輸入管理員密碼</label>
                                    <input type="password" id="managerPassword" name="password" class="form-control d-inline-block" style="width:auto;" value="{{ old('password') }}">
                                    @error('password')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </form> 
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="close">關閉</button>
                        <button type="submit" class="btn btn-primary" id="submitAddManager" form="addForm">提交</button>
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
<script>
    window.onload = function(){
        // checklogin();
    }

    //檢查登入狀態
    // function checklogin(){
    //     $.getJSON('checkSession',function(data){
    //         if(data.isLogin == true){
    //             $('#managerName').text(data.auth.manager_name);
    //         }
    //         else{
    //             window.location.assign('/message/loginForm');
    //         }
    //     })
    // }

    //刪除管理員
    // $('.delete-btn').on('click', function() {
    //     let manager_id = $(this).data('id');
    //     let deleteUrl = 'deleteManager/' + manager_id;
    //     $.ajax({
    //         url:deleteUrl,
    //         method:'POST',
    //     })
    // }) 
</script>
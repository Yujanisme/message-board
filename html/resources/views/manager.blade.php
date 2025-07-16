@extends('layout.main')

@section('title', '管理員列表')

@section('content')
    <h1>管理員列表</h1>
    <div>
        <h3 style="display:inline-block">目前登入管理員：{{ Auth::user()->manager_name }}</h3>
        <form id="logout" method="POST" action="{{ route('logout') }}" style="display: inline-block; margin-left: 20px;">
            @csrf
            <button class="btn btn-outline-primary" type="submit">登出</button>
        </form>
        <br>
        <button class="btn btn-secondary" onclick="location.href = '{{ route('message.contentView') }}';">查看所有留言</button>
    </div>

    <div class="container-md" style="text-align: center; margin-top: 20px;">
        <table class="table table-striped" id="managerTable" ,border="1" style="margin: auto;">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>管理員名字</th>
                    <th>操作</th>
                </tr>
            </thead>
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
    @if ($errors->any())
        <script>
            var myModal = new bootstrap.Modal(document.getElementById('addManager'));
            myModal.show();
        </script>
    @endif
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            // 列表
            const columns = [
                { data: 'id' },
                { data: 'manager_name' },
                { data: 'action', orderable: false, searchable: false }
            ];

            const table = initDataTable('#managerTable', "{{ route('manager.list') }}", columns);

            // 刪除管理員
            $('#managerTable').on('click', '.delete-btn',function() {
                console.log('刪除按鈕被點擊');
                let checkid = $(this).data('id');
                Swal.fire({
                    title: '確定要刪除這個管理員嗎？',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '刪除',
                    cancelButtonText: '取消'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/manager/deleteManager/${checkid}`,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire('已刪除', '', 'success');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire('錯誤', '刪除失敗', 'error');
                            }
                        });
                    }
                });
            });


        })
    </script>
@endsection
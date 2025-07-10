<!DOCTYPE html>
<html>
    <head>
        <title>留言板</title>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/purecss@3.0.0/build/pure-min.css" integrity="sha384-X38yfunGUhNzHpBaEBsWLO+A0HDYOQi8ufWDkZ0k9e0eXz/tH3II7uKZ9msv++Ls" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <style>
        .bigfont {
            font-size: 20px;
        }
        p{
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
            lists(1);
        }
        function lists(page = 1){
            $.getJSON('message/lists?page='+page,function(response){
                console.log(response);
                console.log(response.data);
                const table = $('#messages');
                table.html(`
                    <thead>
                        <tr>
                            <th>暱稱</th>
                            <th>留言內容</th>
                            <th>留言時間</th>
                            <th>管理員回覆</th>
                        </tr>
                    </thead>
                    <tbody></body>`
                );
                const tbody = table.find('tbody');
                response.data.forEach(content => {
                    data_table(content,tbody);
                });
                
                $('#pagination').empty();
                let paginationHtml = "";
                if(response.current_page >1){
                    $('#pagination').append(`<button class="btn btn-primary" onclick="lists(parseInt(${response.current_page})-1)">上一頁</button>`);
                }
                $('#pagination').append(`<p style="display:inline-block">${response.current_page}/${response.last_page}</p>`);
                if(response.current_page < response.last_page){
                    console.log("current:",response.current_page," last:",response.last_page);
                    $('#pagination').append(`<button class="btn btn-primary" onclick="lists(parseInt(${response.current_page})+1)">下一頁</button>`)
                }
            })
            .fail (function(error){
                console.error('Error Message:', error);  
            })
        }
        //data table
        function data_table(content,tbody){
            tbody.append(`
                <tr>
                    <td>${content.user_nickname}</td>
                    <td>${content.content}</td>
                    <td>${content.created_at}</td>
                    <td>${content.reply || ""}</td>
                </tr>
            `)   
        }
        $(document).ready(function(){
            $('#addMessage').click(function(event){
                event.preventDefault();
                $.ajax({
                    url:'message/create',
                    method:'POST',
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data:$('#addForm').serialize(),
                    success:function(response){
                        showAlert('留言成功','success');
                        $('addNewMessage').modal('hide');
                    },
                    error:function(error){
                        showAlert('留言失敗','error');
                    }
                })
            })
        })
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
        <div style="text-align: center;">
            <div style="text-align: center; margin: 22px;"> 
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewMessage" style="font-size:24px">我要留言</button>
            </div>
            <div class="modal fade" id="addNewMessage" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="addNewMessage">新增留言</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="pure-form" id="addForm" style="text-align: center;">
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
                            <button type="button" class="btn btn-primary" name="addMessage" id="addMessage" value="送出">送出</button>
                        </div>
                    </div>
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
                                <th>管理</th>
                            </tr>
                       </thead> 
                       <tbody></tbody>
                </table>
               <div id="pagination"></div>
            </div>
            
        </div>
        
    </body>
</html>
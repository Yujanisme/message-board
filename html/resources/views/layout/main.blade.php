<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <!-- DataTables JS + CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- datatable -->
        <script src="{{ asset('js/datatable.js') }}"></script>
        @yield('head')
    </head>

    <body>
        @yield('content')

        @yield('scripts')

        <style>
            table.dataTable thead th {
                text-align: center;
            }
        </style>
        
        <script>
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '成功',
                    text: '{{ session('success') }}',
                    confirmButtonText: '確定'
                });
            @elseif (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: '錯誤',
                    text: '{{ session('error') }}',
                    confirmButtonText: '確定'
                });
            @elseif (session('info'))
                Swal.fire({
                    icon: 'info',
                    text: '{{ session('warning') }}',
                    confirmButtonText: '確定'
                });
            @endif

            // ajax全域設定 - CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // modal設定 - 關閉時重新載入頁面
            $('.modal').on('hidden.bs.modal', function () {
                location.reload();
            });
        </script>
    </body>
</html>


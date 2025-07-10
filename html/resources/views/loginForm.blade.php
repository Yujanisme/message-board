<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>登入</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/purecss@3.0.0/build/pure-min.css" integrity="sha384-X38yfunGUhNzHpBaEBsWLO+A0HDYOQi8ufWDkZ0k9e0eXz/tH3II7uKZ9msv++Ls" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </head>
    <body>
        <h1>管理員登入</h1>
        <form class="pure-form" method="post" action="{{ route('login.submit') }}" id="signin" style="font-size: 22px;">
            @csrf
            <label>帳號</label>
            <input type="text" name="signin_account" placeholder="請輸入帳號" style="font-size: 20px;">
            <br>
            <label>密碼</label>
            <input type="password" name="signin_password" placeholder="請輸入密碼" style="font-size: 20px;">
            <br>
            <input class="btn btn-primary" type="submit" name="signin" value="登入">
        </form>
    </body>
</html>
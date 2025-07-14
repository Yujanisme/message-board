@extends('layout.main')

<html>
    <body>
        <div class="container">
            <h1>修改密碼</h1>
            <form method="POST" action="{{ route('manager.updatePassword', $manager->id) }}">
                @csrf
                <div class="mb-3">
                    <label for="account" class="form-label">帳號</label>
                    <input type="text" class="form-control" id="account" name="account" value="{{ $manager->account }}" readonly>
                    @error('account')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="old_password" class="form-label">舊密碼</label>
                    <input type="password" class="form-control" id="old_password" name="old_password" required>
                    @error('old_password')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">新密碼</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                    @error('new_password')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">更新密碼</button>
            </form>
        </div>
    </body>
</html>
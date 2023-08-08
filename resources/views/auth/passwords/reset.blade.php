@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <label for="username">Username</label>
        <input type="text" name="username" value="{{ $username }}" disabled>

        <label for="password">New Password</label>
        <input type="password" name="password" required>

        <label for="password_confirmation">Confirm Password</label>
        <input type="password" name="password_confirmation" required>

        <button type="submit">Reset Password</button>
    </form>
@endsection

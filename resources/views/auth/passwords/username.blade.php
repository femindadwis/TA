

    <form method="POST" action="{{ route('resetpassword') }}">
        @csrf

        <label for="username">Username</label>
        <input type="text" name="username" required>

        <button type="submit">Submit</button>
    </form>


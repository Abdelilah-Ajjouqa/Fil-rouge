<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pinterest | login</title>
</head>
<body>
    <form action="{{ route('auth.login') }}" method="post">
        @csrf
        <label for="email">Email</label><br>
        <input type="text" name="email" placeholder="Email"><br><br>
        <label for="password">Password</label><br>
        <input type="password" name="password" placeholder="Password"><br><br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have account ? <a href="{{ route('auth.register.form') }}">Register now</a></p>
</body>
</html>

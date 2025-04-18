<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="{{route('auth.register')}}" method="post">
        @csrf
        <label for="first_name">First name</label><br>
        <input type="text" name="first_name" id="first_name"><br>

        <label for="last_name">Last name</label><br>
        <input type="text" name="last_name" id="last_name"><br>

        <label for="username">Username</label><br>
        <input type="text" name="username" id="username"><br>

        <label for="email">Email</label><br>
        <input type="text" name="email" id="email"><br>

        <label for="password">Password</label><br>
        <input type="password" name="password" id="password"><br>

        <label for="password_confirmation">Password confirmation</label><br>
        <input type="password" name="password_confirmation" id="password_confirmation"><br>

        <button type="submit">Submit</button>
        <br><br>
    </form>
</body>
</html>

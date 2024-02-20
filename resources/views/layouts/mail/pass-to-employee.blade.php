<!DOCTYPE html>
<html>

<head>
    <title>Բարի գալուստ Museum</title>
</head>

<body>

    <p>Lorem ipsuum,</p>

    <p>Link</p>

    <p>Your pass: {{ $data['password'] }}</p>
    <p>Your email: {{ $data['email'] }}</p>
    <p> <a href="{{ request()->getSchemeAndHttpHost() . '/auth/login-basic' }}">Link to login</a></p>
    <p> Welcome to the Team!</p>
    <p> Webex </p>
</body>

</html>

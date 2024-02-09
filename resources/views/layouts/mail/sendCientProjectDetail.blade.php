<!DOCTYPE html>
<html>
<head>
    <title>Клиент проект детали</title>
</head>
<body>
   <p>Имя: {{$data['name']}} </p>
   <p>Телефон: {{$data['phone']}} </p>
   <p>Майл: {{$data['email']}} </p>
    @if($data['domain'] )
        <p>Сайт: {{$data['domain']}} </p>
    @endif
    @if($data['message'] )
        <p>Сообщения: {{$data['message']}} </p>
    @endif
</body>
</html>
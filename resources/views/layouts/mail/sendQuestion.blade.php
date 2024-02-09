<!DOCTYPE html>
<html>
<head>
    <title>Новый Вопрос</title>
</head>
<body>
    @if($data['name'] )
        <p>Имя: {{$data['name']}} </p>
    @endif
    @if($data['phone'] )
        <p>Телефон: {{$data['phone']}} </p>
    @endif
   <p>Майл: {{$data['email']}} </p>
   <p>Сообщение: {{$data['message']}} </p>
</body>
</html>
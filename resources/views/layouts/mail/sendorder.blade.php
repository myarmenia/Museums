<!DOCTYPE html>
<html>
<head>
    <title>Новый заказ</title>
</head>
<body>
   <p>Имя: {{$data['name']}} </p>
   <p>Телефон: {{$data['phone']}} </p>
   <p>Майл: {{$data['email']}} </p>
    @if($data['domain'] )
        <p>Сайт: {{$data['domain']}} </p>
    @endif
    @if($data['comment'] )
        <p>Коментария: {{$data['comment']}} </p>
    @endif
    @if($data['projectType'] )
        <p>Проект: {{$data['projectType']}} </p>
    @endif
    @if($data['projectType'] )
        <p>Услуги: {{$data['projectType']}} </p>
    @endif
    @if(isset($data['project']) &&  $data['project'] )
        <p>Проект: {{$data['project']}} </p>
    @endif
   
</body>
</html>
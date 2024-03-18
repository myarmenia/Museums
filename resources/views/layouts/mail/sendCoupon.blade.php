<!DOCTYPE html>
<html>
<head>
    <title>Բարի գալուստ Թանգարանի կայք</title>
    <style>
        h4 {
            margin: 0;
        }
    </style>
</head>
<body>
    <div style="display: flex">
        <h4>Կուպոն - </h4> &ensp;
        <h4>{{$data['coupon']}}</h4>
    </div>
    <div style="display: flex">
        <h4>Թանգարան - </h4> &ensp;
        <h4>{{$data['museum_name']}}</h4>
    </div>
    <div style="display: flex">
        <h4>Ուժի մեջ է մինչև - </h4> &ensp;
        <h4>{{$data['ttl_at']}}</h4>
    </div>
    <div style="display: flex">
        <h4>Տոմսերի քանակ - </h4> &ensp;
        <h4>{{$data['tickets_count']}}</h4>
    </div>
    <br>
    <div>
        <h4>Թանգարան այցելելիս ներկայացրեք Կուպոնը՝ տոմսերը ստանալու համար: </h4>
        <h4> Հարգանքներով ՝ Հայաստանի թանգարաններ</h4>
    </div>
    
</body>
</html>
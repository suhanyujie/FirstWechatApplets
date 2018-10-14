<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>列表</title>
</head>
<body>
    <div class="container">
        <ul>
            @foreach($articles as $k=>$article)
            <li>
                <a href="/article/{{$article->id}}"> {{$article->title}} </a>
            </li>
            @endforeach
        </ul>
    </div>
</body>
</html>
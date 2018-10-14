<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$article->title}}</title>
    <style>
        .container{margin:10px auto;}

    </style>
</head>
<body>
    <div class="container">
        <h2 style="margin:auto;">{{$article->title}}</h2>
        <div class="content">
            {!! $article->content !!}
        </div>

    </div>
</body>
</html>
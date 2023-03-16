<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Letters</title>
    <link rel="stylesheet" href="{{public_path('css\pdf\paper.css')}}">
</head>

<body>
    @for ($index = 0; $index < 3; $index++) 
    <div class="{{ ($index < 3) ? 'page-break' : '' }}" id="app">
        <header class="header">
            soy el header
        </header>
        <main class="main">
            Pagina {{$index}}
            @for ($i = 0; $i < 20; $i++) <p>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                Officiis ducimus molestiae natus. Beatae adipisci facere autem nobis,
                possimus blanditiis iure soluta accusamus id
                deserunt? Provident fuga vel magnam accusantium ducimus.
                </p>
                @endfor
        </main>
        <footer class="footer">
            soy el footer
        </footer>
        </div>
        @endfor
</body>

</html>
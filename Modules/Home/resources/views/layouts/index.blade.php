<!DOCTYPE html>
<html lang="en">

<head>
    <title>Loggingpedia</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('home::bases.css')
</head>

<body>
    @include('home::components.header')
    @include('home::components.navigation')
    @include('home::components.main.jumbotron')
    @include('home::components.main.article-1')
    @include('home::components.main.about')
    @include('home::components.main.article-2')
    @include('home::components.main.article-3')
    @include('home::components.main.article-4')
    @include('home::components.main.testimonial')
    @include('home::components.main.comment')
    {{-- @include('home::components.main.pricing') --}}
    {{-- @include('home::components.main.article-5') --}}
    @include('home::components.footer')
    @include('home::bases.js')
</body>

</html>

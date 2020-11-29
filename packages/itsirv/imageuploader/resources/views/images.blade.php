<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons' rel="stylesheet">
        <title>ImageUploader</title>
    </head>
    <body>
{{--        <div>--}}
{{--            @foreach ($images as $image)--}}
{{--                    <img src="https://storage.googleapis.com/{{ $image->bucket }}/{{ $image->name }}" alt="{{ $image->name }}" />--}}
{{--            @endforeach--}}
{{--        </div>--}}
    <div id="app">
        <image-uploader></image-uploader>
    </div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>

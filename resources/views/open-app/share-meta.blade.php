<html>
    <head>
        <title>{{$title}}</title>
        <meta name="description" content="{{$description}}"> 
        <meta property="og:title" content="{{$title}}" />
        <meta property="og:type" content="video.movie"/> 
        <meta property="og:image" content="{{$og_image}}" /> 
    </head>
</html>

<script>
    setTimeout(function () { window.location = '{{$redirect_url}}' }, 25);
    window.location = '{{$deeplink_url}}';
</script>
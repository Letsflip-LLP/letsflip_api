<html>
    <head>
        <title>{{$title}}</title>
        <meta name="description" content="{{$description}}"> 
        <meta property="og:title" content="{{$title}}" />
        <meta property="og:type" content="video.movie"/> 
        <meta property="og:image" content="{{$og_image}}" /> 
    </head> 
    <a href="{{$redirect_url}}">Click</a>

<script>  
    setTimeout(function(){ location.href = '{{$redirect_url}}';},25); 
    location.href = '{{$deeplink_url}}'; 
</script>

</html>  

 
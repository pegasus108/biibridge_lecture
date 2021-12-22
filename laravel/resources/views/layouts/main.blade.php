<!DOCTYPE HTML>
<html lang="ja">
@hassection('top')
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# website: http://ogp.me/ns/website#">
<meta property="og:type" content="website" />
@else
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
<meta property="og:type" content="article" />
@endif
<meta name="google-site-verification" content="SHEz0Ks4XQWzO_OODSsEbAunn9bFx0K07K6glKb89TM" />
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ config('ga.id') }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){window.dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '{{ config('ga.id') }}');
</script>
<meta charset="utf-8">
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=1">
<meta name="format-detection" content="telephone=no">
<link rel="icon" href="{{ asset('favicon.ico') }}">
<link href="https://unpkg.com/ress/dist/ress.min.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" media="screen and (min-width:961px),print" href="{{ asset('css/style_pc.css') }}">
<link rel="stylesheet" type="text/css" media="screen and (max-width:960px)" href="{{ asset('css/style_sp.css') }}">
<link rel="stylesheet" type="text/css" media="screen,print" href="{{ asset('css/style.css') }}">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
@hasSection ('title')
<title>@yield('title') | SHIBAKAWA BOOK STORE</title>
<meta property="og:title" content= "@yield('title') | SHIBAKAWA BOOK STORE"  />
<meta property="og:description" content="@yield('title')" />
@else
<title>SHIBAKAWA BOOK STORE</title>
<meta property="og:title" content="SHIBAKAWA BOOK STORE" />
<meta property="og:description" content="SHIBAKAWA BOOK STORE" />
@endif
<meta property="og:image" content="{{ asset('image/common/logo.png') }}" />
<meta property="og:url" content="{{ request()->fullUrl() }}"/>
<meta property="og:site_name" content="SHIBAKAWA BOOK STORE" />
<meta property="fb:app_id" content="000000000000000" />
<meta name="twitter:card" content="photo" />
<meta name="twitter:site" content="@shibakawabookstore" />
<meta name="twitter:player" content="@shibakawabookstore" />
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/modal-video@2.4.2/js/jquery-modal-video.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/modal-video@2.4.2/css/modal-video.min.css" />
<link rel="stylesheet" href="{{ asset('css/inc_index.css') }}">
<script src="{{ asset('/js/youtube.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1/slick/slick.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1/slick/slick.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1/slick/slick-theme.min.css">
<script>
$(function(){
    $('.slider').slick({
        variableWidth :true,
        centerMode: true,
        dots:true,
        focusOnSelect:true,
        autoplay: true,
    });
});
</script>
</head>

<body>
    <x-header />
    @yield('content')
    <x-footer />
    {{-- <x-footer :company="$company" /> --}}
</body>
</html>

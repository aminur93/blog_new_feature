<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Blog - @yield('page')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- styles -->
    <link href="{{ asset('frontend/assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/bootstrap-responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/prettyPhoto.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/js/google-code-prettify/prettify.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/flexslider.css') }}" rel="stylesheet">

    <link href="{{ asset('frontend/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/assets/color/default.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Droid+Serif:400,600,400italic|Open+Sans:400,600,700" rel="stylesheet">

    <!-- fav and touch icons -->
    <link rel="shortcut icon" href="assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset('frontend/assets/ico/apple-touch-icon-144-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset('frontend/assets/ico/apple-touch-icon-114-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset('frontend/assets/ico/apple-touch-icon-72-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('frontend/assets/ico/apple-touch-icon-57-precomposed.png') }}">

    @stack('css')

    <!-- =======================================================
      Theme Name: Lumia
      Theme URL: https://bootstrapmade.com/lumia-bootstrap-business-template/
      Author: BootstrapMade.com
      Author URL: https://bootstrapmade.com
    ======================================================= -->
</head>


<body>
<div id="wrapper">
    @include('layouts.front.header')
    <!-- Subintro
================================================== -->
    <section id="subintro">
        <div class="container">
            <div class="row">
                <div class="span8">
                    <ul class="breadcrumb">
                        <li><a href="#"><i class="icon-home"></i></a><i class="icon-angle-right"></i></li>
                        <li><a href="#">Blog</a><i class="icon-angle-right"></i></li>
                        <li class="active">Programming News Blog</li>
                    </ul>
                </div>
                <div class="span4">
                    <div class="search">
                        <form method="post" id="search_post" class="input-append">
                            @csrf
                            <input style="width: 300px;" class="form-control search-form" id="title" name="title" type="text" autocomplete="off" placeholder="Search here.." />
                            <button class="btn btn-dark" type="submit">Search</button>
                            <div id="postTitle"></div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="maincontent">
        <div class="container">
            <div class="row">
                @yield('content')
            </div>
        </div>
    </section>
    <!-- Footer
 ================================================== -->
    @include('layouts.front.footer')

</div>
<!-- end wrapper -->
<a href="#" class="scrollup"><i class="icon-chevron-up icon-square icon-48 active"></i></a>
<script src="{{ asset('frontend/assets/js/jquery.js') }}"></script>
<script src="{{ asset('frontend/assets/js/raphael-min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/jquery.easing.1.3.js') }}"></script>
<script src="{{ asset('frontend/assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('frontend/assets/js/google-code-prettify/prettify.js') }}"></script>
<script src="{{ asset('frontend/assets/js/jquery.elastislide.js') }}"></script>
<script src="{{ asset('frontend/assets/js/jquery.prettyPhoto.js') }}"></script>
<script src="{{ asset('frontend/assets/js/jquery.flexslider.js') }}"></script>
<script src="{{ asset('frontend/assets/js/jquery-hover-effect.js') }}"></script>
<script src="{{ asset('frontend/assets/js/animate.js') }}"></script>

<!-- Template Custom JavaScript File -->
<script src="{{ asset('frontend/assets/js/custom.js') }}"></script>

<script>
    $(document).ready(function () {
       $("#title").keyup(function () {
           var query = $(this).val();
            if(query != '') {
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url: "{{ route('post.fetch') }}",
                    method: "POST",
                    data: {query: query, _token: _token},
                    success: function (data) {
                        $("#postTitle").fadeIn();
                        $("#postTitle").html(data);
                    }
                });
            }
       });

        $("#search_post").on("submit",function (e) {
            e.preventDefault();

            var title = $("#title").val();
            var _token = $('input[name="_token"]').val();

            $.ajax({
                url: "{{ route('post.search') }}",
                type: "post",
                data: {title:title,_token:_token},
                success: function (data) {
                    $("#seach4").html(" ");
                    $("#seach4").html(data);
                }
            });
        });

        $(document).on('click', 'li', function(){
            $('#title').val($(this).text());
            $('#postTitle').fadeOut();
        });

    });
</script>

@stack('js')

</body>

</html>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
        <title>@yield('title') - {{ config('app.name') }}</title>

        <meta name="description" content="overview &amp; stats" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

        <!-- bootstrap & fontawesome -->
        <link rel="stylesheet" href="{{ asset('/admin/assets/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('/admin/assets/font-awesome/4.5.0/css/font-awesome.min.css') }}" />

        <!-- page specific plugin styles -->

        <!-- text fonts -->
        <link rel="stylesheet" href="{{ asset('/admin/assets/css/fonts.googleapis.com.css') }}" />

        <!-- ace styles -->
        <link rel="stylesheet" href="{{ asset('/admin/assets/css/ace.min.css') }}" />

        <!--[if lte IE 9]>
            <link rel="stylesheet" href="{{ asset('/admin/assets/css/ace-part2.min.css') }}" />
        <![endif]-->
        <link rel="stylesheet" href="{{ asset('/admin/assets/css/ace-skins.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('/admin/assets/css/ace-rtl.min.css') }}" />

        <!--[if lte IE 9]>
          <link rel="stylesheet" href="{{ asset('/admin/assets/css/ace-ie.min.css') }}" />
        <![endif]-->

        <!-- inline styles related to this page -->

        <!-- ace settings handler -->
        <script src="{{ asset('/admin/assets/js/ace-extra.min.js') }}"></script>

        <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

        <!--[if lte IE 8]>
        <script src="{{ asset('/admin/assets/js/html5shiv.min.js') }}"></script>
        <script src="{{ asset('/admin/assets/js/respond.min.js') }}"></script>
        <![endif]-->
    </head>

    <body class="no-skin">
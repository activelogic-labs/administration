<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $system_title }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link href="{{ elixir('css/administration-app.css', 'vendor/administration/build') }}" rel="stylesheet">
    <link rel="stylesheet" href="/vendor/administration/components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />

    @if(!empty(config('admin.styles')))
        @foreach (config('admin.styles') as $style)
            <link href="{{ $style }}" rel="stylesheet">
        @endforeach
    @endif

    <link rel="stylesheet" href="/vendor/administration/components/selectize/dist/css/selectize.default.css" />
    <script src="https://use.fontawesome.com/d1e2052865.js"></script>

</head>
<body id="app-layout">

    <div id="main">
        {{--<div id="upload_hotspot"></div>--}}
        <div id="navigation">

            <!-- Navigation Header -->
            <div class="header">
                <a href="/{{ config('admin.base_uri') }}" class="logo">
                    <img src="{{ config("admin.logo_url") }}">
                </a>
                <a href="#" class="collapse">
                    <span class="fa fa-chevron-circle-left"></span>
                </a>
            </div>

            <!-- Navigation Buttons -->
            <ul>
                @foreach($navigation as $nav)

                    @if(isset($nav->class))

                        @can('view.nav', $nav->class)

                            <li>
                                <a href="{{ $nav->url }}" @if($nav->linkOut) target="_blank" @endif>
                                    <i class="fa {{ $nav->icon }}"></i>
                                    <span>{{ $nav->title }}</span>
                                </a>
                            </li>

                        @endcan

                    @else

                        <li>
                            <a href="{{ $nav->url }}">
                                <i class="fa {{ $nav->icon }}"></i>
                                <span>{{ $nav->title }}</span>
                            </a>
                        </li>

                    @endif

                @endforeach
            </ul>
        </div>

        <div id="content">

            <!-- Toolbar -->
            <div class="toolbar">
                <a href="#" id="menu_anchor">
                    <i class="fa fa-bars"></i>
                </a>

                @if(view()->exists('toolbar'))
                    @include("toolbar")
                @else
                    @include("administration::toolbar")
                @endif
            </div>

            @yield("header")

            @if($error)
            <div class="error">
                <a href="#" class="dismiss"><i class="fa fa-times"></i></a>
                <strong><i class="fa fa-exclamation-circle"></i> Error!</strong> {{ $error }}
            </div>
            @endif

            @if (count($errors) > 0)
                <div class="error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li><i class="fa fa-exclamation-circle"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($success)
            <div class="success">
                <a href="#" class="dismiss"><i class="fa fa-times"></i></a>
                <strong><i class="fa fa-check-circle"></i> Success!</strong> {{ $success }}
            </div>
            @endif

            <div class="area">
                @if($modules)
                    <div id="modules">
                        @foreach($modules as $module)
                            <div class="module">
                                <div class="container">{!! $module !!}</div>
                            </div>
                        @endforeach
                        <div class="clearfix"></div>
                    </div>
                @endif

                @yield("content")
            </div>

        </div>
    </div>

    <script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="/vendor/administration/components/stacktable.js/stacktable.js"></script>
    <script src="/vendor/administration/components/dropzone/dist/min/dropzone.min.js"></script>
    <script src="//cdn.ckeditor.com/4.5.10/standard/ckeditor.js"></script>
    <script src="/vendor/administration/components/microplugin/src/microplugin.js"></script>
    <script src="/vendor/administration/components/selectize/dist/js/selectize.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.2/Chart.bundle.min.js"></script>
    <script type="text/javascript" src="/vendor/administration/components/moment/min/moment.min.js"></script>
    <script type="text/javascript" src="/vendor/administration/components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

    <script>

        Chart.defaults.global.responsive = false;
        Chart.defaults.global.maintainAspectRatio = false;
        Chart.defaults.global.legend.display = false;
        Chart.defaults.global.tooltips.enabled = false;

    </script>

    <script src="{{ elixir('js/administration-all.js', 'vendor/administration/build') }}"></script>

    @if(!empty(config('admin.scripts')))

        @foreach (config('admin.scripts') as $script)

            <script src="{{ $script }}"></script>

        @endforeach

    @endif

    <script>

        @if(isset($filterable))

            Filters.init({!! json_encode($filterable) !!});

        @endif

        $(function(){

            $(".datepicker").datetimepicker({
                format : "MMMM DD, YYYY"
            });

            $(".timepicker").datetimepicker({
                format : "h:mm A"
            });

            $(".datetimepicker").datetimepicker({
                format : "MMMM DD, YYYY @ h:mm A"
            });

        });

    </script>

    @stack("js")

    @yield("scripts")

</body>
</html>

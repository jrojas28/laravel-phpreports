<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    @if (isset($title))
        <title>{{ $title }}</title>
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('sub-css')
    @yield('sub-js')
  </head>

  <body>
    <div class="col-xs-12">
		<div class='header'>
            @if (!isset($header))
            <?php  $header = ""; ?>
            @endif

            @yield('header', $header)
		</div>

		{{-- {% section notice_area %} --}}
        @if (isset($error) && $error)
            <div class='alert alert-danger'>
                @if (isset($error['message']))
                    {!! $error['message'] !!}
                @else
                    {!!$error!!}
                @endif
            </div>
        @endif
        @if (isset($notice) && $notice)
            <div class='alert alert-info'>
                {{$notice}}
            </div>
        @endif
		{{-- {% endsection %} --}}

		<div id='content'>
		{{-- {% section content %} --}}
			@if (isset($content))
			    {!!$content!!}
            @else
                @yield('sub-content')
			@endif
		{{-- {% endsection %} --}}
		</div>
    </div> <!-- /container -->
  </body>
</html>

<?xml version="1.0"?>

@if (isset($content))
	{!!$content!!}
@else
	@yield('content')
@endif

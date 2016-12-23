{{-- {% block notice_area %} --}}
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
{{-- {% endblock %} --}}

{{-- {% block content %} --}}
@if (!isset($error) || (isset($error) && !$error))
	@include(is_dir(resource_path('views/vendor/phpreports')) ? 'vendor.phpreports.default.html.report_content' : 'phpreports::default.html.report_content')
@endif
{{-- {% endblock %} --}}

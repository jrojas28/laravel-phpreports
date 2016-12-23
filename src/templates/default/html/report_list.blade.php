@extends(is_dir(resource_path('views/vendor/phpreports')) ? 'vendor.phpreports.default.html.page' : 'phpreports::default.html.page')

@section('title')
	Report List
@endsection

{{-- {!! $breadcrumb =  ["Report List" => true] !!} --}}

@section('sub-css')
	<link rel="stylesheet" href="{{ $base or '' }}{{ $asset_base or '' }}css/report_list.css" />
@endsection

@section('header')
	<h1 class="visible-phone">All Reports</h1>
@endsection

@section('sub-content')
	<div class="row">
		<div id="report_list" class="col-xs-12">
			@if (count($report_errors) != 0)
				<div class="well well-small">
					<div>
						<img src="{{ $base or '' }}{{ $asset_base or '' }}images/errorIcon.gif" />
						{{ count($report_errors) }} reports contain errors
						<button type="button" class="btn btn-link" data-toggle="collapse" data-target="#report_errors_holder">See all errors</button>
					</div>
					<div class="collapse" id="report_errors_holder">
						<div class="alert alert-danger alert-block">
							@foreach ($report_errors as $error)
								<div>
									<strong>{{ $error['report'] }}</strong> - {{ $error['exception']->getMessage() }}
									<span style="padding-left: 20px;">
										<a href="{{$base}}/report/raw/?report={{$error['report']}}" style="margin-right: 10px;" target="_blank">view source</a>
										<a href="{{$base}}/edit/?report={{$error['report']}}" target="_blank">edit</a>
									</span>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			@endif

			{{-- <a name="reportlist"></a> --}}
			@foreach ($reports as $item)
				<div class="report_list">
					@if (!$loop->first)
						<a href="#" class="pull-right" style="font-size: 12px; font-weight:normal;">top</a>
					@endif
					@include(is_dir(resource_path('views/vendor/phpreports')) ? 'vendor.phpreports.default.html.report_list_item' : 'phpreports::default.html.report_list_item', ['item' => $item])
				</div>
			@endforeach
		</div>
	</div>
@endsection

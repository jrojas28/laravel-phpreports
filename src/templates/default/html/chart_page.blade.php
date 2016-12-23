@extends(is_dir(resource_path('views/vendor/phpreports')) ? 'vendor.phpreports.default.html.blank_page' : 'phpreports::default.html.blank_page')

@section('sub-js')
	{{-- block javascripts --}}
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	    <script src="{{ $base or '' }}{{ $asset_base or '' }}js/jquery-2.0.3.min.js"></script>
		<script>
		google.charts.load("current", {packages:["corechart","geochart","annotatedtimeline","gauge","gantt"]});
		</script>
	    <script src="{{ $base or '' }}{{ $asset_base or '' }}js/timeline.js"></script>
	    <link rel="stylesheet" type="text/css" href="{{ $base or '' }}{{ $asset_base or '' }}css/timeline.css">
	    <style>
		#content > div > table {
		    margin-left: auto !important;
		    margin-right: auto !important;
		}
	    </style>
	{{-- endblock --}}
@endsection

@section('sub-css')
	{{-- block stylesheets --}}
	<style>
	body {
	    padding: 0;
	    margin: 0;
	}
	</style>
	{{-- endblock --}}
@endsection

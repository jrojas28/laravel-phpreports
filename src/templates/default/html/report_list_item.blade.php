@if ($item['is_dir'])
	@if (!isset($h))
		<?php $h = 2; ?>
	@endif
	@if ($item['children'])
		<a name="report_{{$item['Id']}}" href='#'>.</a>
		<h{{$h < 5 ? $h : 5}} class="@if(!isset($item['Title'])) no_title @endif">
			{{ $item['Title'] or $item['Name']}}
			<button type='button' class='btn' data-toggle="collapse" data-target="#report_{{$item['Id']}}_children"><span class='caret'></span></button>
		</h{{$h < 5 ? $h : 5}}>
		<div class='collapse @if(isset($item['Title'])) in @endif' id='report_{{$item['Id']}}_children'>
			@if (isset($item['Description']))
				<p>{!! $item['Description'] !!}</p>
			@endif

			<ul class='nav nav-list well'>
				@foreach ($item['children'] as $item)
					<li>
						<?php $h = $h + 1; ?>
							@include(is_dir(resource_path('views/vendor/phpreports')) ? 'vendor.phpreports.default.html.report_list_item' : 'phpreports::default.html.report_list_item', ['item' => $item])
						<?php $h = $h + 2; ?>
					</li>
				@endforeach
			</ul>
		</div>
	@endif
@elseif (!isset($item['ignore']))
	<a class='pull-right' href='{!! str_replace("report/html/?", "report/csv/?", $item['url']) !!}' style='margin-left: 5px; opacity:.6;'><img alt='Export CSV File' title='Export CSV File' src='{{ $base or '' }}{{ $asset_base or '' }}images/document-excel-csv.png' /></a>
	<a href='{{$item['url']}}' id='report_{{$item['Id']}}'>
		{{$item['Name']}}
		@if (isset($item['stop']))
			<img alt='Stop!' title='Stop!' src='{{ $base or '' }}{{ $asset_base or '' }}images/traffic-light-red.png' />
		@endif
		@if (isset($item['Caution']) || isset($item['Warning']))
			<img alt='Caution!' title='Caution!' src='{{ $base or '' }}{{ $asset_base or '' }}images/prohibition.png' />
		@endif
		@if (isset($item['Variables']))
			<img alt='Configurable' title='Configurable' src='{{ $base or '' }}{{ $asset_base or '' }}images/wrench.png' />
		@endif
		@if (isset($item['Charts']))
			<img alt='Contains graphs/charts' title='Contains graphs/charts' src='{{ $base or '' }}{{ $asset_base or '' }}images/chart.png' />
		@endif
		@if (isset($item['Detail']))
			<img alt='Contains drill down links' title='Contains drill down links' src='{{ $base or '' }}{{ $asset_base or '' }}images/drill.png' />
		@endif
		@if (isset($item['expensive']))
			<img alt='Expensive to run' title='Expensive to run' src='{{ $base or '' }}{{ $asset_base or '' }}images/money_dollar.png' />
		@endif
		{{-- {% if item.Created and item.Created|slice(0,10)|date('U') > date("-2 weeks")|date('U') %}
			<img alt='Created on {{$item['Created']|slice(0,10)|date('Y-m-d')}}' title='Created on {{$item['Created']|slice(0,10)|date('Y-m-d')}}' src='{{ $base or '' }}{{ $asset_base or '' }}images/new_icon.gif' />
		{% endif %} --}}
	</a>
@endif

@extends(is_dir(resource_path('views/vendor/phpreports')) ? 'vendor.phpreports.default.html.chart_page' : 'phpreports::default.html.chart_page')


@section('sub-content')
	{{-- block content --}}
	@foreach($Charts as $chart)
	{{-- for chart in Charts --}}
		<div id='chart_div_{{ $chart['num'] }}' style='@if(isset($chart['width'])) width:{{ $chart['width'] }}; @endif @if(isset($chart['height'])) height:{{ $chart['height']}};@endif'></div>
	{{-- endfor --}}
	@endforeach


	<script type="text/javascript">
	function drawCharts() {
		@foreach($Charts as $chart)
			var data_{{ $chart['num'] }} = new google.visualization.DataTable();

			@foreach($chart['Rows'][0]['values'] as $key => $value)
                @if (isset($chart['roles'][$key]))
                    data_{{$chart['num']}}.addColumn({!! json_encode($chart['roles'][$key]) !!});
                @else
                    data_{{$chart['num']}}.addColumn("{{ $chart['datatypes'][$key] }}","{{ $chart['Rows'][0]['values'][$key]->key }}");
                @endif
			@endforeach

			var rows = [
				@foreach($chart['Rows'] as $row)[
					@foreach($row['values'] as $value)
						@if ($value->datatype == "date" || $value->datatype == "datetime")
							new Date("{!! str_replace('\\', '\\\\', str_replace('"', '\\"', $value->getValue() ) ) !!}")
						@elseif($value->datatype == "timeofday")
							[{{ array_slice($value->getValue(), 0, 2)*1 }}, {{ array_slice($value->getValue(), 3, 2)*1 }}, {{ array_slice($value->getValue(), 6, 2)*1 }}, 0]
						@elseif($value->datatype == "null")
							null
						@elseif($value->datatype == "number")
						{{ $value->getValue() }}
						@else
						"{!! str_replace('\\', '\\\\', str_replace('"', '\\"', $value->getValue() ) ) !!}"
						@endif

						@if(!$loop->last)
							,
						@endif
					@endforeach

				]
				@if(!$loop->last)
					,
				@endif
			@endforeach];


			data_{{$chart['num']}}.addRows(rows);

            // create columns array
            var columns_{{$chart['num']}} = [];
            var series_{{$chart['num']}} = {};
            for (var i = 0; i < data_{{$chart['num']}}.getNumberOfColumns(); i++) {
                columns_{{$chart['num']}}.push(i);
                if (i > 0) {
                    series_{{$chart['num']}}[i - 1] = {};
                }
            }

			console.log(data_{{$chart['num']}});

            var options_{{$chart['num']}} = {
				title: '{{$chart['title']}}',
				@if(isset($chart['markers']))
					displayMode: 'markers',
					colorAxis: {colors: ['blue', 'red']},
					sizeAxis: {minValue: 1,  maxSize:  10},
				@endif
				displayAnnotations: true,
                series: series_{{$chart['num']}},
				colors: [],
				wmode: 'transparent'
			};

      @if(isset($chart['options']))
				@foreach($chart['options'] as $k => $v)
	          options_{{$chart['num']}}["{{ $k }}"] = {!! json_encode($v) !!};
	      @endforeach
      @endif

			@foreach($chart['colors'] as $color)
				options_{{$chart['num']}}.colors.push('{{$color}}');
			@endforeach


			if(!options_{{$chart['num']}}.colors.length) {
				delete options_{{$chart['num']}}.colors;
			}

        @if($chart['type'] == "Timeline")
            var chart_{{$chart['num']}} = new links.Timeline(document.getElementById('chart_div_{{$chart['num']}}'));
        @else
						var chart_{{$chart['num']}} = new google.visualization.{{$chart['type']}}(document.getElementById('chart_div_{{$chart['num']}}'));
        @endif

            google.visualization.events.addListener(chart_{{$chart['num']}}, 'select', function () {
                select2hide(chart_{{$chart['num']}}, data_{{$chart['num']}}, options_{{$chart['num']}}, columns_{{$chart['num']}}, series_{{$chart['num']}});
            });

        @if($chart['type'] == "BarChart")
            google.visualization.events.addListener(chart_{{$chart['num']}}, 'ready', function () {
                    $('#chart_div_{{$chart['num']}}').find('text[text-anchor=end]').each(function () {
                        $(this).attr('x', 25);
                        $(this).attr('text-anchor', 'front');
                    });
            });
        @endif
            chart_{{$chart['num']}}.draw(data_{{$chart['num']}}, options_{{$chart['num']}});
		@endforeach
	}

    function select2hide (chart, data, options, columns, series) {
        var sel = chart.getSelection();
        // if selection length is 0, we deselected an element
        if (sel.length > 0) {
            // if row is undefined, we clicked on the legend
            if (sel[0].row == null) {
                var col = sel[0].column;
                if (columns[col] == col) {
                    // hide the data series
                    columns[col] = {
                        label: data.getColumnLabel(col),
                        type: data.getColumnType(col),
                        calc: function () {
                            return null;
                        }
                    };

                    // grey out the legend entry
                    series[col - 1].color = '#CCCCCC';
                } else {
                    // show the data series
                    columns[col] = col;
                    series[col - 1].color = null;
                }
                var view = new google.visualization.DataView(data);
                view.setColumns(columns);
                chart.draw(view, options);
            }
        }
    }

	google.charts.setOnLoadCallback(drawCharts);
	</script>
@endsection

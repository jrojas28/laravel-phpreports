@if(is_dir(resource_path('views/vendor/phpreports')))
	@extends('vendor.phpreports.default.xml.page')
@else
	@extends('phpreports::default.xml.page')
@endif

@section('content')
	@if (isset($dataset_format) && $dataset_format)
		<Results>
			@foreach ($datasets as $dataset)
				<Dataset>
					@if (isset($DataSets[$dataset]['title']))
						<Title>{{ $DataSets[$dataset]['title'] }}</Title>
					@endif
					<Rows>
						@foreach ($DataSets[$dataset]['rows'] as $row)
							<Row>
							@foreach ($row['values'] as $value)
								{{ var_dump($value)}}
								<{{$value->getKeyCollapsed()}}>{!!$value->getValue()!!}</{{$value->getKeyCollapsed()}}>
							@endforeach
							</Row>
						@endforeach
					</Rows>
				</Dataset>
			@endforeach
		</Results>
	@else
		<Results>
			@foreach ($DataSets[$datasets[0]]['rows'] as $row)
				<Row>
					@foreach ($row['values'] as $value)
						<{{$value->getKeyCollapsed()}}>{!! $value->getValue() !!}</{{$value->getKeyCollapsed()}}>
					@endforeach
				</Row>
			@endforeach
		</Results>
	@endif
@endsection

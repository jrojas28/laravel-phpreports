@foreach ($DataSets as $dataset)
	@if (!$loop->first)

	@endif
	INSERT INTO `{{$Name}}_{{$loop->index}}`
		(@foreach ($dataset['rows'][0]['values'] as $value) @if (!$loop->first),@endif `{{$value->key}}` @endforeach)
	VALUES
	@foreach ($dataset['rows'] as $row)
		@if (!$loop->first),@endif
			(@foreach ($row['values'] as $value)@if (!$loop->first),@endif
			<?php $data = str_replace('"','\\"', str_replace('\\', '\\\\', $value->getValue()) );  ?>
			"{!! $data !!}"
			@endforeach)
	@endforeach
@endforeach

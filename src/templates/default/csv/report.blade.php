@foreach ($DataSets[$dataset]['rows'][0]['values'] as $value)@if (!$loop->first),@endif{{$value->key}}@endforeach

@foreach ($DataSets[$dataset]['rows'] as $row)
    @foreach ($row['values'] as $value)
        @if (!$loop->first),@endif<?php str_replace('"','""',$value->getValue()); ?>
    @endforeach
@endforeach

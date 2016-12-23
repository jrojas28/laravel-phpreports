@foreach ($DataSets as $dataset)
    @if (isset($dataset['title']))
        <h3>{{$dataset['title']}}</h3>
    @endif
    @if (count($DataSets) > 1)
        {{-- {% block table_download_link %} --}}
            <?php $dataset_id = $loop->index - 1 ?>
            @if (count($config['report_formats']) > 1)
                <div class="btn-group">
					<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
						<i class='icon-download'></i> Download/show as <span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu">
                        @foreach ($config['report_formats'] as $format=>$value)
                            @if ($value == 'divider')
                                <li class="divider"></li>
                            @else
                                <li><a href='{{$base}}/report/{{$format}}/?dataset={{ $dataset_id }}&{{$report_querystring}}' class='report_link'>{{$value}}</a></li>
                            @endif
                        @endforeach
					</ul>
				</div>
            @else
                <a href='{{$base}}/report/{{$config['report_formats']['0']}}/?{{$report_querystring}}' class='report_link btn'>
					<i class='icon-download'></i> Download {{$config['report_formats'][$config['report_formats'][0]]}}
				</a>
            @endif
		{{-- {% endblock %} --}}
    @endif
    @if (!isset($Formatting[$loop->index-1]['nodata']) || (isset($Formatting[$loop->index-1]['nodata']) && !$Formatting[$loop->index-1]['nodata']))
        <table @if (!isset($inline_email))
            id='result_table_{{$loop->index}}' class='result_table table table-striped'
            @else
                border="1" cellspacing="0"
            @endif>
            @if(isset($dataset['vertical']) && $dataset['vertical'])
        		<thead>
        			<tr class='header'>
                        @foreach ($dataset['vertical'][0] as $value)
                            <th class="{{ $value['class'] }}">
                                @if ($loop->first)
                                    Key
                                @else
                                    Value {{ $loop->index -1 }}
                                @endif
            				</th>
                        @endforeach
        			</tr>
        		</thead>
                @if (isset($dataset['footer']))
                    <tfoot>
                        @foreach ($dataset['footer'] as $row)
                            <tr>
                                @foreach ($row['values'] as $value)
                                    <td>{!!$value->getValue(true)!!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tfoot>
                @endif
        		<tbody>
                    @foreach ($dataset['vertical'] as $row)
                        <tr>
                            @foreach ($row['values'] as $value)
                                @if (isset($value->is_header) && $value->is_header)
                                    <?php $tag = 'th'; ?>
                                @else
                                    <?php $tag = 'td'; ?>
                                @endif
                				<{{ $tag }} class="{{ $value->class }}">
                                    {!!$value->getValue(true)!!}
                				</{{ $tag }}>
                            @endforeach
            			</tr>
                    @endforeach
        		</tbody>
    		@else
    		<thead>
    			<tr class='header'>
                    @if (isset($dataset['selectable']) && (!isset($inline_email) || (isset($inline_email) && !$inline_email)))
                        <th> </th>
                    @endif
                    @if (isset($dataset['rows'][0]))
                        @foreach ($dataset['rows'][0]['values'] as $value)
                            <th class="{{$value->class}}">{{$value->key}}</th>
                        @endforeach
                    @endif
    			</tr>
    		</thead>
            @if (isset($dataset['footer']))
                <tfoot>
                    @foreach ($dataset['footer'] as $row)
                        <tr>
                            @foreach ($row['values'] as $value)
                                <td>{!!$value->getValue(true)!!}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tfoot>
            @endif
    		<tbody>
                @foreach ($dataset['rows'] as $row)
                    <tr>
                        @if (isset($dataset['selectable']) && (!isset($inline_email) || (isset($inline_email) && !$inline_email)))
                            <td style='padding: 0;'>
                                <div style='width:40px;'>
                                    <input type='checkbox' class='selectable' style=' vertical-align:middle;' />
                                    @if ( !$loop->last)
                                        <a href='#' class='filldown' title='Fill Down - copy this value to all rows below this' style='text-decoration:none;'>&darr;</a>
                                    @endif
                                </div>
                            </td>
                        @endif
                        @foreach ($row['values'] as $value)
                            <td class="{{$value->class}}
                            @if (isset($dataset['selectable']) && $value->key == $dataset['selectable'])
                                selectable
                            @endif">
                            {!!$value->getValue(true)!!}
                            </td>
                        @endforeach
        			</tr>
                @endforeach
    		</tbody>
            @endif
    	</table>
    @endif
@endforeach

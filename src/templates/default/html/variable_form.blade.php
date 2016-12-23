<form class="form-horizontal well well-sm" role="form" method="get" id="variable_form">
	<input type='hidden' name='report' value='{{$report}}' />
    <div class="row">
        <div class="col-sm-12" id="variables-header">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#variable_form" href="#variables-fieldset">
            <span class="glyphicon glyphicon-chevron-down"></span> <strong>@yield('variable_form_title', 'Report configuration')</strong></a>
			<hr>
            <span id="variables-holder"></span>
        </div>
    </div>
    <div class="row">
        <fieldset id="variables-fieldset" class="collapse col-xs-12 @if(isset($collapse_configuration) && !$collapse_configuration) in @endif">
			@foreach ($vars as $var)
				<div class='form-group variable'>
					{{-- {% block variable_form_row_label %} --}}
					<label for='var_{{$var['key']}}' class='col-sm-2 control-label'>{{$var['display']}}</label>
					{{-- {% endblock %} --}}
					<div class='col-sm-10 controls'>
						{{-- {% block variable_form_row_modifier_input %} --}}
						@if (isset($var['modifier_options']))
							<select name='macros[{{$var['key']}}_modifier]' id='var_{{$var['key']}}_modifier' class='input'>
								@foreach ($var['modifier_options'] as $option)
									<option value="{{$option['value']}}" @if ($option['selected'])
										selected="selected"
									@endif>{{$option['display']}}</option>
								@endforeach
							</select>
						@endif
						{{-- {% endblock %} --}}
						{{-- {% block variable_form_row_input %} --}}
						@if (isset($var['is_select']) && $var['is_select'])
							<select
								class="form-control"
								name='macros[{{$var['key']}}]@if (isset($var['is_multiselect'])) [] @endif'
								id='var_{{$var['key']}}'
								@if (isset($var['is_multiselect']))
									multiple=true size={{$var['choices']}}
								@endif>
								@foreach ($var['options'] as $option)
									<option value="{{$option['value']}}" @if ($option['selected']) selected="selected" @endif>{{$option['display']}}</option>
								@endforeach
							</select>
						@elseif (isset($var['is_textarea']) && $var['is_textarea'])
							<textarea name='macros[{{$var['key']}}]' id='var_{{$var['key']}}' style='vertical-align:top; width: 500px; height: 80px;'>{!! join("\n", $var['value']) !!}</textarea>
						@elseif ($var['type'] === "daterange")
							<input type='hidden' name='macros[{{$var['key']}}][start]' id='var_{{$var['key']}}_start' value='{{\Carbon\Carbon::instance($var['value']['start'])->toDateTimeString()}}' />
							<input type='hidden' name='macros[{{$var['key']}}][end]' id='var_{{$var['key']}}_end' value='{{\Carbon\Carbon::instance($var['value']['end'])->toDateTimeString()}}' />
							<div class="daterangepicker_holder btn btn-default pull-left" data-var="{{$var['key']}}">
									<i class="glyphicon glyphicon-calendar"></i>
									<span>
										@if (isset($var['value']['start']) && isset($var['value']['end']))
											{{ \Carbon\Carbon::instance($var['value']['start'])->format('F j, Y') }} - {{ \Carbon\Carbon::instance($var['value']['end'])->format('F j, Y') }}
										@else
											<em>no date range selected</em>
										@endif
									</span> <b class="caret"></b>
							</div>
						@elseif ($var['type'] === "date")
							<div class="input-append date datepicker_holder" data-date="{{\Carbon\Carbon::instance($var['value'])->format('Y-m-d')}}" data-date-format="yyyy-mm-dd">
								<input type='text' name='macros[{{$var['key']}}]' id="var_{{ $var['key'] }}" value='{{\Carbon\Carbon::instance($var['value'])->format('Y-m-d H:i:s')}}' />
								<i class="add-on glyphicon glyphicon-calendar"></i>
							</div>
						@else
							<input type='text' name='macros[{{$var['key']}}]' id="var_{{ $var['key'] }}" value='{{$var['value']}}' />
						@endif

						{{-- {% block variable_form_row_description %} --}}
						@if (isset($var['description']))
							<span class='help-inline'>{{ $var['description']|raw }}</span>
						@endif
						{{-- {% endblock %} --}}
					</div>
				</div>
			@endforeach
            <div class="form-group">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block">Run Report</button>
                </div>
            </div>
        </fieldset>
    </div>
</form>
<script>
$(function() {
    $('#variables-fieldset').on('hide.bs.collapse', function () {
        var t = new Array();
        $('#variables-fieldset .variable').each(function() {
						var inputs = $(this).find('input,select,textarea');
						var label = $(this).find('label.control-label').text();
						var values = [];
						inputs.each(function() {
							if(($(this).attr('name')||'').match(/^macros/)) {
							    // For dropdowns, use the display name instead of the value
							    if($('option:selected',$(this)).length) {
								var t = [];
								$('option:selected',$(this)).each(function() {
								    t.push($(this).text());
								});
								values.push(t.join(' | '));
							    }
							    // Otherwise, show the value
							    else {
								values.push($(this).val());
							    }
							}
						});
						if(values.length) {
							t.push('<strong>'+label+'</strong>: <em>'+values.join(' - ')+'</em>');
						}
				});
        var text = ' : '+t.join(', ');
        $('#variables-holder').html(text);
        $('#variables-header span.glyphicon').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-right');

    }).on('show.bs.collapse', function() {
        $('#variables-selected').collapse('hide');
        $('#variables-holder').text('');
        $('#variables-header span.glyphicon').removeClass('glyphicon-chevron-right').addClass('glyphicon-chevron-down');
    })
		@if (isset($collapse_configuration) && $collapse_configuration)
		.trigger('hide.bs.collapse')
		@endif

	$('.daterangepicker_holder',$('#variable_form')).each(function() {
        moment.lang(window.navigator.language);

		var range_holder = $(this);
		range_holder.daterangepicker(
			{
				ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract('days', 29), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
				},
                showWeekNumbers: true,
                showDropdowns: true,
                locale: {
                    firstDay: moment.langData()._week.dow
                }
			},
			function(start, end) {
				$('#var_'+range_holder.data('var')+'_start').val(start.format('YYYY-MM-DD')+" 00:00:00");
				$('#var_'+range_holder.data('var')+'_end').val(end.format('YYYY-MM-DD')+" 23:59:59");

				$('span',range_holder).html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			}
		);
	});
	$('.datepicker_holder',$('#variable_form')).each(function() {
		$(this).datepicker();
	});

    $('#variable_form select').multiselect({
        buttonClass: 'btn btn-default btn-block',
        buttonWidth: '100%',
		buttonContainer: '<div class="col-xs-12"/>',
        includeSelectAllOption: true,
        maxHeight: '300px',
        buttonText: function(options, select) {
            if (options.length == 0) {
                return 'None selected <b class="caret"></b>';
            } else if (options.length > 8) {
                return options.length + ' selected  <b class="caret"></b>';
            } else {
                var selected = '';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2) + ' <b class="caret"></b>';
            }
        }
    });

});
</script>

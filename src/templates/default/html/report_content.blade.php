{{-- {% block chart_area %} --}}
    @if ((isset($has_charts) && $has_charts) && ((isset($no_charts) && !$no_charts)  || !isset($no_charts)))
        <iframe src="{{$base}}/report/chart/?{{$report_querystring}}" id='chart_container' class="auto-height" scrolling="no" style='width:100%;' frameborder="0"></iframe>
        <script>
        $(function() {
            $('iframe#chart_container').iframeAutoHeight();
        });
        </script>
    @endif
{{-- {% endblock %} --}}
@include(is_dir(resource_path('views/vendor/phpreports')) ? 'vendor.phpreports.default.html.table' : 'phpreports::default.html.table')

@if ((isset($nodata) && !$nodata) || !isset($nodata))
    <script type='text/javascript'>
    $(function() {
        @foreach ($DataSets as $dataset)
        @if (!isset($dataset['vertical']) || (isset($dataset['vertical']) && !$dataset['vertical']))
            $('#result_table_{{$loop->index}}').DataTable({
                bPaginate: false,
                aaSorting: [],
                "sDom": "<'row'<'col-md-6'fi>r>t"
            });
            $('#result_table_{{$loop->index}}').stickyTableHeaders({
                fixedOffset: $('.navbar-fixed-top')
            });
        @endif
        @if (isset($dataset['selected']) && $dataset['selected'])
        $('#result_table_{{$loop->index}}').on('change','input.selectable',function() {
            //determine selected inputs
            var selected_inputs = $("input.selectable:checked",$(this).closest('table'));

            var selected = [];

            selected_inputs.each(function() {
                var id = $.trim($(this).closest('tr').find('td.selectable').text());

                selected.push(id);
            });

            $(".report_link").each(function() {
                var url = $(this).attr('href');
                var query_string = {};

                if(url.indexOf('?') != -1) {
                    query_string = $.queryStringToJSON(decodeURIComponent($(this).attr('href').split('?')[1]).replace(/\+/g,' '));
                    url = url.split('?')[0];
                }

                if(selected.length)
                    query_string.selected_{{$loop->index-1}} = selected;
                else
                    delete query_string.selected_{{$loop->index-1}};

                $(this).attr('href',url+'?'+$.param(query_string));
            });
        })
        .on('click','.filldown',function() {
            var input = $(this).closest('td').find('input.selectable');

            $(this).closest('tr').nextAll('tr').find('input.selectable').prop('checked',input.prop('checked'));
            input.trigger('change');
            return false;
        });
        @endif
        @endforeach
    });
    </script>
@endif

<div class="row">
    <div class="col-md-12">Query took {{$Time}} seconds</div>
</div>

{{-- {% block show_query %} --}}
    @if (isset($Query_Formatted))
        <a data-role="button" data-toggle="collapse" data-target="#query_holder" href="#query_holder">show query</a>
		<div id='query_holder' class='collapse' style='padding-left: 20px;'>
			{!!$Query_Formatted!!}
		</div>
		<script>
		$(function() {
			$('.included_report').each(function() {
				var self = $(this);
				self.css('display','none');

				var name = self.data('name');
				if(name == "Variables") {
					var linktext = "Included Variables";
				}
				else {
					var linktext = "Included Report - "+name;
				}

				var link = $('<a>').attr('href','#').text(linktext).css('display','block').click(function() {
					self.toggle(200);
					return false;
				});

				self.before(link);
			});
		});
		</script>
    @endif
{{-- {% endblock %} --}}

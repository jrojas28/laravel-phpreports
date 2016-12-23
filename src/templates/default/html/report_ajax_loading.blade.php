<div class='alert alert-block alert-info' id='async_notice' style='text-align: center;'>
	<div>
		Your report is running.
		@if (isset($time_estimate) && $time_estimate)
			@if ($time_estimate['median'])
				This report usually takes {{$time_estimate['median']}} seconds.
			@endif
		@endif
	</div>
	<div class="progress progress-striped active" style="width: 50%; margin: 20px auto 0;">
        <div class="progress-bar"  role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
            <span class="sr-only">Working</span>
        </div>
	</div>
</div>
<script>
$(function() {
	$.ajax({
		'url': '{!!$report_url!!}',
		'dataType': 'html',
		'data': {'content_only': true},
		'success': function(data) {
			$('#report_content').html(data);
			prettyPrint();
		}
	});
});
</script>

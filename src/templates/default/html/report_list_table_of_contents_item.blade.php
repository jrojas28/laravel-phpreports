@if ($item['is_dir'] && $item['Title'])
	<li>
		<a href='#report_{{$item['Id']}}'>{{$item['Title']}}</a>
		@if ($item['children'])
			<ul class="nav nav-list">
				@foreach ($item['children'] as $item)
					@include(is_dir(resource_path('views/vendor/phpreports')) ? 'vendor.phpreports.default.html.report_list_table_of_contents_item' : 'phpreports::default.html.report_list_table_of_contents_item', ['item' => $item])
				@endforeach
			</ul>
		@endif
	</li>
@endif

<ol class="breadcrumb">
	@if (isset($breadcrumb) && sizeof($breadcrumb))
		<li><a href="{{ url('/backend') }}">หน้าหลัก</a></li>
		@foreach ($breadcrumb as $display => $link)
			@if (is_null($link))
				<li class="active">{{ str_replace('_', ' ', $display) }}</li>
			@else
				<li><a href="{{ url($link) }}">{{ str_replace('_', ' ', $display) }}</a></li>
			@endif
		@endforeach
	@else
		<li class="active">หน้าหลัก</li>
	@endif
</ol>
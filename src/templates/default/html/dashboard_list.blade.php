@extends(is_dir(resource_path('views/vendor/phpreports')) ? 'vendor.phpreports.default.html.page' : 'phpreports::default.html.page')

@section('title')
  {{--block title --}}Dashboard List{{-- endblock --}}
  {{ $breadcrumb["Dashboard List"] = true }}
@endsection


@section('sub-content')
  {{-- block header --}}
  <h2>Dashboards</h2>
  {{-- endblock --}}

  {{-- block content --}}
  <div class="row">
  <ul>
    @foreach($dashboards as $key => $dashboard)
      <li><a href='{{$base}}/dashboard/{{$key}}'>{{isset($dashboard->title)? $dashboard->title : 'TITLE'}}</a></li>
    @endforeach
  </ul>
  </div>
  {{-- endblock --}}
@endsection

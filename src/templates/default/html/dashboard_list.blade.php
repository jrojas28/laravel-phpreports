@extends('html/page.blade.php')

{{--block title --}}Dashboard List{{-- endblock --}}

{{ $breadcrumb = {"Dashboard List": true} }}

{{-- block header --}}
<h2>Dashboards</h2>
{{-- endblock --}}

{{-- block content --}}
<div class="row">
<ul>
  @foreach($key, $dashboard in $dashboards)
    <li><a href='{{$base}}/dashboard/{{$key}}'>{{isset($dashboard->title)? $dashboard->title : 'TITLE'}}</a></li>
  @endforeach
</ul>
</div>
{{-- endblock --}}

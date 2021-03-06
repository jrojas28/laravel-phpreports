@extends(is_dir(resource_path('views/vendor/phpreports')) ? 'vendor.phpreports.default.html.page' : 'phpreports::default.html.page')

@section('title')
  {{-- block title --}}{!! isset($dashboard['title'])? $dashboard['title'] : 'Dashboard' !!}{{-- endblock --}}
@endsection

@section('sub-content')
  {{-- block header --}}
  <h2>{{ $dashboard['title'] }}</h2>
  @if( $dashboard['description'])
    <p>{!! isset($dashboard['description']) ? $dashboard['description'] : '' !!}</p>
  @endif
  {{-- endblock --}}

  {{-- block content --}}
  <div id='reports_holder'>

  </div>
  {{-- endblock --}}
@endsection

@section('sub-css')
  {{-- block stylesheets --}}
  <style>
    #content {
      width: auto;
    }
    #reports_holder {
      margin-top: 20px;
    }
    .rendered {
      font-size: .7em;
      padding-left: 8px;
    }
    h2.report-title {
      margin: 0;
      padding: 0;
    }
    </style>

    @if (isset($dashboard['style']))
      <style type="text/css">
        {!! $dashboard['style'] !!}
      </style>
    @endif

  @endsection
{{-- endblock --}}

{{-- javascripts --}}
@section('sub-js')
  <script type='text/javascript' src='{{$base or ''}}{{$asset_base or ''}}js/jquery.dataTables.min.js'></script>
  <script type="text/javascript" src="{{$base or ''}}{{$asset_base or ''}}js/moment-with-langs-2.5.1.min.js"></script>
  <script type="text/javascript" src="{{$base or ''}}{{$asset_base or ''}}js/daterangepicker-1.3.2.js"></script>
  <script type="text/javascript" src="{{$base or ''}}{{$asset_base or ''}}js/bootstrap-datepicker.js"></script>
  <script type="text/javascript" src="{{$base or ''}}{{$asset_base or ''}}js/bootstrap-multiselect.js"></script>
  <script type="text/javascript" src="{{$base or ''}}{{$asset_base or ''}}js/prettify.js"></script>
  <script type="text/javascript" src="{{$base or ''}}{{$asset_base or ''}}js/lang-sql.js"></script>
  <script type="text/javascript" src="{{$base or ''}}{{$asset_base or ''}}js/jquery.browser.js"></script>
  <script type="text/javascript" src="{{$base or ''}}{{$asset_base or ''}}js/jquery.iframe-auto-height.plugin.1.9.3.js"></script>
  <script type="text/javascript">
      $("#content").on('click','a[data-role="button"]',function(e) {
          e.preventDefault();
      });
  </script>
  <script>
  var dashboard = {!! json_encode($dashboard) !!};

  var last_row = $("<div class='row'>").appendTo($("#reports_holder"));

  if(dashboard.refresh) {
    window.setTimeout(function() {
      window.location.reload(true);
    },dashboard.refresh*1000);
  }

  $.each(dashboard.reports, function(i,report) {
    if(i && report.newRow) {
      last_row = $("<div class='row'>").appendTo($("#reports_holder"));
    }

    var container = $("<div class='report-holder'>").appendTo(last_row);

    if(report.class) container.addClass(report.class);

    var report_data = {
      macros: report.macros || {},
      report: report.report
    };


    if(report.title) {
      container.append($("<h2 class='report-title'>").text(report.title));
    }
    container.append("<div class='info'><a class='full-link' href='{{$base or ''}}/report/html/?"+$.param(report_data)+"'>View full report</a><span class='rendered'></span></div>");

    if(report.description) {
      container.append($("<p class='description'>").text(report.description));
    }

    var holder;

    var render = function() {
      if(!report.format || report.format === 'html' || report.format === 'table') {
        var report_url = "{{$base or ''}}/report/html";

        // Loading message
        holder.html("<p>Loading...</p>");

        $.get(report_url,report_data,function(response) {
          holder.empty().html(response);
        });
      }
      else {
        holder.attr('src',"{{$base or ''}}/report/"+report.format+"?"+$.param(report_data));
      }
    };

    if(!report.format || report.format === 'html' || report.format === 'table') {
      holder = $("<div>").appendTo(container);
      report_data.content_only = true;
      report_data.no_charts = true;

      if(report.style) holder.attr('style',report.style);
    }
    else {
      holder = $("<iframe>").appendTo(container).css({
        border: 0,
        width: '100%'
      }).on('load',function() {
  	$(this).height($(this).get(0).contentWindow.document.body.scrollHeight);
  	$(this).width($(this).get(0).contentWindow.document.body.scrollWidth);
      });

      if(report.style) holder.attr('style',holder.attr('style')+';'+report.style);
    }


    var render_date = new Date();
    var rendered_span = $('.rendered',container);
    if(report.refresh) {
      window.setInterval(function() {
        render();
        render_date = new Date();
        rendered_span.attr('title','refreshed at '+render_date.toTimeString().substr(0,8)).text('last refreshed 0 seconds ago');
      },report.refresh*1000);

      var refreshRenderDate = function() {
        var date = new Date();
        var diff = Math.floor((date.getTime() - render_date.getTime())/1000);
        console.log(diff);

        if(diff < 60) {
          rendered_span.text('last refreshed '+diff+' seconds ago');
          window.setTimeout(refreshRenderDate,5000);
        }
        else if(diff < 300) {
          rendered_span.text('last refreshed '+Math.floor(diff/60)+' minute'+(diff>=120?'s':'')+((diff%60)?' '+diff%60+' second'+((diff%60 !== 1)?'s':'') : '')+' ago');
          window.setTimeout(refreshRenderDate,20000);
        }
        else if(diff < 3600) {
          rendered_span.text('last refreshed '+Math.floor(diff/60)+' minutes ago');
          window.setTimeout(refreshRenderDate,60000);
        }
        else if(diff < 84600){
          rendered_span.text('last refreshed '+Math.floor(diff/3600)+' hour'+(diff>=7200?'s':'')+' ago');
          window.setTimeout(refreshRenderDate,600000);
        }
        else {
          rendered_span.text('last refreshed more than 1 day ago');
        }
      };
      window.setTimeout(refreshRenderDate,5000);
    }
    render();


  });


  </script>
  {{-- endblock --}}
@endsection

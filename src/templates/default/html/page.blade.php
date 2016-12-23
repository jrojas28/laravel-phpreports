@extends('layouts.app')
@section('css')
    <link href="{{ $base or '' }}{{ $asset_base or '' }}css/typeahead.js-bootstrap.css" rel="stylesheet"/>
    @yield('sub-css')
    <script>
		//this allows us to create onReadyness functions in the body before loading jquery
		window.queued_jquery_calls = [];
		window.$ = function(func) {
			window.queued_jquery_calls.push(func);
		};
	</script>
@endsection
@section('content')
    <div class="container">
        <div class="panel panel-default">
            {{-- NOTICE AREA --}}
            <div class="panel-heading">
                @yield('title',"Reports Module")
            </div>
            <div class="panel-body">
                @if (isset($error))
                    <div class="alert alert-danger">
                        @if (isset($error['exception']))
                            {{$error['exception']->getMessage()}}
                        @else
                            {{$error}}
                        @endif
                    </div>
                @endif
                @if (isset($notice))
                    <div class="alert alert-info">
                        {{$notice}}
                    </div>
                @endif
                {{-- // content: --}}
                @yield('header')
                <hr>
                @if (!isset($error))
                    @yield('sub-content')
                @endif
            </div>
        </div>
    </div> <!-- /container -->
@endsection
@section('js')
    <!--[if lt IE 9]>
    <script src="{{ $base or '' }}{{ $asset_base or '' }}js/jquery-1.10.2.min.js"></script>
    <![endif]-->
    <!--[if (gte IE 9) | (!IE)]><!-->
    {{-- <script src="{{ $base or '' }}{{ $asset_base or '' }}js/jquery-2.0.3.min.js"></script> --}}
    <!--<![endif]-->
    {{-- <script src="{{ $base or '' }}{{ $asset_base or '' }}js/bootstrap-3.0.min.js"></script> --}}
    <script src="{{ $base or '' }}{{ $asset_base or '' }}js/jquery.cookie.js"></script>
    <script src="{{ $base or '' }}{{ $asset_base or '' }}js/typeahead.min.js"></script>
    <script src="{{ $base or '' }}{{ $asset_base or '' }}js/scripts.js"></script>
    <script>
        //typeahead report search
        (function() {
            var data = new Dataset({
                prefetch: {
                    url: '{{ $base or '' }}/report-list-json',
                    ttl: 0
                },
                valueKey: 'name',
                sorter: function(a,b) {
                    var val = $('form[role="search"] input.search-query').typeahead('val')[0];

                    //beginning of title match
                    var beg = new RegExp('^'+val,'i');
                    //word boundary match
                    var word = new RegExp('\b'+val,'i');

                    //weights for components of the sort algorithm
                    var popweight = 2;
                    var wordweight = 10;
                    var begweight = 15;

                    //popularity
                    var popa = a.popularity;
                    var popb = b.popularity;

                    //beginning of string match
                    var bega = beg.test(a.name);
                    var begb = beg.test(b.name);

                    //beginning of word match
                    var worda = !bega && word.test(a.name);
                    var wordb = !begb && word.test(b.name);

                    //determine score
                    var scorea = popa*popweight + bega*begweight + worda*wordweight;
                    var scoreb = popb*popweight + begb*begweight + wordb*wordweight;

                    return scoreb - scorea;
                }
            });

            $('form[role="search"] input.search-query').typeahead({
                sections: [{
                    source: data,
                    highlight: true
                }]
            }).on('typeahead:selected',function(e,obj) {
                window.location.href = obj.url;
            });
        })();
    </script>
    <script>
    //run any queued on-ready scripts
    for(var i in queued_jquery_calls) {
        $(queued_jquery_calls[i]);
    }
    </script>

    @yield('sub-js')
@endsection

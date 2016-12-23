@extends(is_dir(resource_path('views/vendor/phpreports')) ? 'vendor.phpreports.default.html.page' : 'phpreports::default.html.page')

<?php $collapse_configuration = isset($is_ready) ? $is_ready : false ?>
@section('title')
    @if (isset($Name))
        {{ $Name }}
    @endif
@endsection
@section('sub-css')
    <link rel='stylesheet' href='{{ $base or '' }}{{ $asset_base or '' }}css/report.css' />
    @if (isset($nodata))
        <link rel='stylesheet' href='{{ $base or '' }}{{ $asset_base or '' }}css/jquery.dataTables.css' />
    @endif
    <link rel="stylesheet" type="text/css" href="{{ $base or '' }}{{ $asset_base or '' }}css/daterangepicker-bs3.css" />
    <link rel="stylesheet" media="screen" type="text/css" href="{{ $base or '' }}{{ $asset_base or '' }}css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="{{ $base or '' }}{{ $asset_base or '' }}css/bootstrap-multiselect.css" />
    <link rel="stylesheet" type="text/css" href="{{ $base or '' }}{{ $asset_base or '' }}css/prettify.css" />
    <style>
    /*.daterangepicker_holder {
        background: white;
        -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1);
        -moz-box-shadow: 0 1px 3px rgba(0,0,0,.25), inset 0 -1px 0 rgba(0,0,0,.1);
        box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1);
        color: #333;
        padding: 8px;
        line-height: 18px;
        cursor: pointer;
    }
    .daterangepicker_holder .caret {
        margin-top: 8px;
        margin-left: 2px;
    }*/
    </style>
@endsection
@section('sub-js')
    @if (isset($has_charts))
        <script type="text/javascript" src="{{ $base or '' }}{{ $asset_base or '' }}js/jquery.browser.js"></script>
        <script type="text/javascript" src="{{ $base or '' }}{{ $asset_base or '' }}js/jquery.iframe-auto-height.plugin.1.9.3.js"></script>
    @endif
    @if (!isset($nodata))
       {{-- //&& isset($load_datatables) && $load_datatables) --}}
        <script type='text/javascript' src='{{ $base or '' }}{{ $asset_base or '' }}js/jquery.dataTables.min.js'></script>
    @endif
    <script type="text/javascript" src="{{ $base or '' }}{{ $asset_base or '' }}js/moment-with-langs-2.5.1.min.js"></script>
    <script type="text/javascript" src="{{ $base or '' }}{{ $asset_base or '' }}js/daterangepicker-1.3.2.js"></script>
    <script type="text/javascript" src="{{ $base or '' }}{{ $asset_base or '' }}js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="{{ $base or '' }}{{ $asset_base or '' }}js/bootstrap-multiselect.js"></script>
    <script type="text/javascript" src="{{ $base or '' }}{{ $asset_base or '' }}js/jquery.stickytableheaders.js"></script>
    <script type="text/javascript" src="{{ $base or '' }}{{ $asset_base or '' }}js/prettify.js"></script>
    <script type="text/javascript" src="{{ $base or '' }}{{ $asset_base or '' }}js/lang-sql.js"></script>
    <script type="text/javascript">
        $("#content").on('click','a[data-role="button"]',function(e) {
            e.preventDefault();
        });
    </script>
@endsection

@section('header')
    @if (isset($Name))
        <div class="col-xs-12">
            <a href="/reports" class="btn m-custom-blue-grey pull-right"><i class='fa fa-reply'></i> Show all reports</a>
        </div>
        <h1>{{$Name}}</h1>
    @endif
    @if (isset($Description))
        <p id='report_description'>{!! $Description !!}</p>
    @endif
    @if (isset($Variables) && count($Variables) != 0)
			@include(is_dir(resource_path('views/vendor/phpreports')) ? 'vendor.phpreports.default.html.variable_form' : 'phpreports::default.html.variable_form')
    @endif
@endsection

@section('sub-content')
    @if (isset($is_ready) && $is_ready)
        <div class='row export_links' style='margin: 10px 0;'>
        {{-- {% block export_links %} --}}
            {{-- {% block download_link %} --}}
            @if (count($config['report_formats']) > 1)
                @if (isset($DataSets) && count($DataSets) > 1)
                    All Reports Tables:
                @endif
                <div class="btn-group">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class='icon-download'></i> Download/show as <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        @foreach ($config['report_formats'] as $format => $value)
                            {{-- {{ $format }} --}}
                            @if ($config['report_formats'][$format] == 'divider')
                                <li class="divider"></li>
                            @elseif ((isset($DataSets) && count($DataSets) <= 1) || $format != 'csv')
                                <li><a href='{{$base}}/report/{{$format}}/?@if ((isset($DataSets) && count($DataSets) > 1))datasets=all& @endif{{$report_querystring}}'
                                    class='report_link'>{{$config['report_formats'][$format]}}</a></li>
                            @endif
                        @endforeach
                        </ul>
                    </div>
            @else
                <a href='{{$base}}/report/{{$config['report_formats'][0]}}/?{{report_querystring}}' class='report_link btn'>
                    <i class='icon-download'></i> Download {{$config['report_formats'][$config['report_formats'][0]]}}
                </a>
            @endif
            {{-- {% endblock %} --}}
            @if (isset($config['mail_settings']['enabled']) && $config['mail_settings']['enabled'])
                {{-- {% block email_report_button %} --}}
                    <a data-toggle="modal" href="#email-modal" class="btn btn-primary btn-sm"><i class='icon-envelope'></i> Email Report</a>
                    <div class="modal fade" id="email-modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title">Email Report</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-danger" id="email-report-modal-alert" style="display: none;"></div>
                                    <p>A CSV file will be attached and a link will be sent</p>

                                    <form class="form-horizontal" role="form">
                                        <div class="form-group">
                                            <label class="col-lg-5" for='email-report-modal-email'>Recipient Email Address</label>
                                            <div class="col-lg-7">
                                                <input type='email' id='email-report-modal-email' class='form-control' value='' />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-5" for='email-report-modal-subject'>Subject</label>
                                            <div class="col-lg-7">
                                                <input type='text' id='email-report-modal-subject' class='form-control' value="Database Report - {{$Name}}" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-5" for='email-report-modal-message'>Message (optional)</label>
                                            <div class="col-lg-7">
                                                <textarea rows='4' id='email-report-modal-message' class='form-control'></textarea>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                                    <button type="submit" id="sendReport" class="btn btn-primary submit-button" data-loading-text="Sending">Send Email</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        $(function() {
                            $('#sendReport').on('click', function() {
                                var button = $(this);
                                button.button('loading');

                                var data = {
                                    email: $('#email-report-modal-email').val(),
                                    subject: $('#email-report-modal-subject').val(),
                                    message: $('#email-report-modal-message').val(),
                                    url: window.location.href
                                };

                                $.getJSON('{{$base}}/email',data,function(response) {
                                    if(response && response.success) {
                                        $('#email-modal').modal('hide');

                                        //show success message
                                        $('#email-report-success-message').show(300).delay(5000).hide(300);
                                    }
                                    else {
                                        var error = (response && response.error)? response.error : "There was a problem sending the email";
                                        $('#email-report-modal-alert').text(error).show(300);
                                    }
                                    button.button('reset');
                                })
                                .error(function() {
                                    $('#email-report-modal-alert').text("There was an error while sending the email").show(300);
                                });

                                return false;
                            });
                        });
                    </script>
                {{-- {% endblock %} --}}
            @endif
        {{-- {% endblock %} --}}
        </div>
        @if (isset($config['mail_settings']['enabled']) && $config['mail_settings']['enabled'])
            <div class='alert alert-success' style='display: none;' id='email-report-success-message'>
                {{-- {% block email_report_success_message %} --}}
                    Email Sent Successfully
                {{-- {% endblock %} --}}
            </div>
        @endif
    @endif

    <div id='report_content'>
        @if ((isset($is_ready) && !$is_ready) || !isset($is_ready))
            <div class='alert alert-info'>
                This report needs more information before running.
            </div>
        @elseif (isset($async) && $async)
					@include(is_dir(resource_path('views/vendor/phpreports')) ? 'vendor.phpreports.default.html.report_ajax_loading' : 'phpreports::default.html.report_ajax_loading')
        @else
					@include(is_dir(resource_path('views/vendor/phpreports')) ? 'vendor.phpreports.default.html.report_content' : 'phpreports::default.html.report_content')
        @endif
    </div>

    {{-- {% block time_estimate %} --}}
    @if (isset($time_estimate) && $time_estimate)
        <div style='margin-top: 20px;'>
            <a data-role="button" data-toggle="collapse" data-target="#time_estimate_holder" href="#time_estimate_holder">show time estimate data</a>
            <div style='font-size: .8em; padding-left: 20px;' id='time_estimate_holder' class='collapse'>
                <div><strong>Number of Samples: </strong> {{$time_estimate['count']}}</div>
                <div><strong>Minimum Time: </strong> {{$time_estimate['min']}}</div>
                <div><strong>Maximum Time: </strong> {{$time_estimate['max']}}</div>
                <div><strong>Median: </strong> {{$time_estimate['median']}}</div>
                <div><strong>Average: </strong> {{$time_estimate['average']}}</div>
                <div><strong>Standard Deviation: </strong> {{$time_estimate['stdev']}}</div>
            </div>
        </div>
    @endif
    {{-- {% endblock %} --}}
@endsection

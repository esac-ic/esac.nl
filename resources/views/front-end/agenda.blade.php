@extends('layouts.app')

@section('content')
    {{--In this div is the agenda mounted by vue--}}
    <div id="agenda">
    </div>

    <div id="ExportModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('front-end/agenda.export_agenda')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ trans('front-end/agenda.export_explanation') }}</p>
                    <table class="table table-striped">
                        <tr>
                            <td class="align-middle">Apple</td>
                            <td><a href="webcal://esac.nl/ical" class="btn btn-outline-primary btn-sm">Connect</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">Google Calendar</td>
                            <td><a href="https://calendar.google.com/calendar/r?cid=http://esac.nl/ical" class="btn btn-outline-primary btn-sm">Connect</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">Windows + Outlook</td>
                            <td><a href="webcal://esac.nl/ical" class="btn btn-outline-primary btn-sm">Connect</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">{{ trans('front-end/agenda.other') }} (iCal URL)</td>
                            <td>esac.nl/ical</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var APP_URL = "{{url('/')}}";
        var DESCRIPTION = "{!! $content !!}";
    </script>
    <script src="{{mix('js/agenda.js')}}"></script>
@endpush

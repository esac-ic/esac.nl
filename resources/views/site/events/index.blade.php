@extends('layouts.app')

@section('content')
{{-- Vue mounts the events list here --}}
<div id="app"><agenda description="{{ $content }}"></agenda></div>

<div id="export-calendar-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export calendar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>By clicking one of the links below it is possible to synchronize all ESAC events with your own calendar.</p>
                <table class="table table-striped">
                    <tr>
                        <td class="align-middle">Apple</td>
                        <td><a href="{{ str(route('events.feed'))->replaceMatches('#^https?://#', 'webcal://') }}" class="btn btn-outline-primary btn-sm">Connect</a></td>
                    </tr>
                    <tr>
                        <td class="align-middle">Google Calendar</td>
                        <td><a href="https://calendar.google.com/calendar/r?cid={{ urlencode(route('events.feed')) }}" class="btn btn-outline-primary btn-sm">Connect</a></td>
                    </tr>
                    <tr>
                        <td class="align-middle">Windows + Outlook</td>
                        <td><a href="{{ str(route('events.feed'))->replaceMatches('#^https?://#', 'webcal://') }}" class="btn btn-outline-primary btn-sm">Connect</a></td>
                    </tr>
                    <tr>
                        <td class="align-middle">Other (iCal URL)</td>
                        <td>{{ route('events.feed') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

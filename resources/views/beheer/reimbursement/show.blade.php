@extends('layouts.beheer')

@section('title')
{{'Event general information'}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Reimbursement</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('/reimbursement/'.$reimbursement->id . '/edit' )}}" class="btn btn-primary">
                    <em class="ion-edit"></em> {{'Edit'}}
                </a>
                @if(app('request')->input('back') != 'false')
                    <a href="{{url('/reimbursement/')}}" class="btn btn-primary">
                        <em class="ion-android-arrow-back"></em> {{'Back'}}
                    </a>
                @endif
                <form method="post"
                      action="{{ route("reimbursement.destroy", $reimbursement) }}"
                      onsubmit="return confirm('Are you sure you want to delete this reimbursement?');"
                >
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{'Remove'}}</button>
                </form>
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{'Reimbursement information'}}</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped" style="width:100%">
                <tr>
                    <td>{{'Title'}}</td>
                    <td>{{$reimbursement->title}}</td>
                </tr>
                <tr>
                    <td>{{'Short description'}}</td>
                    <td>{{$reimbursement->text}}</td>
                </tr>
                <tr>
                    <td>{{'Total amount'}}</td>
                    <td>{{"€ " . number_format($reimbursement->getAmount(), 2)}}</td>
                </tr>
                <tr>
                    <td>{{'Current status'}}</td>
                    <td>{{$reimbursement->status}}</td>
                </tr>
                <tr>
                    <td>{{'Reimbursement Type'}}</td>
                    <td>{{$reimbursement->type}}</td>
                </tr>
                <tr>
                    <td>{{'Created By'}}</td>
                    <td>{{App\Models\User::find($reimbursement->createdBy)?->getName()}}</td>
                </tr>
                <tr>
                    <td>{{'Created On'}}</td>
                    <td>{{$reimbursement->createdOn}}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h3>{{'Items Bought'}}</h3>
        </div>
        <div class="card-body">
           <table id="reimbursement_items" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{'Item'}}</th>
            <th>{{'Amount'}}</th>
            <th>{{'Paid by'}}</th>
            <th>{{'ESAC card'}}</th>
            <th>{{'Edit'}}</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .icon {
            margin-left: 5px;
        }
    </style>
@endpush

@push('scripts')
    @vite(['resources/assets/js/vendor/datatables.js', 'resources/assets/js/vendor/moment.js', 'resources/assets/js/vendor/tempusdominus.js'])
    <script type="module">

        let reimbursementItemTable = $('#reimbursement_items').DataTable({
            "order": [[0, "asc"]],
            "ajax": {
                'url' : '{{url('api/reimbursementItems?reimbursement=' . $reimbursement->id)}}',
                "dataSrc": "reimbursementItems"
            },
            columnDefs: [
                {width: '15%', targets: [1,3,4]},
                {width: '20%', targets: 2},
                ],
            "columns": [
                {"data": "item"},
                {"data": "amount"},
                {"data": "paidBy"},
                {"data": function(data) {
                    return (data.esacpas) ? "Yes" : "No";
                }},
                {
                    "data": function (data) {
                        return getReimbursementActions(data);
                    }
                },
            ]
        });

        function getReimbursementActions(data) {
            let actions = "";
            actions += '<a class="mr-1 ml-1" href="{{url('reimbursement')}}/' + data.id + '/edit"><span title="{{'Edit event'}}" class="ion-edit font-size-120 icon" aria-hidden="true"></span></a>';
            return actions;
        }
    </script>
@endpush
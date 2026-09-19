<!-- Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{'Add members'}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped collection table-condensed table-bordered table-hover"
                       id="committeeUserList" style="width: 100%">
                    <thead>
                    <tr>
                        <td></td>
                        <td><strong>{{'Name'}}</strong></td>
                        <td><strong>{{'Email address'}}</strong></td>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="addUsers" class="btn btn-primary float-right">Add members</button>
{{--                <a class="btn btn-primary float-right" href="{{ route('committees.addMembers', $committee, ) }}"--}}
            </div>
        </div>
    </div>
</div>

@section('modal_javascript')
    <script>
        let datatable;
        let selectedUserIds = [];
        let committeeMemberIds = [];

        function initializeDataTable() {
            datatable = $('#committeeUserList').DataTable({
                buttons: ['pageLength'],
                ajax: '/api/users',
                columns: [
                    {
                        data: "id",
                        render: function(data) {
                            let inCommittee = committeeMemberIds.includes(data);
                            let isChecked = selectedUserIds.includes(data) || inCommittee ? 'checked' : '';
                            
                            return `<input type='checkbox' value='${data}' class='user-checkbox' ${isChecked} ${inCommittee ? 'disabled title="Already in the committee"' : ''}>`;
                        }
                    },
                    {data: "name"},
                    {data: "email"},
                ],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Alles"]]
            });
        }

        function toggleUserIdInList(userId) {
            if (selectedUserIds.includes(userId)) {
                selectedUserIds.splice(selectedUserIds.indexOf(userId), 1);
            } else {
                selectedUserIds.push(userId);
            }
        }

        function addUsersToCommittee() {
            {{--var mailListId = '{{$committee->id}}';--}}
            
            $.ajax({
                {{--url: "/committees/" + {{$committee->id}} + "/addMembers",--}}
                url: "{{ route('committees.addMembers', $committee) }}",
                data: {
                    member_ids: selectedUserIds,
                    _token: window.Laravel.csrfToken
                },
                type: 'POST',
                success: function() {
                    window.location.reload();
                },
                error: function() {
                    window.location.reload();
                }
            });
        }

        $(document).ready(function() {
            committeeMemberIds = {!! json_encode($committee->members()->allRelatedIds())!!};
            initializeDataTable();

            $(document).on('change', '.user-checkbox', function() {
                toggleUserIdInList($(this).val());
            });

            $(document).on('click', '#addUsers', addUsersToCommittee);
        });
    </script>
@endsection

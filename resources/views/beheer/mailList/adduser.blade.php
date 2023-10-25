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
                       id="maillistUserList" style="width: 100%">
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
                <button id="addUsers" class="btn btn-primary float-right">{{ 'Add members' }}</button>
            </div>
        </div>
    </div>
</div>

@section('modal_javascript')
    <script>
        var datatable;
        var selectedUserIds = [];

        function initializeDataTable() {
            datatable = $('#maillistUserList').DataTable({
                buttons: ['pageLength'],
                ajax: '/api/users',
                columns: [
                    {
                        data: "id",
                        render: function(data) {
                            let isChecked = selectedUserIds.includes(data.toString()) ? 'checked' : '';
                            return `<input type='checkbox' value='${data}' class='user-checkbox' ${isChecked}>`;
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

        function addUsersToMailList() {
            var mailListId = '{{$mailList->getId()}}';
            $.ajax({
                url: '/mailList/' + mailListId + '/member',
                data: {
                    userIds: selectedUserIds,
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
            initializeDataTable();

            $(document).on('change', '.user-checkbox', function() {
                toggleUserIdInList($(this).val());
            });

            $(document).on('click', '#addUsers', addUsersToMailList);
        });
    </script>
@endsection

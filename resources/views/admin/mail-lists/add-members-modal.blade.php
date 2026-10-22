<div class="modal fade" id="add-members-modal" tabindex="-1" role="dialog" aria-labelledby="add-members-modal-title"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-members-modal-title">Add members</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-condensed table-bordered table-hover"
                       id="mail-list-candidates-table" style="width: 100%">
                    <thead>
                    <tr>
                        <td></td>
                        <td><strong>Name</strong></td>
                        <td><strong>Email address</strong></td>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="add-members-submit" class="btn btn-primary float-right">Add members</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        var candidatesTable;
        var selectedMemberIds = [];

        function initializeDataTable() {
            candidatesTable = $('#mail-list-candidates-table').DataTable({
                buttons: ['pageLength'],
                ajax: '{{ route('api.members.index') }}',
                columns: [
                    {
                        data: "id",
                        render: function(data) {
                            let isChecked = selectedMemberIds.includes(data.toString()) ? 'checked' : '';
                            return `<input type='checkbox' value='${data}' class='member-checkbox' ${isChecked}>`;
                        }
                    },
                    {data: "name"},
                    {data: "email"},
                ],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]]
            });
        }

        function toggleSelectedMember(memberId) {
            if (selectedMemberIds.includes(memberId)) {
                selectedMemberIds.splice(selectedMemberIds.indexOf(memberId), 1);
            } else {
                selectedMemberIds.push(memberId);
            }
        }

        function addSelectedMembersToMailList() {
            $.ajax({
                url: '{{ route('admin.mail-lists.members.store', $mailList->id) }}',
                data: {
                    member_ids: selectedMemberIds,
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

            $(document).on('change', '.member-checkbox', function() {
                toggleSelectedMember($(this).val());
            });

            $(document).on('click', '#add-members-submit', addSelectedMembersToMailList);
        });
    </script>
@endpush

<div class="modal fade" id="add-members-modal" tabindex="-1" role="dialog" aria-labelledby="add-members-modal-title"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <form class="modal-content" method="post" action="{{ route('admin.mail-lists.members.store', $mailList->id) }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="add-members-modal-title">Add members</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @foreach ($members as $member)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="member_ids[]" value="{{ $member->id }}" id="member-{{ $member->id }}">
                        <label class="form-check-label" for="member-{{ $member->id }}">{{ $member->name }} ({{ $member->email }})</label>
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add members</button>
            </div>
        </form>
    </div>
</div>

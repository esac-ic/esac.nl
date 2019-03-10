<!-- Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('MailList.addMember')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table  class="table table-striped collection table-condensed table-bordered table-hover" id="maillistUserList" style="width: 100%">
            <thead>
            <tr>
                <td><strong>{{trans('user.name')}}</strong></td>
                <td><strong>{{trans('user.email')}}</strong></td>
                <td><strong>{{trans('menu.action')}}</strong></td>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@section('modal_javascript')
    <script>
        var datatable;
        $(document).ready(function() {
            datatable = $('#maillistUserList').DataTable( {

                buttons: [
                    'pageLength'
                ],
                "ajax": '/api/users',
                "columns": [
                    { "data": "name" },
                    { "data": "email" },
                    { "data": "action" }
                ],
                "lengthMenu": [[10, 25, 50, -1],[10,25,50,"Alles"]]
            } );
        });

        $(document).on('click','#addUser',function(){
            var email = $(this).attr('data-email');
            var name = $(this).attr('data-name');
            var mailListId = '{{$mailList->getAddress()}}';
            $.ajax({
                url: '/mailList/' + mailListId + '/member' ,
                data : {
                    email : email,
                    name : name,
                    _token : window.Laravel.csrfToken
                },
                type: 'POST',
                success : function(){
                    window.location.reload();
                },
                error : function(){
                    window.location.reload();
                }
            });
        });
    </script>
@endsection
@extends('layouts.app')

@section('content')

    <h1>Tickets</h1>
    <a href="{{ route('tickets.create') }}" class="btn btn-primary mb-3">Create Ticket</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table ticket-table">
        <thead>
            <tr>
                <th>ID</th> 
                <th>Title</th>
                <th>Description</th>
                <th>Creation Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <div class="modal fade" id="ticketModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="ticketForm" name="ticketForm" class="form-horizontal">
                       <input type="hidden" name="id" id="id">

                        <div class="form-group">
                            <label for="status" class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-12">
                                <select class="form-control" name="status" id="status">
                                    <option value="pending">Pending</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="savedata" value="create">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
    $(function () {

        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('.ticket-table').DataTable({
           processing: true,
           serverSide: true,
           ajax: "{{ route('tickets.index') }}",
           columns: [
               {data: 'DT_RowIndex', name: 'DT_RowIndex'},
               {data: 'title', name: 'title'},
               {data: 'description', name: 'description'},
               {
                   data: 'created_at',
                   type: 'num',
                   render: {
                      _: 'display',
                      sort: 'timestamp'
                   }
                },
               {data: 'status', name: 'status'},
               {data: 'action', name: 'action', orderable: false},
           ]
        });

        $('body').on('click', '.editTicket', function () {
            var id = $(this).data('id');
            var status = $(this).data('status');
            $('#ticketModel').modal('show');
            $('#id').val(id);
            $('#status').val(status);
        });

        $('#savedata').click(function (e) {
            e.preventDefault();
            $(this).html('Sending..');
        
            $.ajax({
              data: $('#ticketForm').serialize(),
              url: "{{ route('tickets.changeStatus') }}",
              type: "POST",
              dataType: 'json',
              success: function (data) {
         
                  $('#ticketForm').trigger("reset");
                  $('#ticketModel').modal('hide');
                  table.draw();
                  location.reload();
             
              },
              error: function (data) {
                  console.log('Error:', data);
                  $('#savedata').html('Save Changes');
              }
            });
        });

    });  
      </script>
@endpush
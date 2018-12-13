@extends('layout')
@section('content')

    @push('bottom')
        <script>
            function loadData()
            {
                $.get("{{ url('oauth/clients') }}", function (r) {
                    if(r) {
                        var table = $("#table-client");

                        if ($.fn.DataTable.isDataTable("#table-client")) {
                            table.DataTable().clear().destroy();
                        }

                        var tr = "";
                        $.each(r,function (i,obj) {
                            tr += "<tr>" +
                                "<td>" + obj.id + "</td>" +
                                "<td>" + obj.name + "</td>" +
                                "<td>" + obj.secret + "</td>" +
                                "<td>" + obj.redirect + "</td>" +
                                "<td>" +
                                "<a href='javascript:void(0);' onclick='deleteClient(this)' data-id='"+obj.id+"' title='Delete client' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i></a> " +
                                "<a href='javascript:void(0);' onclick='showModalEditClient(this)' data-id='" + obj.id +"' data-name='"+ obj.name + "' data-redirect='"+ obj.redirect +"' title='Edit client' class='btn btn-primary btn-sm'><i class=' fa fa-pencil'></i></a> " +
                                "</td>" +
                                "</tr>";
                        })
                        table.find("tbody").html(tr);
                        table.DataTable({
                            "order": [[ 0, "desc" ]]
                        } );
                    }
                })
            }

            function deleteClient(t)
            {
                if(confirm('Are you sure want to delete?')) {
                    let id = $(t).data('id');
                    $("#alert").fadeIn().html("<i class='fa fa-spin fa-spinner'></i> Please wait while deleting...");
                    $.post("{{ url('oauth/clients') }}/"+ id,{
                        _method: "delete",
                        _token: "{{ csrf_token() }}"
                    },function (r) {
                        location.href = window.location.href;
                    })
                }
            }
            $(function () {
                loadData();
            })
        </script>
    @endpush

    <p>
        <a href="javascript:;" onclick="showModalNewClient()" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Client</a>
    </p>

    <div id="alert" style="display: none" class="alert alert-warning">
        <i class="fa fa-info"></i> Please wait while submit the data...
    </div>

    <table id="table-client" class="table datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>NAME</th>
                <th>SECRET</th>
                <th>REDIRECT</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

    @push('bottom')
        <script>
            function showModalNewClient() {
                $("#modal-new-client").modal("show");
            }

            function showModalEditClient(t) {
                $("#modal-edit-client").modal("show");
                let modal = $("#modal-edit-client");
                modal.find("input[name=id]").val( $(t).data('id') );
                modal.find('input[name=name]').val( $(t).data('name') );
                modal.find('input[name=redirect]').val( $(t).data('redirect') );

            }

            function submitNewclient(t) {
                let name = $("#modal-new-client input[name=name]").val();
                let redirect = $("#modal-new-client input[name=redirect]").val();

                if(name == "" || redirect == "") {
                    return false;
                }

                $("#modal-new-client").modal("hide");
                $("#alert").fadeIn().html("<i class='fa fa-spin fa-spinner'></i> Please wait while submitting...");

                $.post("{{ url('oauth/clients') }}",{
                    _token: "{{ csrf_token() }}",
                    name: name,
                    redirect: redirect
                },function (r) {
                    $("#alert").fadeOut();
                    location.href = window.location.href;
                })
            }

            function submitUpdateClient(t) {
                let id = $("#modal-edit-client input[name=id]").val();
                let name = $("#modal-edit-client input[name=name]").val();
                let redirect = $("#modal-edit-client input[name=redirect]").val();

                if(name == "" || redirect == "") {
                    return false;
                }

                $("#modal-edit-client").modal("hide");
                $("#alert").fadeIn().html("<i class='fa fa-spin fa-spinner'></i> Please wait while updating...");

                $.post("{{ url('oauth/clients') }}/" + id,{
                    _token: "{{ csrf_token() }}",
                    _method: "put",
                    id: id,
                    name: name,
                    redirect: redirect
                },function (r) {
                    $("#alert").fadeOut();
                    location.href = window.location.href;
                })
            }
        </script>
    @endpush

    <div class="modal" id="modal-new-client" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Client</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" class="form-control" name="name" required >
                    </div>
                    <div class="form-group">
                        <label for="">Redirect</label>
                        <input type="text" class="form-control" name="redirect" required >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" onclick="submitNewclient(this)" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modal-edit-client" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Client</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" class="form-control" name="name" required >
                    </div>
                    <div class="form-group">
                        <label for="">Redirect</label>
                        <input type="text" class="form-control" name="redirect" required >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" onclick="submitUpdateClient(this)" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>

@endsection
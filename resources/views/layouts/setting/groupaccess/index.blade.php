@extends('layouts.system.master', ['title' => 'Group Access'])

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Group Access</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active">User Management</div>
              <div class="breadcrumb-item"><a href="#">Group Access</a></div>
              <!-- <div class="breadcrumb-item">Default Layout</div> -->
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Group Access</h2>
            <!-- <p class="section-lead">This page is just an example for you to create your own page.</p> -->
            <div class="card">
              <div class="card-header">
                <button type="button" class="btn btn-primary" id="add_group">Tambah Group</button>
              </div>
              <div class="card-body">
              	<div class="table-responsive">
	                <table id="datatable" class="table table-bordered table-striped">
	                  <thead>
	                      <tr>
	                          <th>#</th>
	                          <th>Nama Group</th>
	                          <th>Akses Menu</th>
	                          <th>Pengguna</th>
	                          <th>Aksi</th>
	                      </tr>
	                  </thead>
	                </table>
                </div>
              </div>
              <!-- <div class="card-footer bg-whitesmoke">
                This is card footer
              </div> -->
            </div>
        </div>
    </section>

    <!-- modal add group -->
    <div class="modal fade" id="addgroup">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-horizontal">
                        @csrf
                        <input type="hidden" id="idku">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Nama Group</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="namegroup" name="namegroup"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Email</label>
                                    <div class="col-sm-12">
                                        <input type="email" class="form-control" id="emailuser" name="emailuser"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary pull-left" id="submitbtn">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->

</div>
@endsection

@section('script')
<script type="text/javascript">
  $(document).ready(function() {
    var mytable = $('#datatable').dataTable({
        order: [],
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('datatablegroup') }}",
        },

        "fnCreatedRow": function(row, data, index) {
            $('td', row).eq(0).html(index + 1);
        },

        columns: [
            // data is for view, name is for real value
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'namagroup',
                name: 'namagroup'
            },
            {
                data: 'menuakses',
                name: 'menuakses'
            },
            {
                data: 'pengguna',
                name: 'pengguna'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ],
    });

    $('#datatable').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    })

    $('#add_group').click(function(e) {
        $('#namegroup').val('');
        $('#idku').val('');
        $('#modaltitle').text('Tambah Group');
        $('#addgroup').modal({
            show: true,
            backdrop: 'static'
        });
    });

    $('body').on('click', '#editgroup', function() {
    	$('#modaltitle').text('Edit Group');
        $('#addgroup').modal({
            show: true,
            backdrop: 'static'
        });
        let idku = $(this).attr('data-id');

        $.ajax({
            url: "{!! route('editgroup') !!}",
            type: 'POST',
            dataType: 'json',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                id: idku,
            },
        }).done(function(data) {
            let dataku = data.data;
            console.log('data :>> ', dataku);

            $('#idku').val(dataku.id_groupaccess);
            $('#namegroup').val(dataku.nama_groupaccess);
        })
    });

    $('#submitbtn').click(function(e) {
        let id = $('#idku').val();
        let namagroup = $('#namegroup').val();
        
        if (namagroup == null || namagroup == '') {
            Swal.fire({
                title: 'Informasi',
                text: ' Nama Group Tidak Boleh Kosong!!',
                type: 'warning'
            });
            return;
        } else {
            $.ajax({
                type: "post",
                url: (id == '') ? "{{ route('savegroup') }}" :
                    "{{ route('updategroup') }}",
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    id: id,
                    namagroup: namagroup,
                },
                dataType: "json",
                success: function(response) {
                    Swal.fire({
                        title: response.title,
                        text: response.message,
                        type: (response.status != 'error') ? 'success' : 'error'
                    }).then((result) => {
                    	$('#addgroup').modal('hide');
                    	$('#datatable').DataTable().ajax.reload();
                    	// mytable.ajax.reload();
                        // (response.status == 'success') ? window.location
                        //     .replace("{{ route('groupaccess') }}"):
                        //     ''
                    });
                    return;
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Unsuccessfully Saved Data',
                        text: 'Check Your Data',
                        type: 'error'
                    });
                    return;
                }
            });
        }
    });

    $('body').on('click', '#deletegroup', function() {
        let idku = $(this).attr('data-id');
        let url = '{!! url('deletegroup') !!}' + '/' + idku;
        // url = url.replace('params', idku);
        // console.log('idku :>> ', url);
        Swal.fire({
            title: 'Validasi Hapus Data!',
            text: 'Apakah Anda Yakin Untuk Menghapus Data Ini ?',
            type: 'question',
            showConfirmButton: true,
            showCancelButton: true,
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "JSON",
                    success: function(response) {
                        Swal.fire({
                            title: response.title,
                            text: response.message,
                            type: (response.status != 'error') ?
                                'success' : 'error'
                        }).then(() => {
                            $('#datatable').DataTable().ajax.reload();
                            // (response.status == 'success') ? window
                            //     .location
                            //     .replace("{{ route('useraccess') }}"):
                            //     ''
                        })
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Unsuccessfully Saved Data',
                            text: 'Check Your Data',
                            type: 'error'
                        });
                        return;
                    }
                });
                return false;
            }
        })
    });

  });
</script>
@endsection
@extends('layouts.system.master', ['title' => 'Akses Pengguna'])

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Akses Pengguna</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active">Pengaturan Pengguna</div>
              <div class="breadcrumb-item"><a href="#">Akses Pengguna</a></div>
              <!-- <div class="breadcrumb-item">Default Layout</div> -->
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Akses Pengguna</h2>
            <!-- <p class="section-lead">This page is just an example for you to create your own page.</p> -->
            <div class="card">
              <div class="card-header">
                <button type="button" class="btn btn-primary" id="add_user">Tambah Pengguna</button>
              </div>
              <div class="card-body">
                <form class="form-horizontal d-none" id="inputform" action="#">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <input type="hidden" id="idku">
                                <div class="col-md-3">
                                    <label class="col-sm-12 control-label"><strong>Nama Pengguna</strong></label>
                                    <div class="col-sm-12">
                                        <input class="form-control" type="text" name="nameuser" id="nameuser" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="col-sm-12 control-label"><strong>Email Pengguna</strong></label>
                                    <div class="col-sm-12">
                                        <input class="form-control" type="text" name="emailuser" id="emailuser" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="col-sm-12 control-label"><strong>Group Akses</strong></label>
                                    <div class="col-sm-12">
                                        <select class="select2" style="width: 100%;" name="groupakses" id="groupakses">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="col-sm-12 control-label"> &nbsp; </label>
                                    <div class="col-sm-8">
                                        <a href="#" type="button" id="submitbtn" class="btn btn-info form-control">Tambah</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <br>
                <table id="datatable" class="table table-bordered table-striped">
                  <thead>
                      <tr>
                          <th>No</th>
                          <th>Nama</th>
                          <th>Email</th>
                          <th>Group Akses</th>
                          <th>Aksi</th>
                      </tr>
                  </thead>
                </table>
              </div>
              <!-- <div class="card-footer bg-whitesmoke">
                This is card footer
              </div> -->
            </div>
        </div>
    </section>

    <!-- modal add user -->
    <div class="modal fade" id="adduser">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="modaltitle">Add User</span></h4>
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
                                    <label class="col-sm-12 control-label">Name</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nameuser" name="nameuser"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label">Email</label>
                                    <div class="col-sm-12">
                                        <input type="email" class="form-control" id="emailuser" name="emailuser"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary pull-left" id="submitbtn">Submit</button>
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
            url: "{{ route('datatableuser') }}",
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
                data: 'namauser',
                name: 'namauser'
            },
            {
                data: 'emailuser',
                name: 'emailuser'
            },
            {
                data: 'groupakses',
                name: 'groupakses'
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

    $('#add_user').click(function(e) {
        // console.log('idku :>> ', 'tt');
        $('#inputform').removeClass('d-none');
        $('#idku').val('');
        $('#nameuser').val('');
        $('#emailuser').val('');
        $('#groupakses').empty();
        $('#submitbtn').text('Tambah');
        // $('#adduser').modal({
        //     show: true,
        //     backdrop: 'static'
        // });
    });

    $('body').on('click', '#edituser', function() {
        // $('#adduser').modal({
        //     show: true,
        //     backdrop: 'static'
        // });
        let idku = $(this).attr('data-id');

        $.ajax({
            url: "{!! route('edituser') !!}",
            type: 'POST',
            dataType: 'json',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                id: idku,
            },
        }).done(function(data) {
            let dataku = data.data;
            console.log('dataedit :>> ', dataku);
            $('#add_user').trigger('click');
            $('#idku').val(dataku.id);
            $('#nameuser').val(dataku.name);
            $('#emailuser').val(dataku.email);
            // $('#groupakses').val(dataku.nama_groupaccess);
            $('#groupakses').empty().html('<option value="' + dataku.id_groupaccess + '">' + dataku.nama_groupaccess + '</option>').val(dataku.id_groupaccess).trigger('change');
            $('#submitbtn').text('Ubah');
        })
    });

    $('#submitbtn').click(function(e) {
        let id = $('#idku').val();
        let nameuser = $('#nameuser').val();
        let emailuser = $('#emailuser').val();
        let group = $('#groupakses').val();
        
        if (nameuser == null || nameuser == '') {
            Swal.fire({
                title: 'Informasi',
                text: ' Nama User Tidak Boleh Kosong!!',
                type: 'warning'
            });
            return;
        } else if (emailuser == null || emailuser == '') {
            Swal.fire({
                title: 'Informasi',
                text: ' Email User Tidak Boleh Kosong!!',
                type: 'warning'
            });
            return;
        } else if (IsEmail(emailuser) == false) {
            Swal.fire({
                title: 'Informasi',
                text: ' Mohon Untuk Gunakan Format Email yang Benar',
                type: 'warning'
            });
            return;
        } else if (group == null || group == '') {
            Swal.fire({
                title: 'Informasi',
                text: ' Group Akses Tidak Boleh Kosong',
                type: 'warning'
            });
            return;
        } else {
            $.ajax({
                type: "post",
                url: (id == '') ? "{{ route('saveuser') }}" :
                    "{{ route('updateuser') }}",
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    id: id,
                    nameuser: nameuser,
                    emailuser: emailuser,
                    groupakses: group
                },
                dataType: "json",
                success: function(response) {
                    Swal.fire({
                        title: response.message,
                        type: (response.status != 'error') ? 'success' : 'error',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                    }).then((result) => {
                        $('#idku').val('');
                        $('#nameuser').val('');
                        $('#emailuser').val();
                        $('#groupakses').empty();
                        $('#submitbtn').html('Tambah');
                        $('#inputform').addClass('d-none');
                        $('#datatable').DataTable().ajax.reload();
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

    $('body').on('click', '#deleteuser', function() {
        let idku = $(this).attr('data-id');
        let url = '{!! url('deleteuser') !!}' + '/' + idku;
        // url = url.replace('params', idku);
        // console.log('idku :>> ', url);
        Swal.fire({
            title: 'Validation delete data!',
            text: 'Are you sure you want to delete the data  ?',
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
                            // oTable.ajax.reload();
                            (response.status == 'success') ? window
                                .location
                                .replace("{{ route('useraccess') }}"):
                                ''
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

    function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(email)) {
            return false;
        } else {
            return true;
        }
    }

    $('#groupakses').select2({
        placeholder: '-- Pilih Group Akses --',
        ajax: {
            url: "{!! route('getgroupakses') !!}",
            dataType: 'json',
            delay: 500,
            type: 'post',
            data: function(params) {
                var query = {
                    q: params.term,
                    page: params.page || 1,
                    _token: $('meta[name=csrf-token]').attr('content')
                }
                return query;
            },
            processResults: function(data, params) {
                console.log('data :>> ', data);
                return {
                    results: $.map(data.data, function(item) {
                        return {
                            text: item.nama_groupaccess,
                            id: item.id_groupaccess,
                            selected: true,
                        }
                    }),
                    pagination: {
                        more: data.to < data.total
                    }
                };
            },
            cache: true
        }
    });

  });
</script>
@endsection
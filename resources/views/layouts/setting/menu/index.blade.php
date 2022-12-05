@extends('layouts.system.master', ['title' => 'Menu Access'])
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
@endsection
@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Menu Access</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active">User Management</div>
              <div class="breadcrumb-item"><a href="#">Menu Access</a></div>
              <!-- <div class="breadcrumb-item">Default Layout</div> -->
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Menu Access</h2>
            <!-- <p class="section-lead">This page is just an example for you to create your own page.</p> -->
            <div class="card">
              <!-- <div class="card-header">
                <button type="button" class="btn btn-primary" id="add_menu">Tambah Menu</button>
              </div> -->
              <div class="card-body">
              	<div class="row">
              		<div class="col-md-12">
              			<div class="row">
              				<input type="hidden" id="idku">
          					<div class="col-md-3">
                                <label class="col-sm-12 control-label"><strong>Nama Menu</strong></label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="text" name="namamenu" id="namamenu">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="col-sm-12 control-label"> &nbsp; </label>
                                <div class="col-sm-8">
                                    <a href="#" type="button" id="btntambah" class="btn btn-info form-control">Tambah</a>
                                </div>
                            </div>
              			</div>
              		</div>
              	</div>
              	<br>
              	<div class="table-responsive">
	                <table id="datatable" class="table table-bordered table-striped">
	                  <thead>
	                      <tr>
	                          <th>Kode Menu</th>
	                          <th>Nama Menu</th>
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
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    var mytable = $('#datatable').dataTable({
        order: [],
        processing: true,
        serverSide: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        ajax: {
            url: "{{ route('datatablemenu') }}",
        },

        "fnCreatedRow": function(row, data, index) {
            $('td', row).eq(0).html(index + 1);
        },

        columns: [
            // data is for view, name is for real value
            {
                data: 'kodemenu',
                name: 'kodemenu'
            },
            {
                data: 'namamenu',
                name: 'namamenu'
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

    $('body').on('click', '#editmenu', function() {
        let idku = $(this).attr('data-id');

        $.ajax({
            url: "{!! route('editmenu') !!}",
            type: 'POST',
            dataType: 'json',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                id: idku,
            },
        }).done(function(data) {
            let dataku = data.data;
            console.log('data :>> ', dataku);

            $('#idku').val(dataku.id_menu);
            $('#namamenu').val(dataku.nama_menu);
            $('#btntambah').html('Ubah');
        })
    });

    $('#btntambah').click(function(e) {
  //   	Swal.fire({
  //   	  title: 'Menu Berhasil Disimpan',
  //         type: 'success',
		//   toast: true,
		//   position: 'top-end',
		//   showConfirmButton: false,
		//   timer: 2000,
		// })

        let id = $('#idku').val();
        let namamenu = $('#namamenu').val();
        
        if (namamenu == null || namamenu == '') {
            Swal.fire({
                title: 'Informasi',
                text: ' Nama Menu Tidak Boleh Kosong!!',
                type: 'warning'
            });
            return;
        } else {
            $.ajax({
                type: "post",
                url: (id == '') ? "{{ route('savemenu') }}" :
                    "{{ route('updatemenu') }}",
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    id: id,
                    namamenu: namamenu,
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
                    	$('#namamenu').val('');
                    	$('#btntambah').html('Tambah');
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

    $('body').on('click', '#deletemenu', function() {
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
                            title: response.message,
	                        type: (response.status != 'error') ? 'success' : 'error',
	                        toast: true,
							position: 'top-end',
							showConfirmButton: false,
							timer: 2000,
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
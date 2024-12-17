@extends('layouts.app')

@include('rak.create')
@include('rak.edit')

@section('content')
    <div class="section-header">
        <h1>Data Rak</h1>
        <div class="ml-auto">
            <a href="javascript:void(0)" class="btn btn-primary" id="button_tambah_kode_rak"><i class="fa fa-plus"></i>
                Data Rak</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_id" class="display">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Rak</th>
                                    <th>Nama Rak</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Datatables Jquery -->
    <script>
        $(document).ready(function() {
            $('#table_id').DataTable({
                paging: true
            });
            $.ajax({
                url: "/rak/get-data",
                type: "GET",
                dataType: 'JSON',
                success: function(response) {
                    let counter = 1;
                    $('#table_id').DataTable().clear();
                    $.each(response.data, function(key, value) {
                        let kodeRak = `
                        <tr class="barang-row" id="index_${value.id}">
                            <td>${counter++}</td>   
                            <td>${value.kd_rak}</td>
                            <td>${value.nm_rak}</td>
                            <td>
                                <a href="javascript:void(0)" id="button_edit_kode_rak" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2"><i class="far fa-edit"></i> </a>
                                <a href="javascript:void(0)" id="button_hapus_kode_rak" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                            </td>
                        </tr>
                         `;
                        $('#table_id').DataTable().row.add($(kodeRak)).draw(false);
                    });
                }
            });
        });
    </script>

    <!-- Show Modal Tambah Jenis Barang -->
    <script>
        $('body').on('click', '#button_tambah_kode_rak', function() {
            $('#modal_tambah_kode_rak').modal('show');
        });

        $('#store').click(function(e) {
            e.preventDefault();

            let kd_rak = $('#kd_rak').val();
            let nm_rak = $('#nm_rak').val();
            let token = $("meta[name='csrf-token']").attr("content");

            let formData = new FormData();
            formData.append('nm_rak', nm_rak);
            formData.append('kd_rak', kd_rak);
            formData.append('_token', token);

            $.ajax({
                url: '/rak',
                type: "POST",
                cache: false,
                data: formData,
                contentType: false,
                processData: false,

                success: function(response) {
                    Swal.fire({
                        type: 'success',
                        icon: 'success',
                        title: `${response.message}`,
                        showConfirmButton: true,
                        timer: 3000
                    });

                    $.ajax({
                        url: '/rak/get-data',
                        type: "GET",
                        cache: false,
                        success: function(response) {
                            $('#table-barangs').html('');

                            let counter = 1;
                            $('#table_id').DataTable().clear();
                            $.each(response.data, function(key, value) {
                                let kodeRak = `
                               <tr class="barang-row" id="index_${value.id}">
                                    <td>${counter++}</td>   
                                    <td>${value.kd_rak}</td>
                                    <td>${value.nm_rak}</td>
                                    <td>
                                        <a href="javascript:void(0)" id="button_edit_kode_rak" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2"><i class="far fa-edit"></i> </a>
                                        <a href="javascript:void(0)" id="button_hapus_kode_rak" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                                    </td>
                                </tr>
                                `;
                                $('#table_id').DataTable().row.add($(kodeRak))
                                    .draw(false);
                            });

                            $('#kd_rak').val('');
                            $('#nm_rak').val('');
                            $('#modal_tambah_kode_rak').modal('hide');

                            let table = $('#table_id').DataTable();
                            table.draw();
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    })
                },

                error: function(error) {
                    if (error.responseJSON && error.responseJSON.kd_rak && error.responseJSON
                        .kd_rak[0]) {
                        $('#alert-kd_rak').removeClass('d-none');
                        $('#alert-kd_rak').addClass('d-block');

                        $('#alert-kd_rak').html(error.responseJSON.kd_rak[0]);
                    }
                    if (error.responseJSON && error.responseJSON.nm_rak && error.responseJSON
                        .nm_rak[0]) {
                        $('#alert-nm_rak').removeClass('d-none');
                        $('#alert-nm_rak').addClass('d-block');

                        $('#alert-nm_rak').html(error.responseJSON.nm_rak[0]);
                    }
                }
            });
        });
    </script>

    <!-- Edit Data Jenis Barang -->
    <script>
        //Show modal edit
        $('body').on('click', '#button_edit_kode_rak', function() {
            let rak_id = $(this).data('id');

            $.ajax({
                url: `/rak/${rak_id}/edit`,
                type: "GET",
                cache: false,
                success: function(response) {
                    $('#rak_id').val(response.data.id);
                    $('#edit_kd_rak').val(response.data.kd_rak);
                    $('#edit_nm_rak').val(response.data.nm_rak);

                    $('#modal_edit_kode_rak').modal('show');
                }
            });
        });

        // Proses Update Data
        $('#update').click(function(e) {
            e.preventDefault();

            let rak_id = $('#rak_id').val();
            let kd_rak = $('#edit_kd_rak').val();
            let nm_rak = $('#edit_nm_rak').val();
            let token = $("meta[name='csrf-token']").attr('content');

            let formData = new FormData();
            formData.append('kd_rak', kd_rak);
            formData.append('nm_rak', nm_rak);
            formData.append('_token', token);
            formData.append('_method', 'PUT');

            $.ajax({
                url: `/rak/${rak_id}`,
                type: "POST",
                cache: false,
                data: formData,
                contentType: false,
                processData: false,

                success: function(response) {
                    Swal.fire({
                        type: 'success',
                        icon: 'success',
                        title: `${response.message}`,
                        showConfirmButton: true,
                        timer: 3000
                    });

                    let row = $(`#index_${response.data.id}`);
                    let rowData = row.find('td');
                    rowData.eq(1).text(response.data.kd_rak);
                    rowData.eq(2).text(response.data.nm_rak);

                    $('#modal_edit_kode_rak').modal('hide');
                },

                error: function(error) {
                    if (error.responseJSON && error.responseJSON.kd_rak && error.responseJSON
                        .kd_rak[0]) {
                        $('#alert-kd_rak').removeClass('d-none');
                        $('#alert-kd_rak').addClass('d-block');

                        $('#alert-kd_rak').html(error.responseJSON.kd_rak[0]);
                    }
                    if (error.responseJSON && error.responseJSON.nm_rak && error.responseJSON
                        .nm_rak[0]) {
                        $('#alert-nm_rak').removeClass('d-none');
                        $('#alert-nm_rak').addClass('d-block');

                        $('#alert-nm_rak').html(error.responseJSON.nm_rak[0]);
                    }
                }
            });
        });
    </script>

    <!-- Hapus Data Barang -->
    <script>
        $('body').on('click', '#button_hapus_kode_rak', function() {
            let rak_id = $(this).data('id');
            let token = $("meta[name='csrf-token']").attr("content");

            Swal.fire({
                title: 'Apakah Kamu Yakin?',
                text: "ingin menghapus data ini !",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'TIDAK',
                confirmButtonText: 'YA, HAPUS!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/rak/${rak_id}`,
                        type: "DELETE",
                        cache: false,
                        data: {
                            "_token": token
                        },
                        success: function(response) {
                            Swal.fire({
                                type: 'success',
                                icon: 'success',
                                title: `${response.message}`,
                                showConfirmButton: true,
                                timer: 3000
                            });
                            $('#table_id').DataTable().clear().draw();

                            $.ajax({
                                url: "/rak/get-data",
                                type: "GET",
                                dataType: 'JSON',
                                success: function(response) {
                                    let counter = 1;
                                    $('#table_id').DataTable().clear();
                                    $.each(response.data, function(key, value) {
                                        let kodeRak = `
                                        <tr class="barang-row" id="index_${value.id}">
                                            <td>${counter++}</td>   
                                            <td>${value.kd_rak}</td>
                                            <td>${value.nm_rak}</td>
                                            <td>
                                                <a href="javascript:void(0)" id="button_edit_kode_rak" data-id="${value.id}" class="btn btn-icon btn-warning btn-lg mb-2"><i class="far fa-edit"></i> </a>
                                                <a href="javascript:void(0)" id="button_hapus_kode_rak" data-id="${value.id}" class="btn btn-icon btn-danger btn-lg mb-2"><i class="fas fa-trash"></i> </a>
                                            </td>
                                        </tr>
                                         `;
                                        $('#table_id').DataTable().row.add(
                                            $(kodeRak)).draw(false);
                                    });
                                }
                            });
                        }
                    })
                }
            });
        });
    </script>
@endsection

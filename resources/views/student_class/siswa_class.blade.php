@extends('master')

@section('title', '')

@section('alert')

@if(Session::has('alert_success'))
    @component('components.alert')
        @slot('class')
            success
        @endslot
        @slot('title')
            Terimakasih
        @endslot
        @slot('message')
            {{ session('alert_success') }}
        @endslot
    @endcomponent
@elseif(Session::has('alert_error'))
    @component('components.alert')
        @slot('class')
            error
        @endslot
        @slot('title')
            Cek Kembali
        @endslot
        @slot('message')
            {{ session('alert_error') }}
        @endslot
    @endcomponent 
@endif

@endsection

@section('content')
    <div class="table-responsive">
        <table id="siswa_table" class="table table-bordered data-table display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th style="text-align: center" width="400px">Nama</th>
                    <th style="text-align: center">Angkatan</th>
                    <th style="text-align: center">Kelas</th>
                    <th style="text-align: center" width="50px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
@endsection

@section('modal')

@endsection

@push('scripts')
    <link rel="stylesheet" type="text/css" href="<?= URL::to('/'); ?>/layout/assets/css/jquery.dataTables.css">

    <script type="text/javascript" charset="utf8" src="<?= URL::to('/'); ?>/layout/assets/js/jquery.dataTables.js" defer></script>
    <script type="text/javascript">
        var iduser;
        var table;

        function clearAll() {
            $('#nama').val('');
            $('#angkatan').val('');
            $('#kelas').val('');
        }

        $(function() {
            table = $('#siswa_table').DataTable({
                processing: true,
                serverSide: true,
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                responsive: true,
                ajax: "{{ route('siswa-class', ['id_kelas'=>$id_kelas]) }}",
                columns: [{
                    data: 'full_name',
                    name: 'full_name'
                },
                {
                    data: 'angkatan',
                    name: 'angkatan'
                },
                {
                    data: 'kelas',
                    name: 'kelas'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
                ]
            });
        });

        function btnDel(id) {
        iduser = id;
        idkelas = "{{ route('delete-siswa', ['id_kelas'=>$id_kelas]) }}";
        swal({
            title: "Hapus User",
            text: 'Akan mengeluarkan Siswa ini dari kelas.',
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                type: 'POST',
                url: idkelas,
                data: {
                    iduser: iduser,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    if (data.status != false) {
                    swal(data.message, {
                        button: false,
                        icon: "success",
                        timer: 1000
                    });
                    } else {
                    swal(data.message, {
                        button: false,
                        icon: "error",
                        timer: 1000
                    });
                    }
                    table.ajax.reload();
                },
                error: function(error) {
                    swal('Terjadi kegagalan sistem', {
                    button: false,
                    icon: "error",
                    timer: 1000
                    });
                }
                });
            }
            });
        }

        function btnTgs(id){
            iduser = id;
            idkelas = "{{ $id_kelas }}";
            url = '/student-class/' + idkelas + '/siswa-class/' + iduser;
            window.location.href = url;
        }
    </script>
@endpush
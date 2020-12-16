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

<div style="padding-bottom: 20px">
  <a href="{{ route('create-user') }}" type="button" class="ui huge inverted primary button"> TAMBAH </a>
</div>

<div style="width: 100%; padding-left: -10px;">
  <div class="table-responsive">
    <table id="user_table" class="table table-bordered data-table display nowrap" style="width:100%">
      <thead>
        <tr>
          <th style="text-align: center;" width="300">Nama</th>
          <th style="text-align: center">Username</th>
          <th style="text-align: center">Email</th>
          <th style="text-align: center">Account Type</th>
          <th style="text-align: center; width: 100px">Action</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
</div>

@endsection

@section('modal')

<div class="modal fade" id="detailModal" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <p class="modal-title">User Detail</p>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Username</label>
          <input type="text" class="form-control" value="" id="username">
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="text" class="form-control" value="" id="email">
        </div>
        <div class="form-group">
          <label>Nama</label>
          <input type="text" class="form-control" value="" id="nama_lengkap">
        </div>
        <div class="form-group">
          <label for="sel1">Tipe Akun</label>
          <select class="form-control" id="tipe_akun">
            <option value="{{ User::ACCOUNT_TYPE_ADMIN }}">Administrator</option>
            <option value="{{ User::ACCOUNT_TYPE_TEACHER }}">Guru</option>
            <option value="{{ User::ACCOUNT_TYPE_SISWA }}">Siswa</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="update_data" class="ui huge inverted primary button">Update</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="updatePassword" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <p class="modal-title">Update Password</p>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Username</label>
          <input type="text" class="form-control" value="" id="username_password" disabled>
        </div>
        <div class="form-group">
          <label>Password Baru</label>
          <input type="password" class="form-control" value="" id="password">
        </div>
        <div class="form-group">
          <label>Konfirmasi Password</label>
          <input type="password" class="form-control" value="" id="password_confirmation">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="update_data_password" class="ui huge inverted primary button">Update Password</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
  <link rel="stylesheet" type="text/css" href="<?= URL::to('/'); ?>/layout/assets/css/jquery.dataTables.css">

  <script type="text/javascript" charset="utf8" src="<?= URL::to('/'); ?>/layout/assets/js/jquery.dataTables.js" defer></script>
  <script type="text/javascript">
    var iduser;
    var table;

    function clearAll() {
      $('#username').val('');
      $('#tipe_akun').val('');
      $('#email').val('');
      $('#nama_lengkap').val('');
    }

    $(function() {
      table = $('#user_table').DataTable({
        processing: true,
        serverSide: true,
        rowReorder: {
          selector: 'td:nth-child(2)'
        },
        responsive: true,
        ajax: "{{ route('index-user') }}",
        columns: [{
            data: 'full_name',
            name: 'full_name'
          },
          {
            data: 'username',
            name: 'username'
          },
          {
            data: 'email',
            name: 'email'
          },
          {
            data: 'account_type',
            name: 'account_type'
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
      swal({
          title: "Hapus User",
          text: 'User yang telah dihapus tidak dapat direstorasi',
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            $.ajax({
              type: 'POST',
              url: '/user/delete',
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

    function btnPass(id) {
      $('#updatePassword').modal('toggle');
      iduser = id;
      $.ajax({
        type: 'POST',
        url: base_url + '/user/get-detail',
        data: {
          iduser: iduser,
          "_token": "{{ csrf_token() }}",
        },
        success: function(data) {
          $('#username_password').val(data.data.username);
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

    function btnUbah(id) {
      $('#detailModal').modal('toggle');
      iduser = id;
      $.ajax({
        type: 'POST',
        url: base_url + '/user/get-detail',
        data: {
          iduser: iduser,
          "_token": "{{ csrf_token() }}",
        },
        success: function(data) {
          $('#username').val(data.data.username);
          $('#email').val(data.data.email);
          $('#nama_lengkap').val(data.data.full_name);
          $('#tipe_akun').val(data.data.account_type);
        }
      });
    }

    $(document).ready(function() {
      $('#non_aktif_button').click(function() {
        btnDel(iduser)
        $("#detailModal .close").click()
      })
      $('#update_data_password').click(function() {
        var password = $('#password').val();
        var password_confirmation = $('#password_confirmation').val();
        $.ajax({
          type: 'POST',
          url: base_url + '/user/update-password',
          data: {
            iduser: iduser,
            "_token": "{{ csrf_token() }}",
            password: password,
            password_confirmation: password_confirmation,
          },
          success: function(data) {
            if (data.status != false) {
              swal(data.message, {
                button: false,
                icon: "success",
                timer: 1000
              });
              $("#updatePassword .close").click()
            } else {
              swal(data.message, {
                button: false,
                icon: "error",
                timer: 1000
              });
            }
          },
          error: function(error) {
            var err = eval("(" + error.responseText + ")");
            var array_1 = $.map(err, function(value, index) {
              return [value];
            });
            var array_2 = $.map(array_1, function(value, index) {
              return [value];
            });
            var message = JSON.stringify(array_2);
            swal(message, {
              button: false,
              icon: "error",
              timer: 1000
            });
          }
        });
      })
      $('#update_data').click(function() {
        var username = $('#username').val();
        var email = $('#email').val();
        var full_name = $('#nama_lengkap').val();
        var account_type = $('#tipe_akun').val();
        $.ajax({
          type: 'POST',
          url: base_url + '/user/update',
          data: {
            iduser: iduser,
            "_token": "{{ csrf_token() }}",
            username: username,
            email: email,
            full_name: full_name,
            account_type: account_type
          },
          success: function(data) {
            if (data.status != false) {
              table.ajax.reload();
              swal(data.message, {
                button: false,
                icon: "success",
                timer: 1000
              });
              $("#detailModal .close").click();
              clearAll();
            }
          },
          error: function(error) {
            var err = eval("(" + error.responseText + ")");
            var array_1 = $.map(err, function(value, index) {
              return [value];
            });
            var array_2 = $.map(array_1, function(value, index) {
              return [value];
            });
            var message = JSON.stringify(array_2);
            swal(message, {
              button: false,
              icon: "error",
              timer: 1000
            });
          }
        });
      })
    });
  </script>

@endpush
@extends('master', ['profile_content'=>true])
 
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

<?php 
    use Yajra\Datatables\Datatables; 
    use App\Model\User\User;

    // get user auth
    $user = Auth::user();
?>

<form method="post" action="{{ route('update-profile') }}" enctype="multipart/form-data">

    @csrf
    <div class="form-group">
      <label>Nama Lengkap</label>
      <input type="text" value="{{ $data_user->full_name }}" class="form-control" value="" name="full_name">
      @if ($errors->has('full_name'))
          <div class="error"><p style="color: red"><span>&#42;</span> {{ $errors->first('full_name') }}</p></div>
      @endif
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="text" value="{{ $data_user->email }}" class="form-control" value="" name="email">
      @if ($errors->has('email'))
          <div class="error"><p style="color: red"><span>&#42;</span> {{ $errors->first('email') }}</p></div>
      @endif
    </div>

    @if($user->account_type == User::ACCOUNT_TYPE_SISWA)
    <div class="form-group">
      <label>Angkatan</label>
      <input disabled="true" type="text" value="{{ $data_user->angkatan }}" class="form-control" value="" name="agkatan">
    </div>
    <div class="form-group">
      <label>Kelas</label>
      <input disabled="true" type="text" value="{{ $data_user->kelas }}" class="form-control" value="" name="kelas">
    </div>
    @endif

    <input type="hidden" value="{{ $data_user->id }}" class="form-control" value="" name="iduser">
    <div class="form-group">
      <button type="submit" class="ui big primary button"> UPDATE </button>
      <button type="button" id="change_pass" class="ui big inverted red button right floated"> UBAH PASSWORD </button>
    </div>
@endsection

@section('profile_picture')

<div style="text-align: center; height: 100%; width: 100%">
    <img src="{{ url('asset_user/'.$data_user->account_type.'/'.$data_user->full_name.'/'.$data_user->profile_picture) }}" class="img-thumbnail" id="profile_pic" style="object-fit: cover; height: 200px; width: 200px"/> 
</div>
<div style="text-align: center; padding-top: 10px">
  @if ($data_user->profile_picture != null)
    <button type="button" class="ui big inverted red button" id="delete_image">
        <span class="glyphicon glyphicon-trash"></span>
    </button>
  @else

  <input type="file" name="file" id="file" class="inputfile" accept="image/x-png,image/gif,image/jpeg"/>
  <label for="file"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Pilih Gambar</label>

  <h6> Gambar Max. 2 MB </h6>
  
  @endif
  

</div>


@if ($errors->has('file'))
    <div class="error"><p style="color: red"><span>&#42;</span> {{ $errors->first('file') }}</p></div>
@endif

</form>

@endsection

@section('modal')

<div class="modal fade" id="passwordModal" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <p class="modal-title">Ubah Password</p>
      </div>
      <div class="modal-body">
        
        <div class="form-group col-md-12">
          <label>Password Lama</label>
          <input type="password" class="form-control" value="" id="old_password" name="old_password">
        </div>

        <div class="form-group col-md-6">
          <label>Password</label>
          <input type="password" class="form-control" value="" id="password" name="password">
        </div>

        <div class="form-group col-md-6">
          <label>Re Password</label>
          <input type="password" class="form-control" value="" id="password_confirmation" name="password_confirmation">
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
        <button type="button" id="update_password" class="ui big primary button">Update</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')

<script type="text/javascript">

var idiqro;
var table;


$( document ).ready(function() {

  $("#file").change(function() {
    
    var size = this.files[0].size;
  
    if(size >= 2000587)
    {
      swal('Ukuran file maksimal 2 MB', { button:false, icon: "error", timer: 1000});
      return false;
    }

    readURL(this);

  });

  $('#delete_image').click(function() { 
    // Delete imagee With ajax

    swal({
      title: "Ingin menghapus foto profile ?",
      text: "",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          type:'POST',
          url: base_url + '/profile/delete-image',
          data:
          {
            "_token": "{{ csrf_token() }}",
          },
          success:function(data) {
            if(data.status != false)
            {
              swal(data.message, { button:false, icon: "success", timer: 1000});
            }
            else
            {
              swal(data.message, { button:false, icon: "error", timer: 1000});
            }
            location.reload();
          },
          error: function(error) {
            swal('Error pada sistem', { button:false, icon: "error", timer: 1000});
          }
        }); 
      }
    });
  })

  $('#change_pass').click(function() { 
    $('#passwordModal').modal('toggle');
  })

  $('#update_password').click(function() { 

    var old_password = $('#old_password').val();
    var password = $('#password').val();
    var password_confirmation = $('#password_confirmation').val();

    $.ajax({
        type:'POST',
        url: base_url + '/profile/update-password',
        data:
        {
          "_token": "{{ csrf_token() }}",
          password : password,
          password_confirmation : password_confirmation,
          old_password : old_password
        },
        success:function(data) {
          if(data.status != false)
          {
            swal(data.message, { button:false, icon: "success", timer: 1000});
            $("#passwordModal .close").click()
          }
          else
          {
            swal(data.message, { button:false, icon: "error", timer: 1000});
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
          swal(message, { button:false, icon: "error", timer: 1000});
        }
    });
  })
});

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#profile_pic').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}

</script>

@endpush
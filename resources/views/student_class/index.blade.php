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

  <?php 
    use Yajra\Datatables\Datatables; 
    use App\Model\User\User;

    // get user auth
    $user = Auth::user();
  ?>

  @if($user->account_type == User::ACCOUNT_TYPE_CREATOR || $user->account_type == User::ACCOUNT_TYPE_ADMIN || $user->account_type == User::ACCOUNT_TYPE_TEACHER)
    <div style="padding-bottom: 20px">
      <a  href="{{ route('create-student-class') }}" type="button" class="ui huge inverted primary button"> TAMBAH </a>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered data-table display nowrap" style="width:100%">
          <thead>
              <tr>
                  <th style="text-align: center">Kelas</th>
                  <th style="text-align: center">Guru</th>
                  <th style="text-align: center">Angkatan</th>
                  <th style="text-align: center">Kelas</th>
                  <th style="text-align: center">Token</th>
                  <th style="text-align: center; width: 50px">Action</th>
              </tr>
          </thead>
          <tbody>
          </tbody>
      </table>
    </div>
    <br>

    <fieldset>
    <legend>List Kelas</legend>
    <div id="card-list1" class="ui three stackable cards">
      @foreach($data_kelas as $dk)
        <a id="customCards" class="ui card" href="{{ route('list-student-class', ['id_kelas'=>$dk->id]) }}">
          <div class="content">
            <div class="header" style="padding: 0px">
              {{$dk->class_name}}
            </div>
            <div class="meta">
              <span class="category">{{$dk->full_name}}</span>
            </div>
            <div class="description">
              <p>{{$dk->note}}</p>
            </div>
          </div>
          <div class="extra content">
            <div class="right floated author">
              {{$dk->angkatan}}
            </div>
            <div class="left floated author">
              {{$dk->kelas}}
            </div>
          </div>
        </a>
      @endforeach
    </div>
  @endif

  @if($user->account_type == User::ACCOUNT_TYPE_SISWA)
    <form action="{{ route('join-student-class' )}}" method="post">

    @csrf

    <div class="ui raised segment center aligned">
        <h5 class="ui top attached header">
        <div class="ui action huge input">
            <input name="token" type="text" placeholder="Token Kelas">
                <button class="ui primary right attached huge button">
                    Join Kelas
                </button>
            </div>
        </h5>
        <div class="ui bottom attached warning message">
            Hubungi Guru yang bersangkutan untuk Join Kelas!
        </div>
    </div>
    </form>
    <br>

    <fieldset>
    <legend>List Kelas</legend>
    <div class="ui three stackable cards">
      @foreach($data_kelas as $dk)
        @foreach($dk->hasClass as $c)
        <a id="customCards" class="ui card" href="{{ route('list-student-class', ['id_kelas'=>$c->id]) }}" loading="lazy">
          <div class="content">
            <div class="header" style="padding: 0px">{{$c->class_name}}</div>
            <div class="meta">
              <span class="category">{{User::where('id', $c->teacher_id)->value('full_name')}}</span>
            </div>
            <div class="description">
              <p>{{$c->note}}</p>
            </div>
          </div>
          <div class="extra content">
            <div class="right floated author">
              {{$c->angkatan}}
            </div>
            <div class="left floated author">
              {{$c->kelas}}
            </div>
          </div>
        </a>
        @endforeach
      @endforeach
    </div>
  @endif

@endsection

@section('modal')

  <div class="modal fade" id="detailModal" role="dialog" >
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <p class="modal-title">Detail Kelas</p>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Kelas</label>
            <input type="text" class="form-control" value="" name="class_name" id="class_name">
          </div> 
          @if($user->account_type == User::ACCOUNT_TYPE_CREATOR || $user->account_type == User::ACCOUNT_TYPE_ADMIN)
            <div class="form-group">
              <label>Guru</label>
              <?= $guru_option ?>
            </div>
          @endif
          @if($user->account_type == User::ACCOUNT_TYPE_TEACHER)
            <div class="form-group" style="pointer-events: none;">
              <label>Guru</label>
              <?= $guru_option ?>
            </div>
          @endif
          <div class="form-group">
            <label>Angkatan</label>
            <select class="form-control" id="angkatan" name="angkatan" style="width: 100%">
              @foreach ($years as $year)
                  <option value="{{ $year }}"> {{ $year }} </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Kelas</label>
            <select id="kelas" class="form-control" name="kelas">
              <option value="Pemasaran">Pemasaran</option>
              <option value="Pariwisata">Pariwisata</option>
              <option value="Peternakan">Peternakan</option>
            </select>
          </div>
          <div class="form-group">
            <label>Catatan</label>
            <input type="text" class="form-control" value="" name="note" id="note" maxlength="30">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="ui huge red button right floated" id="hapus_action">Hapus</button>
          <button type="button" id="update_data" class="ui huge inverted primary button left floated">Update</button>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
  <link rel="stylesheet" type="text/css" href="<?= URL::to('/'); ?>/layout/assets/css/jquery.dataTables.css">

  <script type="text/javascript" charset="utf8" src="<?= URL::to('/'); ?>/layout/assets/js/jquery.dataTables.js" defer></script>
  <script type="text/javascript">
    var idclass;
    var table;

    function clearAll() {
      $('#class_name').val('');
      $("#guru").val([]).trigger("change");
      $('#angkatan').val('');
      $('#kelas').val('');
      $('#token').val('');
      $('#note').val('');
    }

    function editClass(){
      $('#editClassModal').modal('toggle');
    }
    function btnUbah(id) {
      clearAll();
      callGuru();
      idclass = id;
      $.ajax ({
        type:'POST',
        url: base_url + '/student-class/get-detail',
        data:{idclass:idclass, "_token": "{{ csrf_token() }}",},
        success:function(data) {
            $('#detailModal').modal('toggle');
            $('#class_name').val(data.data.class_name);
            $('#guru').val(data.data.teacher.id).trigger('change');
            $('#angkatan').val(data.data.angkatan);
            $('#kelas').val(data.data.kelas);
            $('#note').val(data.data.note);
        }
      });

      $('#hapus_action').click(function() {
        hapus(idclass);
        $("#detailModal .close").click()
      })

      $('#update_data').click(function() {
          var angkatan = $('#angkatan').val();
          var kelas = $('#kelas').val();
          var teacher_id = $('#guru').val();
          var class_name = $('#class_name').val();
          var note = $('#note').val();

          $.ajax ({
            type:'POST',
            url: base_url + '/student-class/update',
            data:{
                  idclass:idclass, 
                  "_token": "{{ csrf_token() }}",
                  angkatan : angkatan,
                  kelas : kelas,
                  teacher_id : teacher_id,
                  class_name : class_name,
                  note : note
            },
            success:function(data) {
              if(data.status != false) {
                swal(data.message, { button:false, icon: "success", timer: 1000});
                $("#detailModal .close").click();
                setTimeout(function(){
                  location.reload();
                }, 1000); 
              } else {
                swal(data.message, { button:false, icon: "error", timer: 1000});
              }
              table.ajax.reload();
            },
            error: function(error) {
              swal('Terjadi kegagalan, pastikan kelas dan guru diinput.', { button:false, icon: "error", timer: 1000});
              console.log(data);
            }
          });
          
      })
    }

    function callGuru() {
      $('#guru').select2 ({
        allowClear: true,
        ajax: {
          url: base_url + '/student-class/get-user-teacher',
          dataType: 'json',
          data: function(params) {
              return {
                search: params.term
              }
          },
          processResults: function (data, page) {
              return {
                  results: data
              };
          }
        }
      });
    }

    $(function () {
      table = $('.data-table').DataTable ({
          processing: true,
          serverSide: true,
          rowReorder: {
              selector: 'td:nth-child(2)'
          },
          responsive: true,
          ajax: "{{ route('student-class') }}",
          columns: [
              {data: 'class_name', name: 'class_name'},
              {data: 'guru', name: 'guru'},
              {data: 'angkatan', name: 'angkatan'},
              {data: 'kelas', name: 'kelas'},
              {data: 'token', name: 'token'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });
    });

    function btnDel(id) {
      idclass = id;
      hapus(idclass);
    }

    function hapus(idclass) {
      swal({
          title: "Hapus Kelas",
          text: 'Semua data yang terdapat didalam Kelas ini akan dihapus secara permanen', 
          icon: "warning",
          buttons: true,
          dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            type:'POST',
            url: base_url + '/student-class/delete',
            data:{
              idclass:idclass, 
              "_token": "{{ csrf_token() }}",
            },
            success:function(data) {
              if(data.status != false) {
                swal(data.message, { button:false, icon: "success", timer: 1000});
              } else {
                swal(data.message, { button:false, icon: "error", timer: 1000});
              }
              table.ajax.reload();
              setTimeout(function(){
                location.reload();
              }, 1000); 
            },
            error: function(error) {
              swal('Terjadi kegagalan sistem', { button:false, icon: "error", timer: 1000});
            }
          });      
        }
      });
    }
  </script>

@endpush
<?php
  use App\Model\User\User;
  // get user auth
  $user = Auth::user();
?>

<nav class="navbar navbar-default navbar-fixed">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav navbar-left"></ul>
      <ul class="nav navbar-nav navbar-right">
        <li>
          <a href="<?= URL::to('/profile'); ?>">
            <span class="glyphicon glyphicon-user" aria-hidden="true"></span> &nbsp Profile
          </a>
        </li>

        @if($user->account_type == User::ACCOUNT_TYPE_CREATOR || $user->account_type == User::ACCOUNT_TYPE_ADMIN)
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <p>
              <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> &nbspPengaturan
              <b class="caret"></b>
            </p>
          </a>
          <ul class="dropdown-menu">
            @if($user->account_type == User::ACCOUNT_TYPE_CREATOR)
              <li><a href="<?= URL::to('/role'); ?>"><span class="glyphicon glyphicon-certificate" aria-hidden="true"></span>&nbsp Role</a></li>
            @endif
            <li><a href="<?= URL::to('/action-log'); ?>"><span class="glyphicon glyphicon-record" aria-hidden="true"></span>&nbsp Log Sistem </a></li>
          </ul>
        </li>
        @endif

        @if($user->account_type == User::ACCOUNT_TYPE_CREATOR)
          <li>
            <a href="<?= URL::to('/notification'); ?>">
              <span class="glyphicon glyphicon-bell" aria-hidden="true"></span> &nbsp Buat Notifikasi</p>
            </a>
          </li>
        @endif

        @if($user->account_type == User::ACCOUNT_TYPE_ADMIN)
          <li>
            <a href="<?= URL::to('/notification'); ?>">
              <span class="glyphicon glyphicon-bell" aria-hidden="true"></span> &nbsp Buat Notifikasi</p>
            </a>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="glyphicon glyphicon-comment" aria-hidden="true"></span> &nbsp Notifikasi
              <!-- Hitung pesan notifikasi belum terbaca -->
              <?php
                $data_notif   = UserNotification::where('user_id', $user->id)->orderBy('created_at', 'DESC')->limit(5)->get();
                $count_notif  = UserNotification::where('user_id', $user->id)->where('status', UserNotification::STATUS_UNREAD)->get()->count();
              ?>
              @if($count_notif >= 1)
                <span class="notification">{{ $count_notif }}</span>
              @endif
            </a>
            @if($count_notif >= 1)
              <ul class="dropdown-menu">
                @if($data_notif != null)
                  @foreach($data_notif as $notif)
                    <li><a style="@if($notif->status != UserNotification::STATUS_READ) color: blue @endif" onclick="showNotif('{{$notif->notification_id}}')">{{$notif->getNotification->notification_title}}</a></li>
                  @endforeach
                @endif
              </ul>
            @endif
          </li>
        @endif

        @if($user->account_type != User::ACCOUNT_TYPE_CREATOR && $user->account_type != User::ACCOUNT_TYPE_ADMIN)
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="glyphicon glyphicon-comment" aria-hidden="true"></span> &nbsp Notifikasi
              <!-- Hitung pesan notifikasi belum terbaca -->
              <?php
                $data_notif   = UserNotification::where('user_id', $user->id)->orderBy('created_at', 'DESC')->limit(5)->get();
                $count_notif  = UserNotification::where('user_id', $user->id)->where('status', UserNotification::STATUS_UNREAD)->get()->count();
              ?>
              @if($count_notif >= 1)
              <span class="notification">{{ $count_notif }}</span>
              @endif
            </a>
            @if($count_notif >= 1)
              <ul class="dropdown-menu">
                @if($data_notif != null)
                  @foreach($data_notif as $notif)
                    <li><a style="@if($notif->status != UserNotification::STATUS_READ) color: blue; cursor: pointer @endif" onclick="showNotif('{{$notif->notification_id}}')">{{$notif->getNotification->notification_title}}</a></li>
                  @endforeach
                @endif
              </ul>
            @endif
          </li>
        @endif

        <li>
          <a href="<?= URL::to('/'); ?>/auth/logout">
            <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> &nbsp Logout
          </a>
        </li>
        <li class="separator hidden-lg"></li>
      </ul>
    </div>
  </div>
</nav>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="<?= URL::to('/'); ?>/layout/assets/img/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>ATM Classroom</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <!-- Semantic UI -->
    <link rel="stylesheet" href="<?= URL::to('/'); ?>/layout/assets/css/semantic.min.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?= URL::to('/'); ?>/layout/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= URL::to('/'); ?>/layout/assets/css/bootstrap-theme.min.css">

    <!--   Core JS Files   -->
    <script src="<?= URL::to('/'); ?>/layout/assets/js/jquery.3.2.1.min.js" type="text/javascript"></script>
    <script src="<?= URL::to('/'); ?>/layout/assets/js/bootstrap.min.js" type="text/javascript"></script>

    <!-- Date Picker -->
    <script type="text/javascript" src="<?= URL::to('/'); ?>/layout/assets/js/moment.min.js"></script>
    <script type="text/javascript" src="<?= URL::to('/'); ?>/layout/assets/js/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?= URL::to('/'); ?>/layout/assets/css/daterangepicker.css" />

    <!-- Sweet Alert -->
    <script src="<?= URL::to('/'); ?>/layout/assets/js/sweetalert.min.js"></script>

    <!-- DataTable -->
    <link rel="stylesheet" type="text/css" href="<?= URL::to('/'); ?>/layout/assets/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="<?= URL::to('/'); ?>/layout/assets/js/jquery.dataTables.js" defer></script>


    <link href="<?= URL::to('/'); ?>/layout/assets/css/select2.min.css" rel="stylesheet" />
    <script src="<?= URL::to('/'); ?>/layout/assets/js/select2.min.js"></script>

    <!-- Bootstrap core CSS     -->
    <link href="<?= URL::to('/'); ?>/layout/assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="<?= URL::to('/'); ?>/layout/assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="<?= URL::to('/'); ?>/layout/assets/css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet"/>

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= URL::to('/'); ?>/layout/assets/css/main.css">

    <!--     Fonts and icons     -->
    <link href="<?= URL::to('/'); ?>/layout/assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?= URL::to('/'); ?>/layout/assets/css/robotofont.css" rel='stylesheet' type='text/css'>
    <link href="<?= URL::to('/'); ?>/layout/assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
    <link href="<?= URL::to('/'); ?>/layout/assets/css/additional_css.css" rel="stylesheet" />
    <link href="<?= URL::to('/'); ?>/layout/assets/css/icon.css" rel="stylesheet">
    <link href="<?= URL::to('/'); ?>/layout/assets/css/comment.css" rel="stylesheet">

    @stack('scripts')
</head>
<body>

<div class="wrapper">
    <div class="sidebar" data-color="blue">
        @include('include.navbar')
    </div>

    <div class="main-panel">
        @include('include.head')

        <!-- Content -->
            @if (isset($profile_content))
                @if ($profile_content != false)
                    @include('content_profile')
                @endif
            @else
                @include('content_master')
            @endif
        <!-- End Content -->

        <footer class="footer">
            @include('include.footer')
        </footer>
    </div>
</div>

@yield('modal')

</body>
	<!-- Charts Plugin -->
    <script src="<?= URL::to('/'); ?>/layout/assets/js/chartist.min.js"></script>

    <!-- Notifications Plugin    -->
    <script src="<?= URL::to('/'); ?>/layout/assets/js/bootstrap-notify.js"></script>

    <script src="<?= URL::to('/'); ?>/layout/assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>

	<script type="text/javascript">
    	
        var base_url = {!! json_encode(url('/')) !!}

        function showNotif(idnotification) {

            $('#detailNotification').modal('toggle');

                $.ajax({
                    type:'POST',
                    url: base_url + '/notification/get-detail',
                    data: { idnotification:idnotification, "_token": "{{ csrf_token() }}", },
                        success:function(data) {
                            $('#notification_title').val(data.data.notification_title);
                            $('#notification_message').val(data.data.notification_message);
                            $('#date').val(data.data.date);
                    }
                });
        }

	</script>

</html>

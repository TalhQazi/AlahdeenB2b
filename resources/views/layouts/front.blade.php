<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('admin_theme')}}/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>@yield('title') | Emandii</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,600,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <!-- start JQUERY DATEPICKER CSS -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css"></link>
    <!-- Dropify -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">
    <!-- CSS Files -->
    <link href="{{asset('admin_theme')}}/assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
    <style>
        .h1,
        .h2,
        .h3,
        .h4,
        body,
        p,
        a,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: Quicksand;
            font-weight: 400;
        }
        .table thead th {
            border-top-width: 1px !important;
            color: #000 !important;
            font-weight: bold !important;
            font-size: 14px !important;
        }
        .table>tbody>tr>td {
            font-weight: 500;
        }
        .card-title,
        .error {
            font-weight: bold;
        }
        .form-control {
            border: 1px solid #e7e7e7;
            border-radius: 0px;
            padding: 4px 10px;
        }
        input.form-control,
        textarea.form-control,
        select.form-control{
            background-image: none !important;
        }
        input.form-control,
        select.form-control {
            height: calc(2.4375rem + 1px) !important;
        }
        label,
        .bmd-label-static{
            font-weight: bold;
            text-transform: capitalize;
            margin-bottom: 3px;
            color: #000;
        }
        .select2-hidden-accessible{
            display: none;
        }
        .select2-container,
        .select2-container--default .select2-selection--single,
        .select2-container .select2-selection--single,
        .select2-container--default .select2-selection--single .select2-selection__arrow{
            height: calc(2.4375rem + 1px) !important;
            border-radius: 0px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered{
            line-height: calc(2.4375rem + 1px) !important;
        }
        .login_row
        {
          height: 100vh;
          align-content: center;
        }
        .bmd-form-group [class^='bmd-label'], .bmd-form-group [class*=' bmd-label'] 
        {
            position: initial;
        }
        .card .card-header-warning .card-icon, .card .card-header-warning .card-text, .card .card-header-warning:not(.card-header-icon):not(.card-header-text), .card.bg-warning, .card.card-rotate.bg-warning .front, .card.card-rotate.bg-warning .back {
            background: linear-gradient(60deg, #f16001, #f16001) !important;
        }
        .btn.btn-primary:focus, .btn.btn-primary.focus, .btn.btn-primary:hover {
            color: #fff;
            background-color: #001e92;
            border-color: #001e92;
        }
        .btn.btn-primary {
            color: #fff;
            background-color: #001e92;
            border-color: #001e92;
            font-weight:bolder;
            box-shadow: 0 2px 2px 0 rgb(0 30 146 / 47%), 0 3px 1px -2px #001e9296, 0 1px 5px 0 #001e928c;
        }
        .error
        {
            background: #fb5a5a;
            color: white !important;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset(('common')) }}/css/custom.css">
    @yield('css')
</head>

<body class="">
    @yield('content')

    <!--  -->
    <!--   Core JS Files   -->
    <script src="{{asset('admin_theme')}}/assets/js/core/jquery.min.js"></script>
    <script src="{{asset('admin_theme')}}/assets/js/core/popper.min.js"></script>
    <script src="{{asset('admin_theme')}}/assets/js/core/bootstrap-material-design.min.js"></script>
    <script src="{{asset('admin_theme')}}/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <!-- Plugin for the momentJs  -->
    <script src="{{asset('admin_theme')}}/assets/js/plugins/moment.min.js"></script>
    <!--  Plugin for Sweet Alert -->
    <script src="{{asset('admin_theme')}}/assets/js/plugins/sweetalert2.js"></script>
    <!-- Forms Validations Plugin -->
    <script src="{{asset('admin_theme')}}/assets/js/plugins/jquery.validate.min.js"></script>
    <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
    <script src="{{asset('admin_theme')}}/assets/js/plugins/jquery.bootstrap-wizard.js"></script>
    <!--    Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
    <script src="{{asset('admin_theme')}}/assets/js/plugins/bootstrap-selectpicker.js"></script>
    <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
    <script src="{{asset('admin_theme')}}/assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
    <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
    <script src="{{asset('admin_theme')}}/assets/js/plugins/jquery.dataTables.min.js"></script>
    <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
    <script src="{{asset('admin_theme')}}/assets/js/plugins/jasny-bootstrap.min.js"></script>
    <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
    <!-- Library for adding dinamically elements -->
    <script src="{{asset('admin_theme')}}/assets/js/plugins/arrive.min.js"></script>
    <!--  Notifications Plugin    -->
    <script src="{{asset('admin_theme')}}/assets/js/plugins/bootstrap-notify.js"></script>
    <!--  Select2 Plugin    -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <!--  CKEditor Plugin    -->
    <script src="https://cdn.ckeditor.com/4.15.1/standard/ckeditor.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{asset('admin_theme')}}/assets/js/material-dashboard.js?v=2.1.2" type="text/javascript"></script>
    <!-- Dropify -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <!-- Validator Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
    <!--  -->
    
    <script src="{{ asset(('common')) }}/js/custom.js"></script>



    <script>
        @if(session('success'))
            md.showNotification('success', "{{ session('success') }}");
        @elseif(session('error'))
            md.showNotification('danger', "{{ session('error') }}");
        @endif
        var data = <?php echo json_encode(session()->getOldInput()) ?>;
        mapDataToFields(data);
        startDatePicker();
</script>
    @yield('js')
</body>

</html>


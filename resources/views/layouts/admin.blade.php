@php

$setting = cache('settings');

@endphp
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description"
    content="LetStart Admin is a full featured, multipurpose, premium bootstrap admin template built with Bootstrap 4 Framework, HTML5, CSS and JQuery.">
  <meta name="keywords"
    content="admin, panels, dashboard, admin panel, multipurpose, bootstrap, bootstrap4, all type of dashboards">
  <meta name="author" content="MatrrDigital">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title> Admin Panel  {{@$title? " | ".$title : ""}}</title>
  <link rel="shortcut icon" href="{{url('/public/image/setting/'.$setting->fav_icon)}}" type="image/x-icon" />
   @stack('css')
  <!-- ================== BEGIN PAGE LEVEL CSS START ================== -->
   <link rel="stylesheet" href="{{url('/')}}/public/admin_assets/css/icons.css" />
    <link href="{{url('/')}}/public/admin_assets/libs/mohithg-switchery/switchery.min.css" rel="stylesheet" type="text/css" />
    <link href="{{url('/')}}/public/admin_assets/libs/summernote/summernote-bs4.min.css" rel="stylesheet" />
   <!-- ================== BEGIN PAGE LEVEL END ================== -->
   <!-- ================== Plugins CSS  ================== -->
   
  <link href="{{url('/')}}/public/admin_assets/libs/datatables/css/dataTables.bootstrap4.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
   <!-- ================== Plugins CSS ================== -->
   <!-- ================== BEGIN APP CSS  ================== -->
   <link rel="stylesheet" href="{{url('/')}}/public/admin_assets/css/bootstrap.css" />
   <link rel="stylesheet" href="{{url('/')}}/public/admin_assets/css/styles.css" />
   <!-- ================== END APP CSS ================== -->

   <style>
    .select2-container .select2-selection--multiple .select2-selection__rendered {
      display: flex;
      overflow-x: auto;
    }

    input.btn-primary {
      float: right;
    }
    
    label, th {
      text-transform: capitalize;
    }

    img {
      width: 100px!important;
      object-fit: cover;
    }
   </style>
</head>

<body>
  <div class="backhome">
    <a href="{{ url('/')}}" class="avatar avatar-sm bg-primary text-white"><i class="bx bx-home-alt fs-sm"></i></a>
  </div>

  @if(@Auth::user()->id)

  <!-- Begin Page -->

   <div class="page-wrapper">
  @include('layouts.partials.admin-header')

  @include('layouts.partials.admin-siderbar')
  <div class="main-content">
    @yield('main-content')
</div>
  </div>
  @else
    @yield('main-content')
  @endif



  <!-- Page End -->
   <!-- ================== BEGIN BASE JS ================== -->
   <script src="{{url('/')}}/public/admin_assets/js/vendor.min.js"></script>
   <!-- ================== END BASE JS ================== -->
   <!-- ================== BEGIN PAGE LEVEL JS ================== -->
   <script src="{{url('/')}}/public/admin_assets/libs/datatables/js/jquery.dataTables.min.js"></script>
   <script src="{{url('/')}}/public/admin_assets/libs/datatables/js/dataTables.bootstrap4.min.js"></script>
   <script src="{{url('/')}}/public/admin_assets/js/app.js"></script>
  
   <script src="{{url('/')}}/public/admin_assets/libs/mohithg-switchery/switchery.min.js"></script>
   <script src="{{url('/')}}/public/admin_assets/libs/summernote/summernote-bs4.min.js"></script>

   <script type="text/javascript">
    var elems = Array.prototype.slice.call(document.querySelectorAll('.toggle-class'));

      elems.forEach(function(html) {
        var switchery = new Switchery(html);
      });

      if($("#summernote-basic").length > 0){
        $("#summernote-basic").summernote({placeholder:"Write something...",height:230});
      }
      if($("#datatable").length > 0){
        $("#datatable").dataTable();
     }

     $(document).ready(function() {
      if($(".select2").length > 0){
        
        $('.select2').select2();
      }
    });
   </script>
    @stack('scripts')
</body>

</html>
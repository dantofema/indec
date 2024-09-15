<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('includes.head_sidebar')
</head>
<body>
<div id="app" class="wrapper">

  <!-- sidebar content -->
  <nav id="sidebar" >
      @include('includes.sidebar')
  </nav>
   <div id=content >
     
     <header >
         @include('includes.header_sidebar')
     </header >
     <div >
       <div id="main" class="justify-content-center">
         <!-- main content -->
             @yield('content')
       </div>
     </div>
     <footer class="justify-content-center">
         @include('includes.footer')
     </footer>
   </div>

</div>
</body>
<script>
$(document).ready(function () {

//    $("#sidebar").mCustomScrollbar({
//         theme: "minimal"
//    });

    $('#sidebarCollapse').on('click', function () {
        // open or close navbar
        $('#sidebar').toggleClass('active');
        $('#content').toggleClass('active');
        // close dropdowns
        $('.collapse.in').toggleClass('in');
        // and also adjust aria-expanded attributes we use for the open/closed arrows
        // in our CSS
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });
});
</script>
</html>

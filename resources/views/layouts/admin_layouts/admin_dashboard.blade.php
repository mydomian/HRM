<!DOCTYPE html>
<html>
    @include('layouts.admin_layouts.head')
    <body>
        {{--  header part  --}}
        @include('layouts.admin_layouts.header')
        <div class="d-flex align-items-stretch">
        {{--  sidebar part  --}}
        @include('layouts.admin_layouts.sidebar')
        <div class="page-content">
        
        {{--  main content  --}}
        @yield('content')
        {{--  footer part  --}}
        @include('layouts.admin_layouts.footer')
        </div>
        </div>
        {{--  script part  --}}
       @include('layouts.admin_layouts.scripts')
    </body>
</html>


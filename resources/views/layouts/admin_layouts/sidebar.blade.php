<nav id="sidebar">
    <!-- Sidebar Header-->
    <div class="sidebar-header d-flex align-items-center p-4"><img class="avatar shadow-0 img-fluid rounded-circle" src="img/avatar-6.jpg" alt="...">
      <div class="ms-3 title">
        <h1 class="h5 mb-1">Mark Stephen</h1>
        <p class="text-sm text-gray-700 mb-0 lh-1">Web Designer</p>
      </div>
    </div><span class="text-uppercase text-gray-600 text-xs mx-3 px-2 heading mb-2">Main</span>
    <ul class="list-unstyled">

          <li class="sidebar-item {{ (request()->is('admin/dashborad')) ? 'active' : '' }}"><a class="sidebar-link" href="{{ url('/admin/dashborad') }}">
                  <svg class="svg-icon svg-icon-sm svg-icon-heavy">
                    <use xlink:href="#real-estate-1"> </use>
                  </svg><span>Home </span></a></li>

          <li class="sidebar-item {{ (request()->is('admin/packages')) ? 'active' : '' }}"><a class="sidebar-link" href="{{ url('/admin/packages') }}">
                  <svg class="svg-icon svg-icon-sm svg-icon-heavy">
                    <use xlink:href="#portfolio-grid-1"></use>
                  </svg><span>Packages </span></a></li>
          <li class="sidebar-item {{ (request()->is('admin/package-request')) ? 'active' : '' }}"><a class="sidebar-link" href="{{ url('/admin/package-request') }}">
                  <svg class="svg-icon svg-icon-sm svg-icon-heavy">
                    <use xlink:href="#sales-up-1"> </use>
                  </svg><span>Pakcage Request </span></a></li>
        <li class="sidebar-item {{ (request()->is('admin/package-activated-lists')) ? 'active' : '' }}"><a class="sidebar-link" href="{{ url('/admin/package-activated-lists') }}">
        <svg class="svg-icon svg-icon-sm svg-icon-heavy">
            <use xlink:href="#sales-up-1"> </use>
        </svg><span>Pakcage Activated</span></a></li>
        <li class="sidebar-item {{ (request()->is('admin/package-deactivated-lists')) ? 'active' : '' }}"><a class="sidebar-link" href="{{ url('/admin/package-deactivated-lists') }}">
            <svg class="svg-icon svg-icon-sm svg-icon-heavy">
                <use xlink:href="#sales-up-1"> </use>
            </svg><span>Pakcage Deactivated</span></a></li>

            <li class="sidebar-item {{ (request()->is('admin/role-permission')) ? 'active' : '' }}"><a class="sidebar-link" href="{{ url('/admin/role-permission') }}">
                <svg class="svg-icon svg-icon-sm svg-icon-heavy">
                    <use xlink:href="#survey-1"> </use>
                </svg><span>Role Permission</span></a></li>
          <li class="sidebar-item"><a class="sidebar-link" href="#exampledropdownDropdown" data-bs-toggle="collapse">
                  <svg class="svg-icon svg-icon-sm svg-icon-heavy">
                    <use xlink:href="#browser-window-1"> </use>
                  </svg><span>Example dropdown </span></a>
            <ul class="collapse list-unstyled " id="exampledropdownDropdown">
              <li><a class="sidebar-link" href="#">Page</a></li>
              <li><a class="sidebar-link" href="#">Page</a></li>
              <li><a class="sidebar-link" href="#">Page</a></li>
            </ul>
          </li>
    </ul>
  </nav>

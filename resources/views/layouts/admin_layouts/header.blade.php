<header class="header">
    <nav class="navbar navbar-expand-lg py-3 bg-dash-dark-2 border-bottom border-dash-dark-1 z-index-10">
      <div class="search-panel">
        <div class="search-inner d-flex align-items-center justify-content-center">
          <div class="close-btn d-flex align-items-center position-absolute top-0 end-0 me-4 mt-2 cursor-pointer"><span>Close </span>
                <svg class="svg-icon svg-icon-md svg-icon-heavy text-gray-700 mt-1">
                  <use xlink:href="#close-1"> </use>
                </svg>
          </div>
          <div class="row w-100">
            <div class="col-lg-8 mx-auto">
              <form class="px-4" id="searchForm" action="#">
                <div class="input-group position-relative flex-column flex-lg-row flex-nowrap">
                  <input class="form-control shadow-0 bg-none px-0 w-100" type="search" name="search" placeholder="What are you searching for...">
                  <button class="btn btn-link text-gray-600 px-0 text-decoration-none fw-bold cursor-pointer text-center" type="submit">Search</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="container-fluid d-flex align-items-center justify-content-between py-1">
        <div class="navbar-header d-flex align-items-center"><a class="navbar-brand text-uppercase text-reset" href="index.html">
            <div class="brand-text brand-big"><strong class="text-primary">Dark</strong><strong>Admin</strong></div>
            <div class="brand-text brand-sm"><strong class="text-primary">D</strong><strong>A</strong></div></a>
          <button class="sidebar-toggle">
                <svg class="svg-icon svg-icon-sm svg-icon-heavy transform-none">
                  <use xlink:href="#arrow-left-1"> </use>
                </svg>
          </button>
        </div>
        <ul class="list-inline mb-0">
          <li class="list-inline-item"><a class="search-open nav-link px-0" href="#">
                  <svg class="svg-icon svg-icon-xs svg-icon-heavy text-gray-700">
                    <use xlink:href="#find-1"> </use>
                  </svg></a></li>

          <li class="list-inline-item logout px-lg-2"><a class="nav-link text-sm text-reset px-1 px-lg-0" id="logout" href="{{ url('/admin/logout') }}"> <span class="d-none d-sm-inline-block">Logout </span>
                  <svg class="svg-icon svg-icon-xs svg-icon-heavy">
                    <use xlink:href="#disable-1"> </use>
                  </svg></a></li>
        </ul>
      </div>
    </nav>
  </header>

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 bg-slate-900 fixed-start " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand d-flex align-items-center m-0" href="{{ route('sessions.index') }}">
        <span class="font-weight-bold text-lg">MyApotex</span>
      </a>
    </div>
    <div class="collapse navbar-collapse px-4  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
          <x-sidebar.nav-item name="dashboard" route="sessions.index"/>
          <x-sidebar.nav-item name="medicines" route="medicines.index"/>
          <x-sidebar.nav-item name="sales" route="sales.index"/>
          @can('admin')
          <x-sidebar.nav-item name="accounts" route="accounts.index"/>
          @endcan
          <x-sidebar.nav-item name="profile" route="profile.index"/>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('logout') ? ' active' : ''}}"
               onclick="document.querySelector('#logout-form').submit();"
               href="#">
                <x-sidebar.nav-svg name="logout"/>

                <form class="d-none"
                      id="logout-form"
                      method="POST"
                      action="/logout"
                >@csrf
                </form>

                <span class="nav-link-text ms-1">Logout</span>
            </a>
        </li>
      </ul>
    </div>
  </aside>

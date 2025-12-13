<nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item {{ request()->is('admin') ? 'active' : '' }}">
            <a class="nav-link" href="/admin">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item {{ request()->is('events*') ? 'active' : '' }}">
            <a class="nav-link" href="/events">
              <i class="ti-calendar menu-icon"></i>
              <span class="menu-title">Events</span>
            </a>
          </li>
          <li class="nav-item {{ request()->is('visitor*') ? 'active' : '' }}">
            <a class="nav-link" href="/visitor">
              <i class="ti-user menu-icon"></i>
              <span class="menu-title">Visitor</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="icon-layout menu-icon"></i>
              <span class="menu-title">UI Elements</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="pages/ui-features/buttons.html">Buttons</a></li>
                <li class="nav-item"> <a class="nav-link" href="pages/ui-features/dropdowns.html">Dropdowns</a></li>
                <li class="nav-item"> <a class="nav-link" href="pages/ui-features/typography.html">Typography</a></li>
              </ul>
            </div>
          </li>
          @if(auth()->user()->role_id == 1)
          <li class="nav-item {{ request()->is('users*') ? 'active' : '' }}">
            <a class="nav-link" href="/users">
              <i class="ti-user menu-icon"></i>
              <span class="menu-title">Users</span>
            </a>
          </li>
          @endif
          <!-- <li class="nav-item">
            <a class="nav-link" href="/logout">
              <i class="ti-power-off menu-icon"></i>
              <span class="menu-title">Log Out</span>
            </a>
          </li> -->
        </ul>
      </nav>
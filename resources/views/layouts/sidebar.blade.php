  <!-- Menu -->
  <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
      <div class="app-brand demo">
          <a href="{{ url('/dashboard') }}" class="app-brand-link">
              <span class="app-brand-logo demo">
                  <img src="{{ url('img/logo-imip.png') }}" width="50" alt="">
                  {{-- <p>CORE IMIP</p> --}}
              </span>
          </a>
          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
          </a>
          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
          </a>
      </div>

      <div class="menu-inner-shadow"></div>

      <ul class="menu-inner py-1">
          <!-- Dashboard -->
          <li class="menu-item {{ Request::is('dashboard') ? 'active' : '' }}">
              <a href="{{ url('/dashboard') }}" class="menu-link">
                  <i class="menu-icon tf-icons bx bx-home-circle"></i>
                  <div data-i18n="Analytics">Dashboard</div>
              </a>
          </li>

          @php
              $menus = menus();
          @endphp

          <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Menus</span>
          </li>
          @foreach ($menus as $menu)
              @if (isset($menu['children']) and $menu['child'] == 'Y')
                  <li
                      class="menu-item {{ Request::is(strtolower(str_replace(' ', '', $menu['menu'])) . '*') ? 'active open' : '' }} ">
                      <a href="javascript:void(0);" class="menu-link menu-toggle">
                          <span class="menu-icon tf-icons"><i class="{{ $menu['icon'] }}"></i></span>
                          <span class="sidenav-link-title">{{ $menu['menu'] }}</span>
                      </a>{{ "\n" }}
                      <ul
                          class="menu-sub {{ Request::is(strtolower(str_replace(' ', '', $menu['menu'])) . '*') ? 'display: block;' : '' }}">
                          @foreach ($menu['children'] as $child)
                              <li
                                  class="menu-item {{ Request::is(strtolower(str_replace(' ', '', $menu['menu'])) . '/' . strtolower($child['menu'])) ? 'active' : '' }} ">
                                  <a href="{{ url($child['route_name']) }}" class="menu-link">
                                      <span class="sidenav-link-title">{{ $child['menu'] }}</span>
                                  </a>
                              </li>
                          @endforeach
                      </ul>
                  </li>
              @else
                  <li
                      class="menu-item {{ Request::is(strtolower(str_replace(' ', '', $menu['menu']))) ? 'active' : '' }}">
                      <a href="{{ url($menu['route_name']) }}" class="menu-link">
                          <i class="menu-icon tf-icons {{ $menu['icon'] }}"></i>
                          <div data-i18n="Analytics">{{ $menu['menu'] }}</div>
                      </a>
                  </li>
              @endif
          @endforeach

      </ul>
  </aside>
  <!-- / Menu -->

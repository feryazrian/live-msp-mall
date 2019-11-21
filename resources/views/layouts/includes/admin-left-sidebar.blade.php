 <!-- BEGIN DEFAULT SIDEBAR -->
 <div class="ks-column ks-sidebar ks-danger">
    <div class="ks-wrapper ks-sidebar-wrapper">
        <ul class="nav nav-pills nav-stacked">
            {{-- @role('super-admin')
                @include('layouts.includes.menus.super-admin-menu')
            @endRole
            @role('admin')
                @include('layouts.includes.menus.admin-menu')
            @endRole --}}
            @if (Auth::user()->role_id == 1)
                @include('layouts.includes.menus.admin-menu')
            @elseif (Auth::user()->role_id == 2)
                @include('layouts.includes.menus.sosmed-admin')
            @elseif (Auth::user()->role_id == 3)
                @include('layouts.includes.menus.finance-admin')
            @elseif (Auth::user()->role_id == 4 || Auth::user()->role_id == 5)
                @include('layouts.includes.menus.super-admin-menu')
            @endif
        </ul>
        <div class="ks-sidebar-extras-block">
            <div class="ks-extras-block-item">
                <div class="ks-description">
                    <span class="ks-text">Waktu Sekarang (WIB)</span>
                </div>
                <div class="ks-name datetime-realtime"></div>
            </div>
            <div class="ks-sidebar-copyright">© {{ date('Y').' '.config('app.name') }}</div>
        </div>
    </div>
</div>
<!-- END DEFAULT SIDEBAR -->

    <div id="sidebar" class="sidebar responsive ace-save-state">

        <ul class="nav nav-list">
            <li class="{{ (strpos(request()->route()->getName(), '/') !== false) ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="menu-icon fa fa-tachometer"></i>
                    <span class="menu-text">Dashboard </span>
                </a>

                <b class="arrow"></b>
            </li>

            <li class="{{ (strpos(request()->route()->getName(), 'user') !== false) ? 'active' : '' }}">
                <a href="{{ route('admin.user') }}">
                    <i class="menu-icon fa fa-list-alt"></i>
                    <span class="menu-text">Users</span>
                </a>

                <b class="arrow"></b>
            </li>
        </ul><!-- /.nav-list -->

        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
            <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
        </div>
    </div>
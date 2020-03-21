@includeIf('admin.partials.head')
@includeIf('admin.partials.navbar')
@includeIf('admin.partials.header')
    @includeIf('admin.partials.sidebar')
    <div class="main-content">
        <div class="main-content-inner">
            @yield('content')
        </div>
    </div>
@includeIf('admin.partials.footer')
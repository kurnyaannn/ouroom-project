<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 padding-r-0">
                @yield('alert')
                <div class="card">
                    <div class="header">   
                        @yield('title')
                    </div>
                    <div class="content">
                        {{ Breadcrumbs::render() }}
                        @yield('content')
                    </div>
                </div>
            </div>
            <div class="col-md-4 padding-r-0">
                <div class="card">
                    <div class="header">   
                        @yield('title')
                    </div>
                    <div class="content">
                        @yield('profile_picture')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
            <a href="{{ route('profile.show') }}" class="d-block">{{ Auth::user()->name }}</a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
            data-accordion="false">
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link">
                    <i class="nav-icon fas fa-th"></i>
                    <p>
                        {{ __('Dashboard') }}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('suppliers.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-th"></i>
                    <p>
                        {{ __('Supplier') }}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('categories.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-th"></i>
                    <p>
                        {{ __('Category Product') }}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('products.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-box"></i>
                    <p>
                        {{ __('Product') }}
                    </p>
                </a>
            </li>

            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
            <li class="nav-item">
                <a href="{{ route('stockRequests.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-box"></i>
                    <p>
                        {{ __('Stock Requests') }}
                    </p>
                </a>
            </li>
           

            <li class="nav-item">
                <a href="{{ route('check-stock') }}" class="nav-link">
                    <i class="nav-icon fas fa-box"></i>
                    <p>
                        {{ __('Check Stok Offname') }}
                    </p>
                </a>
            </li>
            @endif

            <li class="nav-item">
                <a href="{{ route('product.usage.form') }}" class="nav-link">
                    <i class="nav-icon fas fa-box"></i>
                    <p>
                        {{ __('Usage Product') }}
                    </p>
                </a>
            </li>

            @if (auth()->user()->hasRole('admin'))
            <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        {{ __('Users') }}
                    </p>
                </a>
            </li>
            @endif

            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-circle nav-icon"></i>
                    <p>
                        Report
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('suppliers.exportPDF') }}" class="nav-link" target="_blank">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Supplier Report</p>
                        </a>
                    </li>
                </ul>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('categories.exportPDF') }}" class="nav-link" target="_blank">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Category Product Report</p>
                        </a>
                    </li>
                </ul>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('products.exportPDF') }}" class="nav-link" target="_blank">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Product Report</p>
                        </a>
                    </li>
                </ul>
                @endif
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
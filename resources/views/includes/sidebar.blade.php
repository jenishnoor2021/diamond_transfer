<div data-simplebar class="h-100">

    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <ul class="metismenu list-unstyled" id="side-menu">
            <li class="menu-title" key="t-menu">Menu</li>

            <li>
                <a href="/admin/dashboard" class="waves-effect">
                    <i class="bx bx-home-circle"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            @if(Session::get('user')['role'] == 'Admin')
            <li>
                <a href="/admin/diamonds" class="waves-effect">
                    <i class="bx bx-diamond"></i>
                    <span>Diamonds</span>
                </a>
            </li>
            @endif

            <li>
                <a href="javascript:void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-diamond"></i>
                    <span>Diamonds Transfer</span>
                </a>

                <ul class="sub-menu">

                    @if(Session::get('user')['role'] == 'Admin')
                    <li>
                        <a href="{{ route('admin.diamond.transfer.index',['type'=>'surat_to_mumbai']) }}">
                            <i class="bx bx-right-arrow-alt"></i> Surat to Mumbai
                        </a>
                    </li>
                    @endif

                    <li>
                        <a href="{{ route('admin.diamond.transfer.index',['type'=>'mumbai_to_surat']) }}">
                            <i class="bx bx-left-arrow-alt"></i> Mumbai to Surat
                        </a>
                    </li>

                </ul>
            </li>

            <li>
                <a href="{{ route('admin.broker.index') }}" class="waves-effect">
                    <i class="bx bx-user"></i>
                    <span>Brokers</span>
                </a>
            </li>

            <li>
                <a href="javascript:void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-user"></i>
                    <span>Broker Issue</span>
                </a>

                <ul class="sub-menu">

                    @if(Session::get('user')['role'] == 'Admin')
                    <li>
                        <a href="{{ route('admin.broker.memo.create','surat') }}">
                            <i class="bx bx-plus"></i> Issue Surat Broker
                        </a>
                    </li>
                    @endif

                    <li>
                        <a href="{{ route('admin.broker.memo.create','mumbai') }}">
                            <i class="bx bx-plus"></i> Issue Mumbai Broker
                        </a>
                    </li>

                    @if(Session::get('user')['role'] == 'Admin')
                    <li>
                        <a href="{{ route('admin.broker.memo.index','surat') }}">
                            <i class="bx bx-list-ul"></i> Surat Memo List
                        </a>
                    </li>
                    @endif

                    <li>
                        <a href="{{ route('admin.broker.memo.index','mumbai') }}">
                            <i class="bx bx-list-ul"></i> Mumbai Memo List
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="javascript:void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-cog"></i>
                    <span>Return / Sale</span>
                </a>
                <ul class="sub-menu">
                    @if(Session::get('user')['role'] == 'Admin')
                    <li>
                        <a href="{{ route('admin.broker.memo.return','surat') }}">
                            <i class="bx bx-buildings"></i> Surat Broker
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('admin.broker.memo.return','mumbai') }}">
                            <i class="bx bx-buildings"></i> Mumbai Broker
                        </a>
                    </li>
                    @if(Session::get('user')['role'] == 'Admin')
                    <li>
                        <a href="{{route('admin.owner.sale',['type'=>'surat'])}}">
                            <i class="bx bx-buildings"></i> Surat Owner sale
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="{{route('admin.owner.sale',['type'=>'mumbai'])}}">
                            <i class="bx bx-buildings"></i> Mumbai Owner sale
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="/admin/sell-diamonds" class="waves-effect">
                    <i class="bx bx-diamond"></i>
                    <span>Sold Diamonds</span>
                </a>
            </li>

            <li>
                <a href="/admin/all-diamonds" class="waves-effect">
                    <i class="bx bx-diamond"></i>
                    <span>All Diamonds</span>
                </a>
            </li>

            @if(Session::get('user')['role'] == 'Admin')
            <li>
                <a href="/admin/add-invoice" class="waves-effect">
                    <i class="bx bx-diamond"></i>
                    <span>Invoice Generate</span>
                </a>
            </li>

            <li>
                <a href="/admin/invoices" class="waves-effect">
                    <i class="bx bx-diamond"></i>
                    <span>Invoices</span>
                </a>
            </li>
            @endif

            @if(Session::get('user')['role'] == 'Admin')
            <li>
                <a href="javascript:void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-cog"></i>
                    <span>Setting</span>
                </a>
                <ul class="sub-menu">
                    <!-- <li>
                        <a href="/admin/designation">
                            <i class="bx bx-id-card"></i> Designation
                        </a>
                    </li> -->
                    <li>
                        <a href="/admin/company">
                            <i class="bx bx-buildings"></i> Company Detail
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="javascript:void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-list-ul"></i>
                    <span>Dynamic Dropdown</span>
                </a>
                <ul class="sub-menu">
                    <li><a href="/admin/color"><i class="bx bx-palette"></i> Color</a></li>
                    <li><a href="/admin/shape"><i class="bx bx-shape-circle"></i> Shape</a></li>
                    <li><a href="/admin/clarity"><i class="bx bx-bullseye"></i> Clarity</a></li>
                    <li><a href="/admin/cut"><i class="bx bx-cut"></i> Cut</a></li>
                    <li><a href="/admin/polish"><i class="bx bx-brush"></i> Polish</a></li>
                    <li><a href="/admin/symmetry"><i class="bx bx-align-middle"></i> Symmetry</a></li>
                </ul>
            </li>
            @endif


        </ul>
    </div>
    <!-- Sidebar -->
</div>
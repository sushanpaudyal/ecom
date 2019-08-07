<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
    <ul>
        <li class="active"><a href="{{route('admin.dashboard')}}"><i class="icon icon-home"></i> <span>Dashboard</span></a> </li>

        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Categories</span> </a>
            <ul>
                <li><a href="{{route('addCategory')}}">Add Category</a></li>
                <li><a href="{{route('viewCategories')}}">View Category</a></li>
            </ul>
        </li>

        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Products</span> </a>
            <ul>
                <li><a href="{{route('addProduct')}}">Add Product</a></li>
                <li><a href="{{route('viewProducts')}}">View Products</a></li>
            </ul>
        </li>

        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Coupons</span> </a>
            <ul>
                <li><a href="{{route('addCoupon')}}">Add Coupons</a></li>
                <li><a href="{{route('viewProducts')}}">View Coupons</a></li>
            </ul>
        </li>

    </ul>
</div>
<!--sidebar-menu-->
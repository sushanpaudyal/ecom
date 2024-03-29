<?php
   $url = url()->current();
?>

<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
    <ul>
        <li
        <?php if(preg_match("/dashboard/i", $url)) { echo 'class="active"'; } ?>    >
            <a href="{{url('admin/dashboard')}}"><i class="icon icon-home"></i> <span>Dashboard</span></a> </li>



        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Categories</span> </a>
            <ul <?php if(preg_match("/categor/i", $url)) { echo 'style="display:block;"';} ?>>
                <li <?php if(preg_match("/add-category/i", $url)) { echo 'class="active"';} ?>><a href="{{route('addCategory')}}">Add Category</a></li>
                <li <?php if(preg_match("/view-categories/i", $url)) { echo 'class="active"';} ?>><a href="{{route('viewCategories')}}">View Category</a></li>
            </ul>
        </li>


        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Products</span> </a>
            <ul <?php if(preg_match("/product/i", $url)) { echo 'style="display:block;"';} ?>>
                <li <?php if(preg_match("/add-product/i", $url)) { echo 'class="active"';} ?>><a href="{{route('addProduct')}}">Add Product</a></li>
                <li <?php if(preg_match("/view-products/i", $url)) { echo 'class="active"';} ?>><a href="{{route('viewProducts')}}">View Product</a></li>
            </ul>
        </li>


        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Coupons</span> </a>
            <ul <?php if(preg_match("/coupon/i", $url)) { echo 'style="display:block;"';} ?>>
                <li <?php if(preg_match("/add-coupon/i", $url)) { echo 'class="active"';} ?>><a href="{{route('addCoupon')}}">Add Coupon</a></li>
                <li <?php if(preg_match("/view-coupons/i", $url)) { echo 'class="active"';} ?>><a href="{{route('view.coupon')}}">View Coupons</a></li>
            </ul>
        </li>

        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Orders</span> </a>
            <ul <?php if(preg_match("/order/i", $url)) { echo 'style="display:block;"';} ?>>
                <li <?php if(preg_match("/view-orders/i", $url)) { echo 'class="active"';} ?>><a href="{{url('/admin/view-orders')}}">View Orders</a></li>
            </ul>
        </li>


        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Banners</span> </a>
            <ul <?php if(preg_match("/banner/i", $url)) { echo 'style="display:block;"';} ?>>
                <li <?php if(preg_match("/add-banner/i", $url)) { echo 'class="active"';} ?>><a href="{{route('add.banner')}}">Add Banner</a></li>
                <li <?php if(preg_match("/view-banners/i", $url)) { echo 'class="active"';} ?>><a href="{{route('view.banner')}}">View Banner</a></li>
            </ul>
        </li>

        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Users</span> </a>
            <ul <?php if(preg_match("/user/i", $url)) { echo 'style="display:block;"';} ?>>
                <li <?php if(preg_match("/view-user/i", $url)) { echo 'class="active"';} ?>><a href="{{route('viewUsers')}}">View User</a></li>
            </ul>
        </li>

        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Admins / Sub Admins</span> </a>
            <ul <?php if(preg_match("/admins/i", $url)) { echo 'style="display:block;"';} ?>>
                <li <?php if(preg_match("/add-admin/i", $url)) { echo 'class="active"';} ?>><a href="{{route('addAdmin')}}">Add Admins</a></li>
                <li <?php if(preg_match("/view-admins/i", $url)) { echo 'class="active"';} ?>><a href="{{route('viewAdmins')}}">View Admins</a></li>
            </ul>
        </li>

        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>CMS Pages</span> </a>
            <ul <?php if(preg_match("/cms-page/i", $url)) { echo 'style="display:block;"';} ?>>
                <li <?php if(preg_match("/add-cms-page/i", $url)) { echo 'class="active"';} ?>><a href="{{route('add-cms-page')}}">Add CMS Pages</a></li>
                <li <?php if(preg_match("/view-cms-pages/i", $url)) { echo 'class="active"';} ?>><a href="{{route('viewCmsPages')}}">View CMS Pages</a></li>
            </ul>
        </li>

        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Currency</span> </a>
            <ul <?php if(preg_match("/currencies/i", $url)) { echo 'style="display:block;"';} ?>>
                <li <?php if(preg_match("/add-currency/i", $url)) { echo 'class="active"';} ?>><a href="{{route('add-currency')}}">Add Currency</a></li>
                <li <?php if(preg_match("/view-currencies/i", $url)) { echo 'class="active"';} ?>><a href="{{route('viewCurrency')}}">View Currency</a></li>
            </ul>
        </li>

        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Shipping Charges</span> </a>
            <ul <?php if(preg_match("/shipping/i", $url)) { echo 'style="display:block;"';} ?>>
                <li <?php if(preg_match("/view-shipping/i", $url)) { echo 'class="active"';} ?>><a href="{{route('viewShipping')}}">View Shipping</a></li>
            </ul>
        </li>


        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Newsletter Subscriber</span> </a>
            <ul <?php if(preg_match("/newsletter-subscribers/i", $url)) { echo 'style="display:block;"';} ?>>
                <li <?php if(preg_match("/newsletter-subscribers/i", $url)) { echo 'class="active"';} ?>><a href="{{route('viewNewsletterSubscribers')}}">View</a></li>
            </ul>
        </li>


    </ul>
</div>
<!--sidebar-menu-->
<?php
  use App\Product;
?>

<form action="{{url('/products-filter')}}" method="post">
    @csrf
    <input type="hidden" name="url" value="">

<div class="left-sidebar">
    <h2>Category</h2>
    <div class="panel-group category-products" id="accordian"><!--category-productsr-->
        <div class="panel panel-default">
            @foreach($categories as $cat)
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#{{$cat->id}}" href="#{{$cat->id}}">
                            <span class="badge pull-right"><i class="fa fa-plus"></i></span>
                            {{$cat->name}}
                        </a>
                    </h4>
                </div>
                <div id="{{$cat->id}}" class="panel-collapse collapse">
                    <div class="panel-body">
                        <ul>
                            @foreach($cat->categories as $subcat)
                                <?php $productCount = Product::productCount($subcat->id); ?>
                                <li><a href="{{route('products', $subcat->url)}}">{{$subcat->name}}</a> ({{$productCount}})</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </div><!--/category-products-->

       <h2>Colors</h2>
    <div class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <input type="checkbox" name="colorFilter[]" onchange="javascript:this.form.submit();" id="Blue" value="Blue"> &nbsp; <span class="products-color">Blue</span>
                </h4>
            </div>
        </div>
        <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                       <input type="checkbox" name="colorFilter[]" onchange="javascript:this.form.submit();" id="Black" value="Black"> &nbsp; <span class="products-color">Black</span>
                    </h4>
                </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <input type="checkbox" name="colorFilter[]" onchange="javascript:this.form.submit();" id="Red" value="Red"> &nbsp; <span class="products-color">Red</span>
                </h4>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <input type="checkbox" name="colorFilter[]" onchange="javascript:this.form.submit();" id="Green" value="Green"> &nbsp; <span class="products-color">Green</span>
                </h4>
            </div>
        </div>

    </div>
</div>

</form>
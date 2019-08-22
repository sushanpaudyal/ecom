<?php
  use App\Product;
?>


<form action="{{url('/products-filter')}}" method="post">
    {{csrf_field()}}
    <input type="hidden" name="url" value="{{$url}}">
    <div class="left-sidebar">
    <h2>Category</h2>
    <div class="panel-group category-products" id="accordian"><!--category-productsr-->
        @foreach($categories as $cat)
        <div class="panel panel-default">
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
        </div>
        @endforeach

    </div><!--/category-products-->

       <h2>Colors</h2>
        <div class="panel-group">
            @if(!empty($_GET['color']))
                <?php $colorArray = explode('-', $_GET['color']);
//                   echo "<pre>"; print_r($colorArray); die;

                ?>
                @endif
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <input name="colorFilter[]" onchange="javascript:this.form.submit();" id="Blue" value="Blue" type="checkbox" @if(!empty($colorArray) && in_array("Blue", $colorArray)) checked="" @endif>&nbsp; &nbsp; <span class="products-color">Blue</span>
                    </h4>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <input name="colorFilter[]" onchange="javascript:this.form.submit();" id="Black" value="Black" type="checkbox" @if(!empty($colorArray) && in_array("Black", $colorArray)) checked="" @endif>&nbsp; &nbsp; <span class="products-color">Black</span>
                    </h4>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <input name="colorFilter[]" onchange="javascript:this.form.submit();" id="Red" value="Red" type="checkbox" @if(!empty($colorArray) && in_array("Red", $colorArray)) checked="" @endif>&nbsp; &nbsp; <span class="products-color">Red</span>
                    </h4>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <input name="colorFilter[]" onchange="javascript:this.form.submit();" id="Green" value="Green" type="checkbox" @if(!empty($colorArray) && in_array("Green", $colorArray)) checked="" @endif>&nbsp; &nbsp; <span class=products-color>Green</span>
                    </h4>
                </div>
            </div>
        </div>

</div>
</form>

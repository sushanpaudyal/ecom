<?php
  use App\Product;
?>


<form action="{{url('/products-filter')}}" method="post">
    {{csrf_field()}}
    @if(!empty($url))
        <input type="hidden" name="url" value="{{$url}}">
        @endif
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

        @if(!empty($url))
       <h2>Colors</h2>
        <div class="panel-group">
            @foreach($colorArray as $color)
                @if(!empty($_GET['color']))
                    <?php $colorArr = explode('-',$_GET['color']) ?>
                    @if(in_array($color,$colorArr))
                        <?php $colorcheck="checked"; ?>
                    @else
                        <?php $colorcheck=""; ?>
                    @endif
                @else
                    <?php $colorcheck=""; ?>
                @endif
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <input name="colorFilter[]" onchange="javascript:this.form.submit();" id="{{ $color }}" value="{{ $color }}" type="checkbox" {{ $colorcheck }}>&nbsp;&nbsp;<span class="products-colors">{{ $color }}</span>
                        </h4>
                    </div>
                </div>
            @endforeach
        </div>
            @endif

</div>
</form>

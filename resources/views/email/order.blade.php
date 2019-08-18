<html>
<head></head>
<body>
     <table width="700px" border="0" cellpadding="0" cellspacing="0">
         <tr><td>&nbsp;</td></tr>
         <tr>
             <td><img src="{{asset('images/frontend_images/home/logo.png')}}"></td>
         </tr>
         <tr><td>&nbsp;</td></tr>
         <tr><td>&nbsp;</td></tr>
         <tr><td>Hello {{$name}}</td></tr>
         <tr><td>&nbsp;</td></tr>
         <tr><td>Thank you for shopping with us. Your order details are as below:</td></tr>
         <tr><td>&nbsp;</td></tr>
         <tr><td>Order No: {{$order_id}}</td></tr>
         <tr><td>&nbsp;</td></tr>
         <tr>
             <td>
                 <table width='95%' cellpadding="5" cellspacing="5" bgcolor="#e0d9d9">
                     <tr bgcolor="#cccccc">
                         <td>Product Name</td>
                         <td>Product Code</td>
                         <td>Size</td>
                         <td>Quantity</td>
                         <td>Unit Price</td>
                     </tr>
                     @foreach($productDetails['orders'] as $product)
                         <tr>
                             <td>{{$product['product_name']}}</td>
                             <td>{{$product['product_code']}}</td>
                             <td>{{$product['product_size']}}</td>
                             <td>{{$product['product_qty']}}</td>
                             <td>{{$product['price']}}</td>
                         </tr>
                         @endforeach

                     <tr>
                         <td colspan="5" align="right">Shipping Charges</td>
                         <td>Rs. {{$productDetails['shipping_charges']}}</td>
                     </tr>

                     <tr>
                         <td colspan="5" align="right">Coupon Discount</td>
                         <td>Rs. {{$productDetails['coupon_amount']}}</td>
                     </tr>

                     <tr>
                         <td colspan="5" align="right">Grand Total</td>
                         <td>Rs. {{$productDetails['grand_total']}}</td>
                     </tr>
                 </table>
             </td>
         </tr>

         <tr>
             <td>
                 <table width="100%">
                     <tr>
                         <td width="50%">
                             <table>
                                 <tr>
                                     <td>Bill To:</td>
                                 </tr>
                                 <tr>
                                     <td>{{$userDetails['name']}}</td>
                                 </tr>
                                 <tr>
                                     <td>{{$userDetails['address']}}</td>
                                 </tr>
                                 <tr>
                                     <td>{{$userDetails['city']}}</td>
                                 </tr>
                                 <tr>
                                     <td>{{$userDetails['state']}}</td>
                                 </tr>
                                 <tr>
                                     <td>{{$userDetails['country']}}</td>
                                 </tr>
                                 <tr>
                                     <td>{{$userDetails['mobile']}}</td>
                                 </tr>
                             </table>
                         </td>
                         <td width="50%">
                             <table>
                                 <tr>
                                     <td>Ship To:</td>
                                 </tr>
                                 <tr>
                                     <td>{{$productDetails['name']}}</td>
                                 </tr>
                                 <tr>
                                     <td>{{$productDetails['address']}}</td>
                                 </tr>
                                 <tr>
                                     <td>{{$productDetails['city']}}</td>
                                 </tr>
                                 <tr>
                                     <td>{{$productDetails['state']}}</td>
                                 </tr>
                                 <tr>
                                     <td>{{$productDetails['country']}}</td>
                                 </tr>
                                 <tr>
                                     <td>{{$productDetails['mobile']}}</td>
                                 </tr>
                             </table>
                         </td>
                     </tr>
                 </table>
             </td>
         </tr>
     </table>
</body>
</html>
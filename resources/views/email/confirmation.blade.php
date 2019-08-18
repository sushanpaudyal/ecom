<html>
<head>
    <title>Register Email</title>
</head>
<body>
<table>
    <tr>
        <td>Dear {{$name}} !</td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td>Please Click on the below link to activate your account</td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td>
            <a href="{{url('/confirm/'.$code)}}">Confirm Account</a>
        </td></tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td>Thank & Regards</td></tr>
    <tr><td>Ecommerce Website</td></tr>
</table>
</body>
</html>
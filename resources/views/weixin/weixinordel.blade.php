<script type="text/javascript" src="/js/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="/js/qrcode.js"></script>
<h1 align="center">微信扫码支付<h1>
<div align="center" id="qrcode"></div>
<script>
    var qrcode = new QRCode('qrcode', {
        text: "{{$url}}",
        width: 256,
        height: 256,
        colorDark : '#000000',
        colorLight : '#ffffff',
        correctLevel : QRCode.CorrectLevel.H
    })
</script>
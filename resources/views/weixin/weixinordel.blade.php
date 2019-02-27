<script type="text/javascript" src="/js/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="/js/qrcode.js"></script>
<div id="qrcode"></div>
<script>
    new QRCode(document.getElementById('qrcode'), 'your content');
    var qrcode = new QRCode('qrcode', {
        text: "{{$arr}}",
        width: 256,
        height: 256,
        colorDark : '#000000',
        colorLight : '#ffffff',
        correctLevel : QRCode.CorrectLevel.H
    });

</script>
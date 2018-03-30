$(document).on('ready', function () {

    var scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
    scanner.addListener('scan', function (content) {
        $('input[name="ticketId"]').val(content);
        $('#qr').hide();
        $('button[name="submit"]').click();
        scanner.stop();
    });
    Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
            scanner.start(cameras[0]);
            $('#qr').show();
        } else {
            console.error('No cameras found.');
        }
    }).catch(function (e) {
        console.error(e);
    });

});
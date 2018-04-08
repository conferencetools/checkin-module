$(document).on('ready', function () {

    var scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
    var cameras = [];
    var camIdx = 0;
    scanner.addListener('scan', function (content) {
        $('input[name="ticketId"]').val(content);
        $('#qr').hide();
        $('button[name="submit"]').click();
        scanner.stop();
    });

    $('#qr-stop').click(
        function () {
            scanner.stop();
            $(this).hide();
            $('#qr-start').show();
        }
    );

    $('#qr-start').click(
        function () {
            scan(cameras[camIdx]);
            $(this).hide();
            $('#qr-stop').show();
        }
    );

    $('#qr-switch-camera').click(
        function () {
            camIdx = (camIdx + 1) % cameras.length;
            scan(cameras[camIdx]);
        }
    );

    function scan(camera) {
        scanner.stop();
        scanner.start(camera)
            .then(function (e) {$('#qr').show();})
            .catch(function(e) {$('#qr').hide();});
    }

    Instascan.Camera.getCameras().then(function (cams) {
        cameras = cams;
        if (cameras.length > 1) {
            $('#qr-switch-camera').click(function() {});
        } else {
            $('#qr-switch-camera').hide();
        }
        if (cameras.length > 0) {
            scanner.start(cameras[0])
                .then(function (e) {$('#qr').show();})
                .catch(function(e) {$('#qr').hide();});
        } else {
            console.error('No cameras found.');
        }
    }).catch(function (e) {
        console.error(e);
    });
});
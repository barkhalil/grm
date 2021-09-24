<?php
?>


<section class="content-header">
    <h1> Vérification bon de sortie</h1>
</section><!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success box-body table-responsive">

                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>




                <video id="preview"></video>
                <script type="text/javascript">
                    let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
                    scanner.addListener('scan', function (content) {
                        alert(content);
                    });
                    Instascan.Camera.getCameras().then(function (cameras) {
                        if (cameras.length > 0) {
                            scanner.start(cameras[0]);
                        } else {
                            console.error('No cameras found.');
                        }
                    }).catch(function (e) {
                        console.error(e);
                    });
                </script>

            </div>
        </div>
    </div>

</section>

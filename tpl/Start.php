<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>GRM</title>    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?= WEBRootD ?>/bootstrap/css/bootstrap.min.css">    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/7ceb636cad.css">   <!-- Ionicons -->
    <link rel="stylesheet" href="<?= WEBRootD ?>/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?= WEBRootD ?>/plugins/select2/select2.min.css">
    <link rel="stylesheet" href="<?= WEBRootD ?>/dist/css/skins/skin-purple-light.min.css">
   <link rel="stylesheet" href="<?= WEBRootD ?>/css/app_css/bootstrap-switch.min.css">
    <link rel="stylesheet" href="<?= WEBRoot ?>/css/my_style.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <!--<link rel="stylesheet" href="<?= WEBRoot ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">-->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>    <![endif]--></head>
<body class="hold-transition skin-purple-light sidebar-mini">
<div class="wrapper">    
    <!-- Main Header --> 
        <? include 'modules/header.php'; ?>
    <!-- Left side column. contains the logo and sidebar --> 
        <? include 'modules/mainsidebar.php' ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="alert" style="display: none">
                    <button type="button" class="close" data-dismiss="alert"><span
                            aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <div class="message" id="MsgAlert"> BienVenue</div>
                </div>
            </div>
        </div>       
        <!-- Content Header (Page header) --> 
            <? echo $pages ?>   
    </div>
    <!-- /.content-wrapper -->
    <!-- Main Footer --> 
        <? include 'modules/footer.php'; ?>
    <!-- Control Sidebar à eliminer -->
    <!-- Add the sidebar's background. This div must be placed
    immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
    <!-- ./wrapper -->
    <!-- REQUIRED JS SCRIPTS -->
    <!-- jQuery 2.1.4 -->
<div id="modal-from-dom" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySuup" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title TitD" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                <p>Attention vous êtes sur le point de faire une suppression d'une ligne d'enregistrement.</p>
                <p>Vous voulez continuer</p>
            <div class="form-group">
                <label>Motif de suppression</label>
                <textarea class="form-control" name="prk" required id="pkliste"></textarea>
            </div>
            </div>
            <div class="modal-footer">
                <a href="<?=$Bread;?>&SuppID=" class="btn btn-danger PkAdd">oui</a>
                <a href="#" data-dismiss="modal" class="btn btn-default">Non</a>
            </div>
        </div>
    </div>
</div>
<script src="<?= WEBRootD ?>/plugins/jQuery/jQuery-2.1.4.min.js"></script><!-- Bootstrap 3.3.5 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/locale/fr.js"></script>
<script src="<?= WEBRootD ?>/bootstrap/js/bootstrap.min.js"></script><!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?= WEBRootD ?>/dist/js/app.min.js"></script>
<script src="<?= WEBRootD ?>/plugins/fastclick/fastclick.min.js"></script>
<script src="<?= WEBRootD ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?= WEBRootD ?>/plugins/select2/select2.full.min.js"></script>
<script src="<?= WEBRootD ?>/js/jquery.autocomplete.js"></script>
<script src="<?= WEBRootD ?>/js/highcharts.js"></script>
<script src="<?= WEBRootD ?>/js/jquery.highchartTable-min.js"></script>
<!--<script src="<?= WEBRoot ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>-->
<script src="<?= WEBRootD ?>/js/bootstrap-switch.min.js"></script>
<script type="text/javascript" src="<?= WEBRoot ?>/js/MyCode.js"></script>
<? if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])): ?>
    <script type="text/javascript">
        MSg("<?= $_SESSION['msg']?>", "<?=$_SESSION['type']?>");
    </script>
    <? $_SESSION['msg'] = $_SESSION['type'] = "";endif; ?>
<? if($Pga=="prospects/ajouter" || $Pga=="prospects/modifier"):  ?>
<script>
    $('input[type=text]').keyup(function () {
        this.value = this.value.toUpperCase();
    });
</script>
<?endif;?>
<script type="text/javascript">
    $(function () {
        $('#datePicker').datetimepicker({
            format: 'DD/MM/YYYY',
            locale: 'fr'
        });
    });
    $(function () {
        $('#from').datetimepicker({
            format: 'DD/MM/YYYY',
            locale: 'fr'
        });
        $('#to').datetimepicker({
            format: 'DD/MM/YYYY',
            locale: 'fr',
            useCurrent: false //Important! See issue #1075
        });
        $("#from").on("dp.change", function (e) {
            $('#to').data("DateTimePicker").minDate(e.date);
        });
        $("#to").on("dp.change", function (e) {
            $('#from').data("DateTimePicker").maxDate(e.date);
        });
    });
</script>
</body>
</html>
<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    include_once(Config::$site_path.'/min/utils.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Thetopremodelers admin</title>
        <meta name="description" content="">
        <meta name="author" content="">
        <link type="text/css" rel="stylesheet" media="screen" href="<?php echo Minify_getUri('herve_new_admin_css') ?>"/>
    </head>
    <body data-spy="scroll" data-target=".subnav" data-offset="50">
        <div class="header"></div>
        <div class="container">
            <div class="row">
                <div id="content" class="col-xs-12 col-sm-12 col-md-12"></div>
            </div>
            <footer class="footer">
                <p class="pull-right"><a href="#">Back Home</a></p>
            </footer>
        </div>
        <script type="text/javascript" src="<?php echo Minify_getUri('herve_new_admin_js') ?>"></script>
    </body>
</html>
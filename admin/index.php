<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    include_once(Config::$admin_path.'/controller/pageController.php');
    $pageController=new PageController();
    echo $pageController->printHeader('herve_admin_css');
?>
    <body>
        <div id="content" class="expense_application_container">
                <!-- #LeftPane -->
                <div id="LeftPane" class="ui-layout-west ui-widget ui-widget-content left">
                    <table id="west-grid"></table>
                </div>
                <!-- #LeftPane -->
                <!-- #RightPane -->
                <div id="RightPane" class="ui-layout-center ui-helper-reset ui-widget-content right" >
                    <!-- Tabs pane -->
                    <div class="left" id="switcher"></div>
                    <div class="clear"></div>
                    <div id="tabs" class="jqgtabs">
                        <ul>
                            <li><a href="#tabs-1">Welcome</a></li>
                        </ul>
                        <div id="tabs-1">
                            <h1>TheTopRemoderlers.com Admin Panel</h1>
                            <h2>Work in progress</h2>
                        </div>
                    </div>
                </div>
                <!-- #RightPane -->
        </div>
        <?php
            //echo $pageController->printFooter();
            echo $pageController->printJS('herve_admin_js');
        ?>
    </body>
</html>
<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    include_once(Config::$admin_path.'/controller/pageController.php');
    include_once(Config::$site_path.'/global/Class/login.class.php');
    Login::start($_GET["logoff"]);
    if (!Login::checkIfLoggedIn()) {
        header('Location: http://www.topremodelers.com/admin_new/login.php');
    }
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
                            <div class="purge">
                                <button href="javascript:void(0);" class="fm-button ui-button ui-button-text ui-state-default ui-corner-all fm-button-icon-left" id="purge-soft">
                                    <span class="ui-icon ui-icon-trash"></span>
                                    Purge Soft
                                </button>
                                <button class="fm-button ui-button ui-button-text ui-state-default ui-corner-all fm-button-icon-left" id="purge-hard">
                                    <span class="ui-icon ui-icon-trash"></span>
                                    Purge Hard
                                </button>
                            </div>
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
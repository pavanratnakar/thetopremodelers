<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
include_once(Config::$site_path.'global/Controller/pageController.php');
class PageController extends PageControllerBase{
    public function __construct(){
        $this->init();
        /*if(!$this->checkUserStatus())
        {
            header( 'Location: http://'.$_SERVER['SERVER_NAME'].'/applications/login' ) ;
        }*/
        include_once(Config::$admin_path.'class/page.class.php');
        $this->page=new Page();
    }
}
?>
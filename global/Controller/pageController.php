<?php
class PageControllerBase
{
    protected $page;
    protected $utils;
    protected $path;
    protected $userController;
    public function init()
    {
        include_once(Config::$site_path.'/min/utils.php');
        include_once(Config::$site_path.'/global/Class/utils.class.php');
        //include_once('login/controller/userController.php');
        //$this->userController=new UserController();
        $this->utils=new Utils();
    }
    public function printHeader($name)
    {
        return $this->page->printHeader($name);
    }
    public function printGA()
    {
        return $this->page->printGA();
    }
    public function printJS($name)
    {
        return $this->page->printJS($name);
    }
    public function printFooter()
    {
        return $this->page->printFooter();
    }
    public function checkUserStatus()
    {
        //return $this->userController->checkUserStatus();
    }
}
?>
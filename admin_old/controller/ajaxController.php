<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
include_once(Config::$site_path.'global/Controller/pageController.php');
class PageController extends PageControllerBase
{
    public function __construct($subModule=null)
    {
        $this->init();
        include_once(Config::$admin_path.'class/page.class.php');
        $this->page=new Page();
    }
    public function printSubModules($subModule)
    {
        $subModule=$this->utils->checkValues($subModule);
        echo $this->page->printSubModules($subModule);
    }
}
if(isset($_REQUEST['submodule']))
{
    $pageController=new PageController();
    $pageController->printSubModules($_REQUEST['submodule']);
}
?>
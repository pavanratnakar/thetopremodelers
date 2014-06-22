<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
include_once(Config::$site_path.'global/Controller/pageController.php');
class PageController extends PageControllerBase{
    public function __construct($subModule=null){
        $this->init();
        include_once(Config::$admin_path.'class/page.class.php');
        $this->page=new Page();
    }
    public function printSubModules($subModule){
        $subModule=$this->utils->checkValues($subModule);
        echo $this->page->printSubModules($subModule);
    }
}
if (isset($_REQUEST['submodule'])) {
    $pageController=new PageController();
    $pageController->printSubModules($_REQUEST['submodule']);
} else if (isset($_REQUEST['purge'])) {
    include_once(Config::$admin_path.'class/purge.class.php');
    $pageController=new PageController();
    $purge = new Purge();
    if ($_REQUEST['purge'] === 'hard') {
        echo $purge->hardDelete();
    } else if ($_REQUEST['purge'] === 'soft') {
        echo $purge->softDelete();
    }
} else if (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] === 'files' && $_REQUEST['type'] === 'images') {
        $pattern="(\.jpg$)|(\.png$)|(\.jpeg$)|(\.gif$)"; //valid image extensions
        $files = array();
        $curimage=0;
        $response = '';
        if ($handle = opendir(Config::$site_path.'images/contractors')) {
            while (false !== ($file = readdir($handle))) { 
                if (eregi($pattern, $file)) {
                    //$response[$curimage]=$file;
                    $fileName = substr($file, 0, (strlen ($file)) - (strlen (strrchr($file,'.'))));
                    $response .= $fileName.':'.$fileName.';';
                    $curimage++;
                }
            } 
        }
        $response = substr_replace($response ,"",-1);
        echo json_encode($response);
        closedir($handle);
    }
}
?>
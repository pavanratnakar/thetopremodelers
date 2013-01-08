<?php
class AjaxController{
    protected $utils;
    public function __construct($ref=null){
        include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
        include_once($_SERVER['DOCUMENT_ROOT'].'/min/utils.php');
        include_once(Config::$site_path.'/global/Class/utils.class.php');
        $this->utils=new Utils();
        $this->$ref();
    }
    public function categorySelect(){
        $place_name = $this->utils->checkValues($_REQUEST['place_name']);
        if ($place_name) {
            include_once(Config::$site_path.'class/category.class.php');
            $category = new Category();
            echo json_encode($category->getCategory($place_name));
        }
    }
    public function sectionSelect(){
        $place_name = $this->utils->checkValues($_REQUEST['place_name']);
        $category_name = $this->utils->checkValues($_REQUEST['category_name']);
        if ($place_name && $category_name) {
            include_once(Config::$site_path.'class/section.class.php');
            $section = new Section($category_name,$place_name);
            echo json_encode($section->getSections());
        }
    }
}
if(isset($_REQUEST['ref'])){
    $ajaxController=new AjaxController($_REQUEST['ref']);
}
?>
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
    public function getContractorSelection(){
        include_once(Config::$site_path.'class/contractor.class.php');
        $contractor_name = $this->utils->checkValues($_REQUEST['contractor_name']);
        $contractor = new Contractor();
        $contractorPlaceDetails = $contractor->getPlacesForContractor(array('contractor_name'=>$contractor_name));
        $place_name = $contractorPlaceDetails[0]['place_name'];
        $contractorCategoryDetails = $contractor->getCategoriesForContractor(array(
            'contractor_name'=>$contractor_name,
            'place_name'=>$place_name
        ));
        $contractorSectionDetails = $contractor->getSectionsForContractor(array(
            'contractor_name'=>$contractor_name,
            'place_name'=>$place_name,
            'place_name'=>$contractorCategoryDetails[0]['contractor_name']
        ));
        $return = '
        <form id="contractorQuoteSelection-'.$contractor_name.'" class="contractorQuoteSelection clearfix">
        <div class="select-options left">
        <select class="place_select">';
        foreach($contractorPlaceDetails as $key=>$value) {
            $return .= '<option value="'.$value['place_name'].'">'.$value['place_title'].'</option>';
        }
        $return .= '</select>';
        $return .= '<select class="category_select">';
        foreach($contractorCategoryDetails as $key=>$value) {
            $return .= '<option value="'.$value['category_name'].'">'.$value['category_title'].'</option>';
        }
        $return .= '</select>';
        $return .= '<select class="section_select">';
        foreach($contractorSectionDetails as $key=>$value) {
            $return .= '<option value="'.$value['section_name'].'">'.$value['section_title'].'</option>';
        }
        $return .= '</select>';
        $return .= '</div>
        <div class="right">
        <button class="button green rounded small submit" type="submit">Select</button>
        </div>
        </form>';
        echo $return;
    }
    public function getCategoriesForContractor(){
        include_once(Config::$site_path.'class/contractor.class.php');
        $contractor_id = $this->utils->checkValues($_REQUEST['contractor_id']);
        $place_name = $this->utils->checkValues($_REQUEST['place_name']);
        $contractor = new Contractor();
        echo json_encode($contractor->getCategoriesForContractor(array(
            'contractor_id'=>$contractor_id,
            'place_name'=>$place_name
        )));
    }
    public function getSectionsForContractor(){
        include_once(Config::$site_path.'class/contractor.class.php');
        $contractor_id = $this->utils->checkValues($_REQUEST['contractor_id']);
        $place_name = $this->utils->checkValues($_REQUEST['place_name']);
        $category_name = $this->utils->checkValues($_REQUEST['category_name']);
        $contractor = new Contractor();
        echo json_encode($contractor->getSectionsForContractor(array(
            'contractor_id'=>$contractor_id,
            'place_name'=>$place_name,
            'category_name'=>$category_name
        )));
    }
}
if (isset($_REQUEST['ref'])) {
    $ajaxController=new AjaxController($_REQUEST['ref']);
}
?>
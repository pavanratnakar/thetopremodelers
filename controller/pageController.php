<?php
class PageController{
    protected $utils;
    protected $place;
    protected $question;
    protected $category;
    protected $section;
    protected $contractor;
    protected $form;
    protected $page;
    public function __construct($pageNumber){
        include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
        include_once($_SERVER['DOCUMENT_ROOT'].'/min/utils.php');
        include_once(Config::$site_path.'/global/Class/utils.class.php');
        include_once(Config::$site_path.'/class/page.class.php');
        $this->page=new Page($pageNumber);
        $this->utils=new Utils();
    }
    public function printHeader($meta=null){
        return $this->page->printHeader($meta);
    }
    public function printCss($name){
        return $this->page->printCss($name);
    }
    public function printNavigation(){
        return $this->page->printNavigation();
    }
    public function printJS($name){
        return $this->page->printJS($name);
    }
    public function printReviewContainer(){
        return $this->page->printReviewContainer();
    }
    public function printUserStepsText($index=null){
        return $this->page->printUserStepsText($index);
    }
    public function printFooterLinks(){
        $return='
        <div id="footer">
            <div class="footer-wrapper clearfix">
                <div class="footer-top">
                    <div class="column city_selector">
                        <h3>Cities we cover | <a href="'.Config::$site_url.'places" title="More Cities">More Cities &#8250;</a></h3>
                        '.$this->citySelector().'
                    </div>
                    <div class="column about_us">
                        <h3>About Us</h3>
                        <div class="footer_about">
                            <p>We are the only company providing roofing contractors in Dallas ,with 5 Stars certified ratings ,giving you the confidence in choosing the right company</p>                         
                            <p>&nbsp;</p>
                            <p>Address: <b>2003 michigan ave, Dallas tx 75216</b></p>
                            <p>Telephone: <b>1(214)303 9771</b></p>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="footer-wrapper clearfix">
                    <div class="column copyrights">
                        <p>Copyright &copy; '.date("Y").'. All Rights Reserved.</p>
                    </div>
                    <div class="column links">
                        <p>';
                        $i=0;
                        foreach ($this->page->getPages() as $page) {
                            if($page->navigation==1){
                                $return.='<a href="'.Config::$site_url.$page->link.'" title="'.$page->name.'">'.$page->name.'</a>';
                                $i++;
                            }
                        }
                        $return .= '</p>
                    </div>
                </div>
            </div>            
        </div>';
        return $return;
    }
    public function printFooter(){
        return $this->page->printFooter();
    }
    public function getPlace(){
        include_once(Config::$site_path.'class/place.class.php');
        $this->place = new Place();
        return $this->place;
    }
    public function getCategory(){
        include_once(Config::$site_path.'class/category.class.php');
        $this->category = new Category();
        return $this->category;
    }
    public function getSection($categoryName,$placeName){
        include_once(Config::$site_path.'class/section.class.php');
        $this->section = new Section($categoryName,$placeName);
        return $this->section;
    }
    public function getContractor(){
        include_once(Config::$site_path.'class/contractor.class.php');
        $this->contractor = new Contractor();
        return $this->contractor;
    }
    public function getArticle(){
        include_once(Config::$site_path.'class/article.class.php');
        $this->article = new Article();
        return $this->article;
    }
    public function getQuestions($placeName,$categoryName,$sectionName){
        include_once(Config::$site_path.'class/question.class.php');
        $this->question = new Question();
        return $this->question->getQuestions($placeName,$categoryName,$sectionName);
    }
    public function formatQuestions($placeName,$categoryName,$sectionName){
        include_once(Config::$site_path.'class/form.class.php');
        $this->form=new Form();
        $questions = $this->getQuestions($placeName,$categoryName,$sectionName);
        $return = '';
        if ($questions) {
            foreach($questions as $question){
                $values = $question['values'] ? $question['values'] : null;
                $return .= $this->form->createElement($question['category_text'],$question['question_text'],$question['question_id'],$question['question_validation'],$values);
            }
            $return .= '<div class="clear"></div>';
        }
        return $return;
    }
    public function getFormatedCategories($position,$placeName='dallas_texas'){
        include_once(Config::$site_path.'class/category.class.php');
        $this->category = new Category();
        return $this->category->getFormatedCategories($position,$placeName);
    }
    public function getFormatedSections($categoryName,$placeName){
        include_once(Config::$site_path.'class/section.class.php');
        $this->section = new Section($categoryName,$placeName);
        return $this->section->getFormatedSections();
    }
    public function citySelector(){
        $return = '<div id="city_selector"><ul>';
        if (!($this->place instanceof Place)) {
            $this->getPlace();
        }
        $places = $this->place->getPlaces(8);
        foreach ($places as $key => $value) {
            $return .= '<li><a href="'.Config::$site_url.'place/'.$value['place_name'].'" title="'.$value['place_title'].'">'.$value['place_title'].'</a></li>';
        }
        $return .= '</ul></div>';
        return $return;
    }
    public function getUtils(){
        return $this->utils;
    }
    public function FormatUSStateSelectList(){
        $state_list = array(
            'AL'=>"Alabama",  
            'AK'=>"Alaska",  
            'AZ'=>"Arizona",  
            'AR'=>"Arkansas",  
            'CA'=>"California",  
            'CO'=>"Colorado",  
            'CT'=>"Connecticut",  
            'DE'=>"Delaware",  
            'DC'=>"District Of Columbia",  
            'FL'=>"Florida",  
            'GA'=>"Georgia",  
            'HI'=>"Hawaii",  
            'ID'=>"Idaho",  
            'IL'=>"Illinois",  
            'IN'=>"Indiana",  
            'IA'=>"Iowa",  
            'KS'=>"Kansas",  
            'KY'=>"Kentucky",  
            'LA'=>"Louisiana",  
            'ME'=>"Maine",  
            'MD'=>"Maryland",  
            'MA'=>"Massachusetts",  
            'MI'=>"Michigan",  
            'MN'=>"Minnesota",  
            'MS'=>"Mississippi",  
            'MO'=>"Missouri",  
            'MT'=>"Montana",
            'NE'=>"Nebraska",
            'NV'=>"Nevada",
            'NH'=>"New Hampshire",
            'NJ'=>"New Jersey",
            'NM'=>"New Mexico",
            'NY'=>"New York",
            'NC'=>"North Carolina",
            'ND'=>"North Dakota",
            'OH'=>"Ohio",  
            'OK'=>"Oklahoma",  
            'OR'=>"Oregon",  
            'PA'=>"Pennsylvania",  
            'RI'=>"Rhode Island",  
            'SC'=>"South Carolina",  
            'SD'=>"South Dakota",
            'TN'=>"Tennessee",  
            'TX'=>"Texas",  
            'UT'=>"Utah",  
            'VT'=>"Vermont",  
            'VA'=>"Virginia",  
            'WA'=>"Washington",  
            'WV'=>"West Virginia",  
            'WI'=>"Wisconsin",  
            'WY'=>"Wyoming"
        );
        foreach($state_list as $key => $value){
            echo '<option value="'.$key.'">'.$value.'</option>';
        }
    }
    public function getMeta($type,$name){
        if ($type==='place') {
            return $this->place->getMeta($this->utils->checkValues($name));
        }
        if ($type==='article') {
            return $this->article->getMeta($this->utils->checkValues($name));
        }
        if ($type==='contractor') {
            return $this->contractor->getMeta($this->utils->checkValues($name));
        }
    }
    public function jumpList($data,$selected){
        return $this->page->jumpList($data,$selected);
    }
    public function facebookComment($data){
        return $this->page->facebookComment($data);
    }
}
?>
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
        $title = 'Find a Local Business, great ratings thetopremodelers';
        $summary = 'Free service that compiles certified ratings from local service companies and contractors in multiple cities';
        $image = Config::$site_url."images/global/logo.png";
        $return='
        <div id="footer">
        <div class="footer-wrapper clearfix">
        <div class="footer-top">
        <div class="column city_selector">
        <h3>Cities we cover | <a href="'.Config::$site_url.'places" title="More Cities">More Cities &#8250;</a></h3>
        '.$this->citySelector().'
        </div>
        <div class="column about_us">
            <div class="footer_about">
                <h3>About Us</h3>
                <div class="content">
                    <p>'.$summary.'</p>                         
                    <p>&nbsp;</p>
                    <p>Address: <b>2003 michigan ave, Dallas tx 75216</b></p>
                    <p>Telephone: <b>1(214)303 9771</b></p>
                </div>
            </div>
            <div class="social-sharing">
                <h3>Share Us</h3>
                <div class="content">
                    <span class="st_sharethis_large" st_image="'.$image.'" st_title="'.$title.'" st_summary="'.$summary.'" st_url="'.Config::$site_url.'" displayText="ShareThis"></span>
                    <span class="st_facebook_large" st_image="'.$image.'" st_title="'.$title.'" st_summary="'.$summary.'" st_url="'.Config::$site_url.'" displayText="Facebook"></span>
                    <span class="st_twitter_large" st_image="'.$image.'" st_title="'.$title.'" st_summary="'.$summary.'" st_url="'.Config::$site_url.'" displayText="Tweet"></span>
                    <span class="st_linkedin_large" st_image="'.$image.'" st_title="'.$title.'" st_summary="'.$summary.'" st_url="'.Config::$site_url.'" displayText="LinkedIn"></span>
                    <span class="st_googleplus_large" st_image="'.$image.'" st_title="'.$title.'" st_summary="'.$summary.'" st_url="'.Config::$site_url.'" displayText="Google +"></span>
                    <span class="st_pinterest_large" st_image="'.$image.'" st_title="'.$title.'" st_summary="'.$summary.'" st_url="'.Config::$site_url.'" displayText="Pinterest"></span>
                    <span class="st_email_large" st_image="'.$image.'"  st_title="'.$title.'" st_summary="'.$summary.'" st_url="'.Config::$site_url.'" displayText="Email"></span>
                </div>
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
    public function getContractorDetails($contractorDetails,$quote=null){
        $return = '
        <h2>'.$contractorDetails['contractor_title'].'</h2>
        <div class="entry-body">
        <div class="clearfix">
        <div class="left first-container">';
        if ($contractorDetails['image_id']) {
        $return .= '<div class="entry-image">
        <img alt="'.$contractorDetails['contractor_title'].'" src="/images/contractors/'.$contractorDetails['image_id'].'.jpg" />
        </div>';
        }
        $return .= '</div>
        <div class="left ratings-reviews">';
        if ($contractorDetails['average_score']) {
            $return.= '<p><i class="rating-static rating-'.($contractorDetails['average_score']*10).'"></i></p>';
            $return.= '<p><span class="rating-score">'.$contractorDetails['average_score'].'</span></p>';
        }
        if ($contractorDetails['review_count']) {
            $return.= '<p>'.$contractorDetails['review_count'].' Reviews</p>';
            $return.= '<p><a href="'.Config::$site_url.'contractor/'. $contractorDetails['contractor_name'].'#ratings-reviews" title="See all reviews">See all reviews</a></p>';
        }
        if (!$contractorDetails['average_score'] && !$contractorDetails['review_count']) {
            $return.= '<p><i>Yet to be rated</i></p>';
        }
        $return.=  '</div>
        <div class="left contact-details">';
        if ($contractorDetails['contractor_phone']) {
            $return.= '<span class="telephone">'.$contractorDetails['contractor_phone'].'</span>';
        }
        if ($contractorDetails['contractor_address']) {
            $return.= '<div class="address">'.$contractorDetails['contractor_address'].'</div>';
        }
        $return.=  '</div>';
        if ($quote)
            $return.=  '<div class="right options">
        <a href="javascript:void(0);" data-name="'.$contractorDetails['contractor_name'].'" id="contractorSelect-'.$contractorDetails['contractor_name'].'" class="get-quote small orange button">Get a Quote</a>
        </div>';
        $return.= "</div>
        <div class='social-sharing'>
            <span class='st_pinterest_hcount' displayText='Pinterest'></span>
            <span class='st_twitter_hcount' displayText='Tweet'></span>
            <span class='st_linkedin_hcount' displayText='LinkedIn'></span>
            <span class='st_fbrec_hcount' displayText='Facebook Recommend'></span>
            <span class='st_email_hcount' displayText='Email'></span>
            <span class='st_plusone_hcount' displayText='Google +1'></span>
        </div>
        </div>
        ";
        return $return;
    }
}
?>
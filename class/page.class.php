<?php
class Page{
    protected $title;
    protected $description;
    protected $keywords;
    protected $pages;
    protected $currentPage;

    public function __construct($pageNumber){
        $this->pages = new SimpleXMLElement(file_get_contents('xml/page.xml'));
        $this->currentPage($pageNumber);
    }
    public function currentPage($pageNumber){
        $this->currentPage = $this->pages->page[$pageNumber];
    }
    public function getPages(){
        return $this->pages;
    }
    public function printHeader($meta=null, $avoidCrawl=false, $theme=0, $background=2){
        $return='
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html dir="ltr" lang="en-US" xml:lang="en"
        xmlns="http://www.w3.org/1999/xhtml"
        xmlns:og="http://ogp.me/ns#"
        xmlns:fb="https://www.facebook.com/2008/fbml"
        itemscope itemtype="http://schema.org/">
        <head>';
        $return.=$this->printMeta($meta,$avoidCrawl);
        $return.=$this->printCss('herve_css');
        if ($this->currentPage->css == 1) {
            $return.=$this->printCss('herve_'.$this->currentPage->class.'_css');
        }
        $backgroundClass = '';
        $multipleClass = '';
        if ($theme == 1) {
            if ($background == 0) {
                $background = 2;
            }
            $backgroundArray = explode(',', $background);
            if (sizeof($backgroundArray) > 1) {
                $multipleClass = 'background-rotate';
            }
            $backgroundClass = 'background'.$backgroundArray[array_rand($backgroundArray)];
        }
        if ($theme == 99) {
            $backgroundClass = 'video';
        }
        $return.='
        </head>
        <body data-bg="'.$background.'" class="'.$this->currentPage->class.' '.$this->currentPage->template.' '.$backgroundClass.' '.$multipleClass.'">
        <div id="fb-root"></div>
        ';
        return $return;
    }
    public function printMeta($meta=null,$avoidCrawl=false){
        $keywords = $this->currentPage->keywords;
        $geo = '32.723812,-96.816880';
        $geo_placename = 'Dallas, Dallas County, Texas, United States';
        if ($meta) {
            $title = $meta['title'] ? $meta['title'] : $this->currentPage->title;
            $description = $meta['description'];
            if ($meta['keywords'] || $meta['keywords'] === false) {
                $keywords = ($meta['keywords'] === false) ? '' : $meta['keywords'];
            };
            if ($meta['geo']) {
                $geo = $meta['geo'];
            }
            if ($meta['geo_placename']) {
                $geo_placename = $meta['geo_placename'];
            }
        } else {
            $title = $this->currentPage->title;
            $description = $this->currentPage->description;
        }
        $keywords = trim($keywords);
        $return='
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <!-- No Cache -->
        <meta http-equiv="PRAGMA" content="NO-CACHE"/>
        <!-- End No Cache -->
        <title>'.$title.'</title>
        <!-- SEO -->
        <meta http-equiv="Content-Language" content="en" />
        <meta name="title" content="'.$title.'" />
        <meta name="description" content="'.$description.'" />';
        if ($keywords) {
            $return.='
            <meta name="keywords" content="'.$keywords.'" />';
        }
        $return.='
        <meta name="author" content="Top Remodelers" />';
        if ($avoidCrawl) {
            $return.='
            <meta name="robots" content="noindex, nofollow" />';
        } else {
            $return.='
            <meta name="robots" content="index,follow" />
            <meta name="revisit-after" content="2 days" />
            <meta name="googlebot" content="index, follow, archive" />
            <meta name="msnbot" content="index, follow" />
            <meta name="YahooSeeker" content="index, follow" />';
        }
        $return.='
        <!-- SEO -->
        <!-- GEO -->
        <meta name="geo.region" content="US-TX" />
        <meta name="geo.placename" content="'.$geo_placename.'" />
        <meta name="geo.position" content="'.str_replace(",",";",$geo).'" />
        <meta name="ICBM" content="'.$geo.'" />
        <!-- END OF GEO -->
        <!-- OG META TAGS -->
        <meta property="og:title" content="'.$title.'" />
        <meta property="og:type" content="website" />
        <meta property="og:image" content="http://www.thetopremodelers.com/images/global/logo.png" />
        <meta property="og:description" content="'.$description.'" />
        <meta property="og:site_name" content="The Top Remodelers" />
        <meta property="fb:admins" content="100000417819011" />
        <!-- END OF OG META TAGS -->
        <!-- GOOGLE PLUS TAGS -->
        <meta itemprop="name" content="'.$title.'" />
        <meta itemprop="description" content="'.$description.'" />
        <meta itemprop="image" content="http://www.thetopremodelers.com/images/global/logo.png" />
        <!-- END OF GOOGLE PLUS TAGS -->
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="HandheldFriendly" content="true" />
        <link rel="icon" href="favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
        ';
        return $return;
    }
    public function printCss($name){
        $return='
        <!-- CSS -->
        <link type="text/css" rel="stylesheet" media="screen" href="'.Minify_getUri($name).'"/>
        <!-- CSS -->';
        return $return;
    }
    public function printJS($name){
        $return='<script type="text/javascript" src="'.Minify_getUri($name).'"></script>';
        return $return;
    }
    public function printNavigation(){
        $return = '
        <button type="button" class="btn btn-default dropdown-toggle visible-xs-block navbar-icon">
            <span class="glyphicon glyphicon-align-justify"></span>
        </button>
        <ul class="nav navbar-nav navbar-right">';
        foreach ($this->pages as $page) {
            if($page->navigation==1){
                $class = ($page->id == $this->currentPage->id) ? 'class="active"' : '';
                $return.='<li '.$class.'><a href="'.Config::$site_url.$page->link.'" title="'.$page->name.'">'.$page->name.'</a></li>';
            }
        }
        $return.='</ul>';
        return $return;
    }
    public function printHeaderMenu(){
       $return = '
            <div class="navbar navbar-default" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-collapse">
                        <div class="nav-contact hidden-xs">
                            <h4 class="gold">Need immediate service</h4>
                            <h5>CALL US : <a href="tel:1-214-303-9771">1(214)303 9771</a></h5>
                        </div>';
        $return .= $this->printNavigation();
        $return .=  '</div>
                </div>
            </div>';
        return $return;
    }
    public function printLogoContainer(){
        $return = '
            <div class="nav-logo-container">
                <div class="logo-container">
                    <a href="http://www.thetopremodelers.com">
                        <img src="'.Config::$site_url.'/images/global/logo.png" alt="The Top Remodelers"/>
                    </a>
                </div>
            </div>';
        return $return;
    }
    public function printReviewContainer(){
        $review = new Review();
        $reviews = $review->getReviews();
        $return = '';
        $i=1;
        foreach ($reviews as $r) {
            $return .= '
            <div class="review">
            <i class="quote_start"></i>
            <h4>Project : '.$r['project'].'</h4>
            <h5 class="blue">Customer in '.$r['region'].'</h5>
            <p>'.$r['description'].'</p>
            <i class="quote_end"></i>';
            $return .= '</div>';
            $i++;
        }
        return $return;
    }
    public function printUserStepsText($index){
        $return = '<div class="match-options-container"><div class="row">';
        $steps = array(
            'Select a Category',
            'Describe your need',
            'Get Matched to Pros'
            );
        $positionClasses = array(
            'first',
            'second',
            'third'
        );
        for ($i=1;$i<=sizeof($steps);$i++){
            $positionClass = '';
            $return .= '<div class="col-md-4 col-xs-4 col-sm-4 '.$positionClasses[$i-1].'">
                            <div class="match-option-container">
                                <span class="label number">'.$i.'</span>
                                <span class="label">'.$steps[$i-1].'</span>
                            </div>
                        </div>';
        }
        $return .= '</div></div>';
        return $return;
    }
    public function printFooter(){
        $return.=$this->printJS('herve_global_js');
        if($this->currentPage->js == 1){
            $return.=$this->printJS('herve_'.$this->currentPage->class.'_js');
        }
        $return.=$this->printGA();
        $return.=$this->zopimChat();
        $return.='</body></html>';
        return $return;
    }
    public function printGA(){
        $return="
        <script type=\"text/javascript\">
        var _gaq = _gaq || [];

        _gaq.push(['_setAccount', 'UA-30287515-1']);
        //_gaq.push(['_setSiteSpeedSampleRate', 90]);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
        </script>";
        return $return;
    }
    public function jumpList($data, $selected) {
        if (sizeof($data) <= 1 && $selected!='top') {
            return '';
        }
        $return ='
        <ul class="nav nav-pills">';
        foreach ($data as $key=>$value) {
            $return .= '
            <li class="'.(($selected==$key) ? 'active' : '').'">
            <a title="'.$value['title'].'" class="'.(($selected==$key) ? 'selected' : '').'" href="#'.$key.'">
            '.$value['title'].'
            '.(($selected==$key) ? '<i></i>' : '').'
            </a>
            </li>
            ';
        }
        if ($selected=='top') {
            $return .= '
            <li class="'.$key.' right selected">
            <a class="active" title="Back to Top" href="#Top">
            Back to Top
            <i></i>
            </a>
            </li>
            ';
        }
        $return .='</ul>';
        return $return;
    }
    public function facebookComment($data) {
        return '<div class="fb-comments" data-href="'.$data['href'].'" data-width="'.$data['width'].'" data-num-posts="'.$data['posts'].'"></div>';
    }
    public function zopimChat(){
        return "<!--Start of Zopim Live Chat Script-->
        <script type=\"text/javascript\">
        window.\$zopim||(function(d,s){var z=\$zopim=function(c){z._.push(c)},$=z.s=
            d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
                _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
                $.src='//cdn.zopim.com/?MddEPZHtRdlfhytSBoJLIVoWotreXFR0';z.t=+new Date;$.
                type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
    </script>
    <!--End of Zopim Live Chat Script-->";
    }
    /* VERY BASIC FUNCTION */
    public function getArticles(){
        return '
        <ul>
        <li class="first-child">
        <h4>A roofing contractor</h4>
        <ul class="clearfix">
        <li><a href="'.Config::$site_url.'article/having_right_contract" title="Have the right contract">Have the right contract</a></li>
        <li><a href="'.Config::$site_url.'article/roofings_warranty" title="Choosing your roofing warranty">Choosing your roofing warranty</a></li>
        </ul>
        </li>
        <li>
        <h4>Your choices of flat roofs</h4>
        <ul class="clearfix">
        <li><a href="'.Config::$site_url.'article/pvc_roofs" title="Pvc roofs">Pvc roofs</a></li>
        <li><a href="'.Config::$site_url.'article/rubber_membrane_usage" title="Rubber membrane">Rubber membrane</a></li>
        <li><a href="'.Config::$site_url.'article/built_up_flat_roofs" title="Rubber membrane">Built up Flat Roofs</a></li>
        <li><a href="'.Config::$site_url.'article/tpo_roofing" title="Tpo roofing">Tpo roofing</a></li>
        </ul>
        </li>
        <li>
        <h4>Different types of roofs materials</h4>
        <ul class="clearfix">
        <li><a href="'.Config::$site_url.'article/metal_roof" title="Metal roof">Metal roof</a></li>
        <li><a href="'.Config::$site_url.'article/benefits_of_asphalt_tiles" title="Asphalt singles">Asphalt singles</a></li>
        <li><a href="'.Config::$site_url.'article/green_roofing" title="Green roofing">Green roofing</a></li>
        <li><a href="'.Config::$site_url.'article/standing_seam_roof" title="Standing Seam roof">Standing Seam roof</a></li>
        <li><a href="'.Config::$site_url.'article/steel_roofing" title="Steel roofing">Steel roofing</a></li>
        <li><a href="'.Config::$site_url.'article/natural_slate_roofing" title="Natural slate roofing">Natural slate roofing</a></li>
        <li><a href="'.Config::$site_url.'article/cooper_roofing" title="Cooper roofing">Cooper roofing</a></li>
        <li><a href="'.Config::$site_url.'article/clay_roofing" title="Clay roofing">Clay roofing</a></li>
        </ul>
        </li>
        <li>
        <h4>Your Insurance</h4>
        <ul class="clearfix">
        <li><a href="'.Config::$site_url.'article/dallas_under_dangerous_threat_hailstorm" title="Dallas under the dangerous threat of Hailstorm in June 2012">Dallas under the dangerous threat of Hailstorm in June 2012</a></li>
        <li><a href="'.Config::$site_url.'article/3_ways_your_roof_can_save_you" title="3 ways your roof can save you">3 ways your roof can save you</a></li>
        </ul>
        </li>
        </ul>';
    }
}
?>
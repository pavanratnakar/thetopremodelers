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
        <meta property="og:image" content="http://www.topremodelers.com/images/global/logo.png" />
        <meta property="og:description" content="'.$description.'" />
        <meta property="og:site_name" content="The Top Remodelers" />
        <meta property="fb:admins" content="100000417819011" />
        <!-- END OF OG META TAGS -->
        <!-- GOOGLE PLUS TAGS -->
        <meta itemprop="name" content="'.$title.'" />
        <meta itemprop="description" content="'.$description.'" />
        <meta itemprop="image" content="http://www.topremodelers.com/images/global/logo.png" />
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
        <style>
            li{margin-bottom:10px;}
        </style>
        <script>
            var cb = function() {
                var l = document.createElement("link"); l.rel = "stylesheet";
                l.href = "'.Minify_getUri($name).'";
                var h = document.getElementsByTagName("head")[0]; h.parentNode.insertBefore(l, h);
            };
            var raf = requestAnimationFrame || mozRequestAnimationFrame ||
                webkitRequestAnimationFrame || msRequestAnimationFrame;
            if (raf) raf(cb);
            else window.addEventListener("load", cb);
        </script>';
        return $return;
    }
    public function printJS($name){
        $return='<script type="text/javascript" src="'.Minify_getUri($name).'"></script>';
        return $return;
    }
    public function printNavigation(){
        $return = '
        <button type="button" class="btn btn-default dropdown-toggle visible-xs-block navbar-icon">
            <span class="glyphicon glyphicon-cog"></span>
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
                    <a href="http://www.topremodelers.com">
                        <img src="'.Config::$site_url.'images/global/logo.png" data-src="'.Config::$site_url.'images/global/logo.png" alt="The Top Remodelers"/>
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
        $return = '';
        if ($this->currentPage->js == 1) {
            $return .= $this->printJS('herve_'.$this->currentPage->class.'_js');
        } else {
            $return .= $this->printJS('herve_global_js');
        }
        $return .= $this->zopimChat();
        $return .= '</body></html>';
        return $return;
    }
    public function printGA(){
        $return="
        <script type=\"text/javascript\">
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-58561111-1', {'siteSpeedSampleRate': 100});
            ga('send', 'pageview');
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
    public function getArticles ($articles) {
        $i = 0;
        if (sizeof($articles) > 0) {
            $return = '<ul>';
        }
        $previousCategory = '';
        foreach ($articles as $key=>$value) {
            if ($previousCategory !== $value['category']) {
                $return .= '
                <li>
                <h4>'.$value['category'].'</h4>
                <ul class="clearfix">
                ';
            } else {

            }
            $return .= '<li><a href="'.Config::$site_url.'article/'.$value['name'].'" title="'.$value['title'].'">'.$value['title'].'</a></li>';
            if ($previousCategory && $value['category'] !== $articles[$i+1]['category']) {
                $return .= '
                </ul></li>';
            }
            $previousCategory = $value['category'];
            $i++;

        }
        if (sizeof($articles) > 0) {
            $return .= '</ul>';
        }
        return $return;
    }
}
?>
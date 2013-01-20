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
    public function printHeader($meta=null){
        $return='
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
            "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html dir="ltr" lang="en-US" xml:lang="en"
            xmlns="http://www.w3.org/1999/xhtml"
            xmlns:og="http://ogp.me/ns#"
            xmlns:fb="https://www.facebook.com/2008/fbml"
            itemscope itemtype="http://schema.org/">
            <head>';
        $return.=$this->printMeta($meta);
        $return.=$this->printCss('herve_css');
        if($this->currentPage->css == 1){
            $return.=$this->printCss('herve_'.$this->currentPage->class.'_css');
        }
        $return.='
        </head>
            <body class="'.$this->currentPage->class.' '.$this->currentPage->template.'">
            <div id="fb-root"></div>
            <div id="wrapper">
                <div class="art-header clearfix">
                    <div class="art-logo left">
                        <h1><a href="'.Config::$site_url.'"><img src="'.Config::$site_url.'images/global/logo.png" alt="The Top Remodelers - MATCHING YOU WITH OUR PRESCREENED CONTRACTORS" title="The Top Remodelers - MATCHING YOU WITH OUR PRESCREENED CONTRACTORS" width="200" height="76"/></a></h1>
                    </div>
                    <div class="art-contact right">
                        <span class="orange">Need immediate service</span>
                        <div class="art-cell">
                            <h3><span class="orange">Call us:</span> <span class="number brown">1(214)303 9771</span></h3>
                        </div>
                    </div>
                </div>';
        return $return;
    }
    public function printMeta($meta=null){
        $keywords = $this->currentPage->keywords;
        if($meta){
            $title = $this->currentPage->title.' | '.$meta['title'];
            $description = $meta['description'];
            if($meta['keywords'] || $meta['keywords']===false){
                $keywords = ($meta['keywords']===false) ? '' : $meta['keywords'];
            }
        } else {
            $title = $this->currentPage->title;
            $description = $this->currentPage->description;
        }
        $return='
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <!-- No Cache -->
        <meta http-equiv="PRAGMA" content="NO-CACHE"/>
        <!-- End No Cache -->
        <title>'.$title.'</title>
        <!-- SEO -->
        <meta http-equiv="Content-Language" content="en" />
        <meta name="title" content="'.$title.'" />
        <meta name="description" content="'.$description.'" />
        <meta name="keywords" content="'.$keywords.'" />
        <meta name="author" content="Top Remodelers" />
        <meta name="robots" content="index,follow" />
        <meta name="revisit-after" content="2 days" />
        <meta name="googlebot" content="index, follow, archive" />
        <meta name="msnbot" content="index, follow" />
        <meta name="YahooSeeker" content="index, follow" />
        <!-- SEO -->
        <!-- GEO -->
        <meta name="geo.region" content="US-TX" />
        <meta name="geo.placename" content="Dallas, Dallas County, Texas, United States" />
        <meta name="geo.position" content="32.723812;-96.816880" />
        <meta name="ICBM" content="32.723812, -96.816880" />
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
        $return='
            <div class="art-bar art-nav clearfix">
                <ul class="art-hmenu">';
        foreach ($this->pages as $page) {
            if($page->navigation==1){
                $class = ($page->id == $this->currentPage->id) ? 'class="active"' : '';
                $return.='<li '.$class.'><a href="'.Config::$site_url.$page->link.'" title="'.$page->name.'">'.$page->name.'</a></li>';
            }
        }
        $return.='</ul></div>';
        return $return;
    }
    public function printReviewContainer(){
        $reviews = new SimpleXMLElement(file_get_contents('xml/review.xml'));
        $return = '<div id="review-container"><div class="carousel left">';
        $i=1;
        // foreach ($reviews as $review) {
        //     $return .= '
        //         <div class="container left" id="reviews-container-'.$i.'">
        //             <div class="header"><span>Review '.$i.'</span></div>
        //             <div class="content-container">
        //                 <p class="title"><span class="strong">Customer in '.$review->location.'</span></p>
        //                 <img alt="'.$review->rating.' stars" src="./images/global/stars/star_'.$review->rating.'.png" height="17" width="72" />
        //                 <p class="project"><span class="strong">Project: '.$review->title.'</span></p>
        //                 <p class="descrption">'.$review->description.'</p>';
        //     if($review->link==1){
        //         $return .= '<p class="read"><a title="Read More" href="'.$review->link.'">Read More</a></p>';
        //     }
        //     $return .= '</div></div>';
        //     $i++;
        // }
        foreach ($reviews as $review) {
            $return .= '
                <div class="container left">
                    <div class="content-container">
                        <h5 class="review-description">'.$review->description.'</h5>
                        <div class="rating-details">
                            <p class="project"><span class="strong"><b>Project</b> - '.$review->title.'</span></p>
                            <div class="rating right">
                                <p class="left"><i class="rating-static rating-'.$review->rating.'"></i></p> - <span class="strong">Customer in '.$review->location.'</span>
                            </div>
                        </div>
                    </div>
                </div>';
            $i++;
        }
        $return .= '
        </div>
        <a id="ui-carousel-next" href="javascipt:void(0);"><span>next</span></a>
        <a id="ui-carousel-prev" href="javascipt:void(0);"><span>previous</span></a>
        </div>
        ';
        return $return;
    }
    public function printUserStepsText($index){
        $return = '<ol class="userStepsText clearfix">';
        $steps=array(
            'Click on a category',
            'Describe your need',
            'Get Matched to Pros'
        );
        for ($i=1;$i<=sizeof($steps);$i++){
            if($i==$index){
                $class='orange-background';
            } else {
                $class='blue-background';
            }
            $return .= '<li class="left"><div class="'.$class.' rounded-corners left">'.$i.'</div><h4>'.$steps[$i-1].'</h4></li>';
        }
        $return .= '</ol>';
        return $return;
    }
    public function printFooter(){
        $return.=$this->printJS('herve_js');
        if($this->currentPage->js == 1){
            $return.=$this->printJS('herve_'.$this->currentPage->class.'_js');
        }
        $return.=$this->printGA();
        //$return.=$this->zopimChat();
        $return.='</body></html>';
        return $return;
    }
    public function printGA(){
        $return="
            <script type=\"text/javascript\">

              var _gaq = _gaq || [];
              _gaq.push(['_setAccount', 'UA-30287515-1']);
              _gaq.push(['_trackPageview']);

              (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
              })();

            </script>";
        return $return;
    }
    public function jumpList($data,$selected){
        if (sizeof($data) <= 1 && $selected!='top') {
            return '';
        }
        $return ='
            <div class="jump-list-container utility-container">
                <ul>';
        foreach ($data as $key=>$value) {
            $return .= '
                <li class="'.$key.'">
                    <a title="'.$value['title'].'" class="'.(($selected==$key) ? 'selected' : '').'" href="#'.$key.'">
                        '.$value['title'].'
                        '.(($selected==$key) ? '<i></i>' : '').'
                    </a>
                </li>
                ';
        }
        if ($selected=='top') {
            $return .= '
                <li class="'.$key.' right">
                    <a class="selected" title="Back to Top" href="#Top">
                        Back to Top
                        <i></i>
                    </a>
                </li>
                ';
        }
        $return .='</ul></div>';
        return $return;
    }
    public function facebookComment($data) {
        return '<div class="fb-comments" data-href="'.$data['href'].'" data-width="'.$data['width'].'" data-num-posts="'.$data['posts'].'"></div>';
    }
}
?>
<?php
class Page{
    protected $title;
    protected $description;
    protected $keywords;
    protected $pages;
    protected $currentPage;
    protected $isMobile;

    public function __construct($pageNumber){
        $useragent=$_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
            $this->isMobile = true;
        } else {
            $this->isMobile = false;
        }
        $this->pages = new SimpleXMLElement(file_get_contents('xml/page.xml'));
        $this->currentPage($pageNumber);
    }
    public function isMobile() {
        return $this->isMobile;
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
        $return='<script async type="text/javascript" src="'.Minify_getUri($name).'"></script>';
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
    public function printReviewContainer($isMobile){
        $review = new Review();
        $reviews = $review->getReviews();
        $return = '';
        $i=1;
        foreach ($reviews as $r) {
            $return .= '
            <div class="review">';
            if (!$isMobile) {
            $return .= '<i class="quote_start hidden-xs"></i>';
            }
            $return .= '
            <h4>Project : '.$r['project'].'</h4>
            <h5 class="blue">Customer in '.$r['region'].'</h5>
            <p>'.$r['description'].'</p>';
            if (!$isMobile) {
            $return .= '<i class="quote_end hidden-xs"></i>';
            }
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
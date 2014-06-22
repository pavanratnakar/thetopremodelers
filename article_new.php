<?php
    if( !$_GET['article'] ){
        header( 'Location: index.php');
        exit;
    }
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    include_once(Config::$site_path.'controller/pageController.php');
    $pageController=new PageController(6);
    $articleName = $pageController->getUtils()->checkValues($_GET['article']);
    $article = $pageController->getArticle();
    $articleContent = $article->getArticleDetailsByName($articleName);
    if (!$articleContent) {
        header( 'Location: '.Config::$site_url.'404.php');
        exit;
    }
    echo $pageController->printHomeHeader($pageController->getMeta('article',$articleName));
?>
       <div class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-collapse collapse">
                    <div class="nav-contact">
                        <h4 class="gold">Need immediate service</h4>
                        <h5>CALL US : 1(214)303 9771</h5>
                    </div>
                    <?php echo $pageController->printHomeNavigation(); ?>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row main-container">
                <div class="col-md-3 col-xs-3 col-sm-3 sidebar">
                    <div class="nav-logo-container">
                        <div class="logo-container">
                            <a href="http://www.thetopremodelers.com">
                                <img src="images/global/logo.png" alt="The Top Remodelers"/>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="container full-main">
                    <div class="sub">
                        <h2><?php echo $articleContent['title']; ?></h2>
                        <?php echo $articleContent['content']; ?>
                    </div>
                    <div class="comment-box">
                        <h2>Comment</h2>
                        <?php echo $pageController->facebookComment(array(
                            'href' => Config::$site_url.'article/'. $articleName,
                            'width' => '900',
                            'posts' => '10'
                        )); ?>
                    </div>
                </div>
            </div>
            <!-- FOOTER -->
            <?php echo $pageController->printHomeFooterLinks(); ?>
        </div>
        <?php echo $pageController->printHomeFooter(); ?>
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
        <?php echo $pageController->printHeaderMenu(); ?>
        <div class="container-fluid">
            <div class="row main-container">
                <div class="col-md-3 col-xs-3 col-sm-3 sidebar">
                    <?php echo $pageController->printLogoContainer(); ?>
                </div>
                <div class="container full-main">
                    <div class="sub">
                        <h1><?php echo $articleContent['title']; ?></h1>
                        <?php echo $articleContent['content']; ?>
                    </div>
                    <div class="comment-box">
                        <h1>Comment</h1>
                        <div class="comment-section">
                            <?php echo $pageController->facebookComment(array(
                                'href' => Config::$site_url.'article/'. $articleName,
                                'width' => '900',
                                'posts' => '10'
                            )); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FOOTER -->
            <?php echo $pageController->printHomeFooterLinks(); ?>
        </div>
        <?php echo $pageController->printHomeFooter(); ?>
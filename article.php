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
    echo $pageController->printHeader($pageController->getMeta('article',$articleName));
?>
            <?php echo $pageController->printNavigation(); ?>
            <div class="main-content-container clearfix">
                <div class="content clearfix">
                    <div class="main left">
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
                <?php echo $pageController->printFooterLinks(); ?>
            </div>
            <div class="clear"></div>
        </div>
<?php echo $pageController->printFooter(); ?>
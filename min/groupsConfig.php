<?php
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 */

/**
 * You may wish to use the Minify URI Builder app to suggest
 * changes. http://yourdomain/min/builder/
 *
 * See http://code.google.com/p/minify/wiki/CustomSource for other ideas
 **/

$herve_global_js = array(
    '//global/js/gtm.js', // should be add it here?
    '//global/js/ga.js', // should be add it here?
    '//global/js/jquery/jquery-1.7.1.min.js',
    '//global/js/jquery/jquery.unveil.js',
    '//global/js/bootstrap.min.js',
    '//global/js/config.js',
    '//global/js/sharethis/button.js',
    '//js/social.js',
    '//js/global.js'
);

return array(
    ///HERVE ///
    'herve_admin_css' => array(
        '//global/themes/south-street/jquery-ui-1.9.2.custom.css',
        '//global/js/plugins/jqgrid/css/ui.jqgrid.css',
        '//global/js/plugins/jqgrid/plugins/ui.multiselect.css',
        '//global/css/style.css',
        '//admin/css/style.css'
    ),
    'herve_admin_js' => array(
        '//global/js/jquery/jquery-1.7.1.min.js',
        '//global/js/jquery/jquery.ajax.js',
        '//global/js/plugins/jquery-ui-1.9.2/js/jquery-ui-1.9.2.custom.min.js',
        '//global/js/jquery/jquery.layout.js',
        '//global/js/plugins/jqgrid/js/i18n/grid.locale-en.js',
        '//global/js/config.js',
        '//admin/js/config.js',
        '//global/js/plugins/jqgrid/js/jquery.jqGrid.min.js',
        '//global/js/themeroller.js',
        '//admin/js/herve_grid.js'
    ),
    'herve_question_css' => array(
        '//global/css/style.css',
        '//css/style.css'
    ),
    'herve_promotion_css' => array(
        '//global/js/plugins/bigvideo/css/bigvideo.css'
    ),
    'herve_css' => array(
        '//global/css/bootstrap/bootstrap.min.css',
        '//css/style.css'
    ),
    'herve_place-request_js' => array_merge(
        $herve_global_js,
        array(
            '//global/js/jquery/jquery.ajax.js',
            '//global/js/jquery/jquery.validate.min.js',
            '//global/js/jquery/jquery.ajax.loader.js',
            '//global/js/global.js',
            '//js/contact.js'
        )
    ),
    'herve_article_js' => array_merge(
        $herve_global_js,
        array(
            '//js/article.js'
        )
    ),
    'herve_js' => array(
        '//global/js/jquery/jquery-1.7.1.min.js',
        '//global/js/config.js',
        '//global/js/sharethis/button.js',
        '//js/social.js',
         '//js/init.js'
    ),
    'herve_global_js' => $herve_global_js,
    'herve_need_js' => array_merge(
        $herve_global_js,
        array(
            '//global/js/jquery/jquery.validate.min.js',
            '//js/question.js'
        )
    ),
    'herve_contractors_js' => array_merge(
        $herve_global_js,
        array(
            '//js/contractors.js'
        )
    ),
    'herve_contractor_css' => array(
        '//global/js/plugins/boxy/stylesheets/boxy.css',
    ),
    'herve_contractor_js' => array_merge(
        $herve_global_js,
        array(
            '//global/js/plugins/boxy/javascripts/jquery.boxy.js',
            '//js/contractor.js'
        )
    ),
    'herve_promotion_js' => array_merge(
        $herve_global_js,
        array(
            '//global/js/plugins/bigvideo/lib/video.js',
            '//global/js/plugins/bigvideo/lib/jquery-ui.js',
            '//global/js/plugins/bigvideo/lib/bigvideo.js',
            '//js/promotion.js'
        )
    ),
    /// HERVE ///
    /// NEW ADMIN ///
    'herve_new_admin_js' => array(
        '//global/js/jquery/jquery-1.7.1.min.js',
        '//global/js/underscore.min.js',
        '//global/js/backbone.min.js',
        '//global/js/bootstrap.min.js',
        '//admin_new/js/utils.js',
        '//admin_new/js/models/articleModels.js',
        '//admin_new/js/models/categoryModels.js',
        '//admin_new/js/models/placeModels.js',
        '//admin_new/js/models/reviewModels.js',
        '//admin_new/js/models/metaModels.js',
        '//admin_new/js/models/contractorModels.js',
        '//admin_new/js/models/contractorReviewModels.js',
        '//admin_new/js/models/contractorMappingModels.js',
        '//admin_new/js/models/sectionModels.js',
        '//admin_new/js/models/articleMappingModels.js',
        '//admin_new/js/views/paginator.js',
        '//admin_new/js/views/header.js',
        '//admin_new/js/views/reviewlist.js',
        '//admin_new/js/views/reviewdetails.js',
        '//admin_new/js/views/metalist.js',
        '//admin_new/js/views/metadetails.js',
        '//admin_new/js/views/contractorlist.js',
        '//admin_new/js/views/contractordetails.js',
        '//admin_new/js/views/contractorreviewlist.js',
        '//admin_new/js/views/contractorreviewdetails.js',
        '//admin_new/js/views/contractormappingdetails.js',
        '//admin_new/js/views/contractormappingsdetails.js',
        '//admin_new/js/views/articlemappingsdetails.js',
        '//admin_new/js/main.js'
    ),
    'herve_new_admin_css' => array(
        '//global/css/bootstrap/bootstrap.min.css',
        '//admin_new/css/styles.css'
    ),
    'herve_admin_login_css' => array(
        '//global/css/bootstrap/bootstrap.min.css',
        '//admin_new/css/login.css'
    )
    /// NEW ADMIN ///
);
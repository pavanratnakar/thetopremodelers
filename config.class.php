<?php
class Config{
    static $root_path  = '/home/manuela6264/public_html/';
    static $admin_path = '/home/manuela6264/public_html/admin/';
    static $site_path  = '/home/manuela6264/public_html/';
    static $site_url  = 'http://www.topremodelers.com/';

    // static $root_path  = 'H:/WEB/WEBSITE/thetopremodelers/thetopremodelers/';
    // static $admin_path = 'H:/WEB/WEBSITE/thetopremodelers/thetopremodelers/admin/';
    // static $site_path  = 'H:/WEB/WEBSITE/thetopremodelers/thetopremodelers/';
    // static $site_url  = 'http://thetopremodelers.localhost/';

    // static $root_path  = '/Applications/XAMPP/xamppfiles/htdocs/thetopremodelers/';
    // static $admin_path = '/Applications/XAMPP/xamppfiles/htdocs/thetopremodelers/admin/';
    // static $site_path  = '/Applications/XAMPP/xamppfiles/htdocs/thetopremodelers/';
    // static $site_url  = 'http://thetopremodelers.localhost/';

    /* DB CONFIG */
    static $db_server='184.168.154.89';
    static $db_username='newtopremodelers';
    static $db_password='Herve28031986';

    // static $db_server='localhost';
    // static $db_username='root';
    // static $db_password='';

    static $db_database='newtopremodelers';
    static $paginationLimit=30;
    /* DB CONFIG */

    /* TABLES */
    static $tables = array(
        'place_table'=>'rene_place',
        'placeCode_table'=>'rene_placecode',
        'category_table'=>'rene_category',
        'section_table'=>'rene_section',
        'question_table' => 'rene_questions',
        'question_category' => 'rene_question_category',
        'sectionQuestion_table' => 'rene_sectionquestion_mapping',
        'answer_table' => 'rene_answers',
        'questionAnswer_table' => 'rene_questionanwer_mapping',
        'article_table' => 'rene_article',
        'articleCategory_table' => 'rene_article_mapping',
        'contractor_table' => 'rene_contractor',
        'contractorMapping_table' => 'rene_contractor_mapping',
        'contractorRating_table' => 'rene_contractor_rating',
        'contractorImage_table' => 'rene_contractor_image',
        'meta_table' => 'rene_meta',
        'review_table' => 'rene_review'
    );
    /* TABLES */
}
?>
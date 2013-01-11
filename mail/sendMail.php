<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
include_once (Config::$site_path.'class/mailClass.php');
include_once (Config::$site_path.'global/Class/utils.class.php');
include_once(Config::$site_path.'class/question.class.php');
include_once(Config::$site_path.'class/category.class.php');
include_once(Config::$site_path.'class/section.class.php');
include_once(Config::$site_path.'class/place.class.php');
include_once(Config::$site_path.'class/contractor.class.php');
$utils = new Utils();
$mail=new MailClass();
$question = new Question();
$category = new Category();
$message = '';
if ($_REQUEST['categoryName']) {
    $categoryName = $utils->checkValues($_REQUEST['categoryName']);
    $placeName = $utils->checkValues($_GET['placeName']);
    $contractorName = $utils->checkValues($_GET['contractorName']);
    $place = new Place();
    $placeDetails = $place->getPlaceDetails($placeName);
    $section = new Section($categoryName,$placeName);
    $contractor = new Contractor();
    $contractorDetails = $contractor->getContractor($contractorName);
    $questionPair = explode(',',$utils->checkValues($_REQUEST['questionPair']));
    $answerPair = explode(',',$utils->checkValues($_REQUEST['answerPair']));
    $message .= '<hr/><b>Place</b> : '.$placeDetails['place_title'].'<hr/>';
    $message .= '<hr/><b>Category</b> : '.$category->getCategoryValueByName($categoryName).'<hr/>';
    $message .= '<hr/><b>Section</b> : '.$section->getSectionTitleByName($utils->checkValues($_REQUEST['sectionName'])).'<hr/>';
    $message .= '<hr/><b>Contractor</b> : '.$contractorDetails['contractor_title'].'<hr/>';
} else {
    $categoryName = null;
}
for($i=0;$i<sizeof($questionPair);$i++){
    $questionAnswerArray[$questionPair[$i]]=$answerPair[$i];
}
$message .='   
        <hr/><b>Customer Details</b><hr/>
        <b>Email</b>            :   '.$utils->checkValues($_REQUEST['email']).'<br />
        <b>Name</b>             :   '.$utils->checkValues($_REQUEST['name']).'<br />
        <b>Address</b>          :   '.$utils->checkValues($_REQUEST['address']).'<br />
        <b>City</b>             :   '.$utils->checkValues($_REQUEST['city']).'<br />
        <b>Zip</b>              :   '.$utils->checkValues($_REQUEST['zip']).'<br />
        <b>Phone</b>            :   '.$utils->checkValues($_REQUEST['phone']).'<br />
        <b>Contact Time</b>     :   '.$utils->checkValues($_REQUEST['contactTime']).'<br />
        <b>Message</b>          :   '.nl2br($utils->checkValues($_REQUEST['message'])).'<br />
        <b>Subscribe</b>        :   '.$utils->checkValues($_REQUEST['subscribe']).'<br />';
if($categoryName){
    $message .= '
            <br/><hr/><b>Questions Answered</b><hr/>
            '.$question->formatQuestionResponse($questionAnswerArray);
}
$message .= '
        <br/><hr/><b>Customer Details For Security</b><hr/>
        <b>IP Address Used</b> :  '.$utils->ip_address_to_number($_SERVER['REMOTE_ADDR']).'<br /><br/>
        ';
echo $message;
exit;
$status=$mail->sendMailFunction(
    'Customer Interest Mail : Estimates Required',
    $message
);
$returnArray= array(
    "status" => $status
);
$response = $_POST["jsoncallback"] . "(" .json_encode($returnArray). ")";
echo $response;
unset($response);
?>
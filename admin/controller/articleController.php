<?php
class articleController
{
    private $utils;
    private $page;
    private $limit;
    private $sidx;
    private $sord;
    private $searchOn;
    private $article;
    public function __construct($ref=null)
    {
        include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
        include_once(Config::$site_path.'global/Class/utils.class.php');
        include_once(Config::$admin_path.'/class/article.class.php');
        $this->utils=new Utils();
        $this->article=new Article('article');
        $this->page = $this->utils->checkValues($_REQUEST['page']); // get the requested page
        $this->limit = $this->utils->checkValues($_REQUEST['rows']); // get how many rows we want to have into the grid
        $this->sidx = $this->utils->checkValues($_REQUEST['sidx']); // get index row - i.e. user click to sort
        $this->sord = $this->utils->checkValues( $_REQUEST['sord']); // get the direction
        $this->searchOn = $this->utils->checkValues($_REQUEST['_search']); // search
        $this->$ref();
    }
    public function details()
    {
        if(isset($_REQUEST['_search']) && $this->searchOn!='false') {
            $fld =  $this->utils->checkValues($_REQUEST['searchField']);
            if( $fld=='name' || $fld=='title' || $fld=='keywords' || $fld=='description' || $fld=='content' || $fld=='active' ) {
                $fldata =  $this->utils->checkValues($_REQUEST['searchString']);
                $foper =  $this->utils->checkValues($_REQUEST['searchOper']);
                // costruct where
                $wh .= " AND ".$fld;
                switch ($foper) {
                    case "bw":
                        $fldata .= "%";
                        $wh .= " LIKE '".$fldata."'";
                        break;
                    case "eq":
                        if(is_numeric($fldata)) {
                            $wh .= " = ".$fldata;
                        } else {
                            $wh .= " = '".$fldata."'";
                        }
                        break;
                    case "ne":
                        if(is_numeric($fldata)) {
                            $wh .= " <> ".$fldata;
                        } else {
                            $wh .= " <> '".$fldata."'";
                        }
                        break;
                    case "lt":
                        if(is_numeric($fldata)) {
                            $wh .= " < ".$fldata;
                        } else {
                            $wh .= " < '".$fldata."'";
                        }
                        break;
                    case "le":
                        if(is_numeric($fldata)) {
                            $wh .= " <= ".$fldata;
                        } else {
                            $wh .= " <= '".$fldata."'";
                        }
                        break;
                    case "gt":
                        if(is_numeric($fldata)) {
                            $wh .= " > ".$fldata;
                        } else {
                            $wh .= " > '".$fldata."'";
                        }
                        break;
                    case "ge":
                        if(is_numeric($fldata)) {
                            $wh .= " >= ".$fldata;
                        } else {
                            $wh .= " >= '".$fldata."'";
                        }
                        break;
                    case "ew":
                        $wh .= " LIKE '%".$fldata."'";
                        break;
                    case "cn":
                        $wh .= " LIKE '%".$fldata."%'";
                        break;
                    default :
                        $wh = "";
                }
            }
        } else {
            if(!$this->sidx) {
                $this->sidx =1;
            }
            $totalrows = isset($_REQUEST['totalrows']) ? $this->utils->checkValues($_REQUEST['totalrows']): false;
            if($totalrows) {
                $this->limit = $totalrows;
            }
            $wh="";
        }
        $response=$this->article->getDetails($this->page,$this->limit,$this->sidx,$this->sord,$wh);
        echo json_encode($response);
        unset($response);
    }
    public function operation()
    {
        $oper=$this->utils->checkValues($_REQUEST['oper']);
        /* ADD */
        if($oper=='add'){
            $response=$this->article->addDetails(
                $this->utils->checkValues($_POST['name']),
                $this->utils->checkValues($_POST['title']),
                $this->utils->checkValues($_POST['keywords']),
                $this->utils->checkValues($_POST['description']),
                $_POST['content'],
                $this->utils->checkValues($_POST['active'])
            );
            if($response){
                $status=TRUE;
                $message="Details Added";
            }else{
                $status=FALSE;
                $message="Details could not be added";
            }
        }
        /* ADD */
        /* EDIT */
        else if($oper=='edit'){
            $response=$this->article->editDetails(
                $this->utils->checkValues($_POST['name']),
                $this->utils->checkValues($_POST['title']),
                $this->utils->checkValues($_POST['keywords']),
                $this->utils->checkValues($_POST['description']),
                $_POST['content'],
                $this->utils->checkValues($_POST['active']),
                $this->utils->checkValues($_POST['id'])
            );
            if($response){
                $status=TRUE;
                $message="Details Edited";
            }else{
                $status=FALSE;
                $message="Details could not be edited";
            }
        }
        /* EDIT */
        /* DELETE */
        else if($oper=='del'){
            $response=$this->article->deleteDetails($this->utils->checkValues($_POST['id']));
            if($response){
                $status=TRUE;
                $message="Details Deleted";
            }else{
                $status=FALSE;
                $message="Details could not be deleted";
            }
        }
        /* DELETE */
        $returnArray= array(
            "status" => $status,
            "message" => $message
        );
        $response = $_POST["jsoncallback"] . "(" . json_encode($returnArray) . ")";
        echo $response;
        unset($response);
    }
}
if(isset($_REQUEST['ref']))
{
    $articleController=new articleController($_REQUEST['ref']);
}
?>
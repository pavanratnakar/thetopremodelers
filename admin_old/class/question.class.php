<?php
include_once(Config::$site_path.'admin/class/general.class.php');
class Question extends General
{
    public function getSelect()
    {
        $this->select_type = $this->type."_text";
        $i=1;
        $query =  "SELECT ".$this->type."_id as id, ".$this->select_type." as name FROM ".$this->table." WHERE delete_flag=FALSE ORDER BY ".$this->select_type;
        if ($result = $this->mysqli->query($query)) 
        {
            while ($row = $result->fetch_object())
            {
                $return_array .= $row->id.':'.$row->name;
                if($result->num_rows!=$i)
                {
                    $return_array .=';';
                }
                $i++;
            }
        }
        return $return_array;
    }
    public function getSelectType()
    {
        $i=1;
        if ($result = $this->mysqli->query("SELECT *FROM ".Config::$tables['question_category']." WHERE delete_flag=FALSE ORDER BY category_text")) 
        {
            while ($row = $result->fetch_object())
            {
                $return_array .= $row->category_id.':'.$row->category_text;
                if($result->num_rows!=$i)
                {
                    $return_array .=';';
                }
                $i++;
            }
        }
        return $return_array;
    }
    public function getDetails($page,$limit,$sidx,$sord,$wh="")
    {
        $page=$this->mysqli->real_escape_string($page);
        $limit=$this->mysqli->real_escape_string($limit);
        $sidx=$this->mysqli->real_escape_string($sidx);
        $sord=$this->mysqli->real_escape_string($sord);
        if ($result = $this->mysqli->query("SELECT COUNT(*) AS count FROM ".$this->table." WHERE delete_flag=FALSE ".$wh)){
            while ($row = $result->fetch_object()){
                $count = $row->count;
                if( $count >0 ){
                    $total_pages = ceil($count/$limit);
                }else{
                    $total_pages = 0;
                }
                if ($page > $total_pages){
                    $page=$total_pages;
                }
                $start = $limit*$page - $limit; // do not put $limit*($page - 1)
                if ($start<0){
                    $start = 0;
                }
                $query="SELECT a.question_id,a.question_text,a.question_validation,b.category_text
                    FROM 
                    ".$this->table." a 
                    LEFT JOIN 
                    ".Config::$tables['question_category']." b ON a.question_type=b.category_id
                    WHERE a.delete_flag=0 ".$wh."
                    ORDER BY ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
                $result1 = $this->mysqli->query($query);
                if ($result1) {
                    $responce->page = $page;
                    $responce->total = $total_pages;
                    $responce->records = $count;
                    $i=0;
                    while ($row1 = $result1->fetch_object()) {
                        $responce->rows[$i]['question_id']=$row1->question_id;
                        $responce->rows[$i]['cell'] =array($row1->question_id,$row1->question_text,$row1->category_text,$row1->question_validation);
                        $i++;
                    }
                    return $responce;
                }
            }
        }
    }
    public function addDetails($question_text,$question_type,$question_validation)
    {
        $question_text=$this->mysqli->real_escape_string($question_text);
        $question_type=$this->mysqli->real_escape_string($question_type);
        $question_validation=$this->mysqli->real_escape_string($question_validation);
        $result = $this->mysqli->query("INSERT INTO ".$this->table."(question_text,question_type,question_validation) VALUES('$question_text','$question_type','$question_validation')");
        if ($result) {
            if($this->mysqli->affected_rows>0) {
                return TRUE;
            }
        }
        return FALSE;
    }
    public function editDetails($question_text,$question_type,$question_validation,$question_id)
    {
        $question_text=$this->mysqli->real_escape_string($question_text);
        $question_type=$this->mysqli->real_escape_string($question_type);
        $question_id=$this->mysqli->real_escape_string($question_id);
        $result = $this->mysqli->query("UPDATE ".$this->table." SET question_text='$question_text',question_type='$question_type',question_validation='$question_validation' WHERE question_id='$question_id'");
        if ($result){
            if($this->mysqli->affected_rows>0){
                return TRUE;
            }
        }
        return FALSE;
    }
}
?>
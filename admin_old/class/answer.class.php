<?php
include_once(Config::$site_path.'admin/class/general.class.php');
class Answer extends General
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
                $query="SELECT a.answer_id,a.answer_text
                    FROM 
                    ".$this->table." a 
                    WHERE a.delete_flag=0 ".$wh."
                    ORDER BY ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
                $result1 = $this->mysqli->query($query);
                if ($result1) {
                    $responce->page = $page;
                    $responce->total = $total_pages;
                    $responce->records = $count;
                    $i=0;
                    while ($row1 = $result1->fetch_object()) {
                        $responce->rows[$i]['answer_id']=$row1->answer_id;
                        $responce->rows[$i]['cell'] =array($row1->answer_id,$row1->answer_text);
                        $i++;
                    }
                    return $responce;
                }
            }
        }
    }
    public function addDetails($answer_text)
    {
        $answer_text=$this->mysqli->real_escape_string($answer_text);
        $result = $this->mysqli->query("INSERT INTO ".$this->table."(answer_text) VALUES('$answer_text')");
        if ($result) {
            if($this->mysqli->affected_rows>0) {
                return TRUE;
            }
        }
        return FALSE;
    }
    public function editDetails($answer_text,$answer_id)
    {
        $answer_text=$this->mysqli->real_escape_string($answer_text);
        $answer_id=$this->mysqli->real_escape_string($answer_id);
        $result = $this->mysqli->query("UPDATE ".$this->table." SET answer_text='$answer_text' WHERE answer_id='$answer_id'");
        if ($result){
            if($this->mysqli->affected_rows>0){
                return TRUE;
            }
        }
        return FALSE;
    }
}
?>
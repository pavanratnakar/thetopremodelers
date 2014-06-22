<?php
include_once(Config::$site_path.'admin/class/general.class.php');
class SectionQuestion extends General
{
    public function getDetails($page,$limit,$sidx,$sord,$wh="")
    {
        $page=$this->mysqli->real_escape_string($page);
        $limit=$this->mysqli->real_escape_string($limit);
        $sidx=$this->mysqli->real_escape_string($sidx);
        $sord=$this->mysqli->real_escape_string($sord);
        if ($result = $this->mysqli->query("SELECT COUNT(*) AS count FROM ".$this->table." WHERE 1=1 ".$wh)){
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
                $query="SELECT a.id,c.section_name,d.category_name,b.question_text,a.question_order
                    FROM 
                    ".$this->table." a
                    LEFT JOIN
                    ".Config::$tables['question_table']." b ON a.question_id=b.question_id
                    LEFT JOIN 
                    ".Config::$tables['section_table']." c ON c.section_id=a.section_id
                    LEFT JOIN 
                    ".Config::$tables['category_table']." d ON d.category_id=a.category_id
                    WHERE 1=1
                    ORDER BY ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
                $result1 = $this->mysqli->query($query);
                if ($result1) {
                    $responce->page = $page;
                    $responce->total = $total_pages;
                    $responce->records = $count;
                    $i=0;
                    while ($row1 = $result1->fetch_object()) {
                        $responce->rows[$i]['id']=$row1->id;
                        $responce->rows[$i]['cell'] =array($row1->id,$row1->section_name,$row1->category_name,$row1->question_text,$row1->question_order);
                        $i++;
                    }
                    return $responce;
                }
            }
        }
    }
    public function addDetails($question_id,$section_id,$category_id,$question_order)
    {
        $question_id=$this->mysqli->real_escape_string($question_id);
        $section_id=$this->mysqli->real_escape_string($section_id);
        $category_id=$this->mysqli->real_escape_string($category_id);
        $order=$this->mysqli->real_escape_string($order);
        $result = $this->mysqli->query("INSERT INTO ".$this->table."(question_id,section_id,category_id,order) VALUES('$question_id','$section_id','$category_id','$question_order')");
        if ($result) {
            if($this->mysqli->affected_rows>0) {
                return TRUE;
            }
        }
        return FALSE;
    }
    public function editDetails($question_id,$section_id,$category_id,$question_order,$id)
    {
        $question_id=$this->mysqli->real_escape_string($question_id);
        $section_id=$this->mysqli->real_escape_string($section_id);
        $category_id=$this->mysqli->real_escape_string($category_id);
        $order=$this->mysqli->real_escape_string($order);
        $id=$this->mysqli->real_escape_string($id);
        $result = $this->mysqli->query("UPDATE ".$this->table." SET question_id='$question_id',section_id='$section_id',category_id='$category_id',question_order='$question_order' WHERE id='$id'");
        if ($result){
            if($this->mysqli->affected_rows>0){
                return TRUE;
            }
        }
        return FALSE;
    }
    public function deleteDetails($id)
    {
        $id=$this->mysqli->real_escape_string($id);
        if ($result = $this->mysqli->query("DELETE FROM ".$this->table." WHERE id='".$id."'")) {
            if($this->mysqli->affected_rows>0) {
                return TRUE;
            }
        }
        return FALSE;
    }
}
?>
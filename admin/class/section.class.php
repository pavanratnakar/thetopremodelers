<?php
include_once(Config::$site_path.'admin/class/general.class.php');
class Section extends General
{
    public function getDetails($page,$limit,$sidx,$sord,$wh="")
    {
        $page=$this->mysqli->real_escape_string($page);
        $limit=$this->mysqli->real_escape_string($limit);
        $sidx=$this->mysqli->real_escape_string($sidx);
        $sord=$this->mysqli->real_escape_string($sord);
        $query = "SELECT COUNT(*) AS count FROM ".$this->table." WHERE delete_flag=FALSE ".$wh;
        if ($result = $this->mysqli->query($query)){
            while ($row = $result->fetch_object()){
                $count = $row->count;
                if ($count > 0) {
                    $total_pages = ceil($count/$limit);
                } else {
                    $total_pages = 0;
                }
                if ($page > $total_pages) {
                    $page=$total_pages;
                }
                $start = $limit*$page - $limit; // do not put $limit*($page - 1)
                if ($start<0) {
                    $start = 0;
                }
                $query="SELECT a.section_id,a.section_name,a.section_title
                    FROM 
                    ".$this->table." a 
                    WHERE 
                    a.delete_flag=FALSE 
                    ".$wh."
                    ORDER BY ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;

                $result1 = $this->mysqli->query($query);
                if ($result1) {
                    $responce->page = $page;
                    $responce->total = $total_pages;
                    $responce->records = $count;
                    $i=0;
                    while ($row1 = $result1->fetch_object()) {
                        $responce->rows[$i]['section_id']=$row1->section_id;
                        $responce->rows[$i]['cell'] =array($row1->section_id,$row1->section_name,$row1->section_title);
                        $i++;
                    }
                    return $responce;
                }
            }
        }
    }
    public function addDetails($section_name,$section_title){
        $section_name=$this->mysqli->real_escape_string($section_name);
        $section_title=$this->mysqli->real_escape_string($section_title);
        $result = $this->mysqli->query("INSERT INTO ".$this->table."(section_name,section_title) VALUES('$section_name','$section_title')");
        if ($result) {
            if ($this->mysqli->affected_rows>0) {
                return TRUE;
            }
        }
        return FALSE;
    }
    public function editDetails($section_name,$section_title,$section_id){
        $section_name=$this->mysqli->real_escape_string($section_name);
        $section_title=$this->mysqli->real_escape_string($section_title);
        $id=$this->mysqli->real_escape_string($id);
        $result = $this->mysqli->query("UPDATE ".$this->table." SET section_name='$section_name',section_title='$section_title' WHERE ".$this->id."='".$section_id."'");
        if ($result){
            if ($this->mysqli->affected_rows>0) {
                return TRUE;
            }
        }
        return FALSE;
    }
}
?>
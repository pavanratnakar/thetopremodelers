<?php
include_once(Config::$site_path.'admin/class/general.class.php');
class Place extends General{
    public function getDetails($page,$limit,$sidx,$sord,$wh=""){
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
                if ($start < 0) {
                    $start = 0;
                }
                $query="SELECT a.place_id,a.place_title,a.place_name,a.under,a.active
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
                        $responce->rows[$i]['place_id']=$row1->place_id;
                        $responce->rows[$i]['cell'] =array($row1->place_id,$row1->place_name,$row1->place_title,$row1->under,$row1->active);
                        $i++;
                    }
                    return $responce;
                }
            }
        }
    }
    public function addDetails($place_name,$place_title,$under,$active){
        $place_name=$this->mysqli->real_escape_string($place_name);
        $place_title=$this->mysqli->real_escape_string($place_title);
        $under=$this->mysqli->real_escape_string($under);
        $active=$this->mysqli->real_escape_string($active);
        $result = $this->mysqli->query("INSERT INTO ".$this->table."(place_name,place_title,under,active) VALUES('$place_name','$place_title','$under','$active')");
        if ($result) {
            if($this->mysqli->affected_rows>0){
                return TRUE;
            }
        }
        return FALSE;
    }
    public function editDetails($place_name,$place_title,$under,$active,$id){
        $place_name=$this->mysqli->real_escape_string($place_name);
        $place_title=$this->mysqli->real_escape_string($place_title);
        $under=$this->mysqli->real_escape_string($under);
        $active=$this->mysqli->real_escape_string($active);
        $id=$this->mysqli->real_escape_string($id);
        $result = $this->mysqli->query("UPDATE ".$this->table." SET place_name='$place_name', place_title='$place_title',under='$under',active='$active' WHERE ".$this->id."='".$id."'");
        if ($result){
            if($this->mysqli->affected_rows>0){
                return TRUE;
            }
        }
        return FALSE;
    }
}
?>
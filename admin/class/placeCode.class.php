<?php
include_once(Config::$site_path.'admin/class/general.class.php');
class PlaceCode extends General{
    public function getDetails($page,$limit,$sidx,$sord,$wh=""){
        $page=$this->mysqli->real_escape_string($page);
        $limit=$this->mysqli->real_escape_string($limit);
        $sidx=$this->mysqli->real_escape_string($sidx);
        $sord=$this->mysqli->real_escape_string($sord);
        $query = "SELECT COUNT(*) AS count FROM ".$this->table." WHERE delete_flag=FALSE ".$wh;
        if ($result = $this->mysqli->query($query)){
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
                $query="SELECT a.placeCode_id,a.code,b.place_title,a.type
                    FROM 
                    ".$this->table." a
                    LEFT JOIN 
                    ".Config::$tables['place_table']." b ON b.place_id=a.place_id
                    WHERE 
                    a.delete_flag=FALSE 
                    AND b.delete_flag=FALSE
                    ".$wh."
                    ORDER BY ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
                $result1 = $this->mysqli->query($query);
                if ($result1) {
                    $responce->page = $page;
                    $responce->total = $total_pages;
                    $responce->records = $count;
                    $i=0;
                    while ($row1 = $result1->fetch_object()) {
                        $responce->rows[$i]['placeCode_id']=$row1->placeCode_id;
                        $responce->rows[$i]['cell'] =array($row1->placeCode_id,$row1->code,$row1->place_title,$row1->type);
                        $i++;
                    }
                    return $responce;
                }
            }
        }
    }
    public function addDetails($code,$place_id,$type){
        $code=$this->mysqli->real_escape_string($code);
        $place_id=$this->mysqli->real_escape_string($place_id);
        $type=$this->mysqli->real_escape_string($type);
        $result = $this->mysqli->query("INSERT INTO ".$this->table."(code,place_id,type) VALUES('$code','$place_id','$type')");
        if ($result) {
            if($this->mysqli->affected_rows>0){
                return TRUE;
            }
        }
        return FALSE;
    }
    public function editDetails($code,$place_id,$type,$id){
        $code=$this->mysqli->real_escape_string($code);
        $place_id=$this->mysqli->real_escape_string($place_id);
        $type=$this->mysqli->real_escape_string($type);
        $id=$this->mysqli->real_escape_string($id);
        $query = "UPDATE ".$this->table." SET code='$code', place_id='$place_id',type='$type' WHERE ".$this->id."='".$id."'";
        echo $query;
        $result = $this->mysqli->query($query);
        if ($result){
            if($this->mysqli->affected_rows>0){
                return TRUE;
            }
        }
        return FALSE;
    }
}
?>
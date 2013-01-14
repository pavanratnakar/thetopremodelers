<?php
include_once(Config::$site_path.'admin/class/general.class.php');
class contractorImage extends General{
    public function getDetails($page,$limit,$sidx,$sord,$wh=""){
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
                $query="SELECT a.contractorImage_id,a.image_id,b.contractor_title,a.type
                    FROM 
                    ".$this->table." a
                    LEFT JOIN
                    ".Config::$tables['contractor_table']." b ON b.contractor_id=a.contractor_id
                    WHERE 
                    a.delete_flag=FALSE 
                    AND b.delete_flag=FALSE
                    ".$wh."
                    ORDER BY ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
                    //echo $query;
                $result1 = $this->mysqli->query($query);
                if ($result1) {
                    $responce->page = $page;
                    $responce->total = $total_pages;
                    $responce->records = $count;
                    $i=0;
                    while ($row1 = $result1->fetch_object()) {
                        $responce->rows[$i]['contractorImage_id']=$row1->contractorImage_id;
                        $responce->rows[$i]['cell'] =array($row1->contractorImage_id,$row1->image_id,$row1->type,$row1->contractor_title);
                        $i++;
                    }
                    return $responce;
                }
            }
        }
    }
    public function addDetails($contractor_id,$image_id,$type){
        $contractor_id=$this->mysqli->real_escape_string($contractor_id);
        $image_id=$this->mysqli->real_escape_string($image_id);
        $type=$this->mysqli->real_escape_string($type);
        $query = "INSERT INTO ".$this->table."(contractor_id,image_id,type) VALUES('$contractor_id','$image_id','$type')";
        echo $query;
        $result = $this->mysqli->query($query);
        if ($result){
            if ($this->mysqli->affected_rows>0) {
                return TRUE;
            }
        }
        return FALSE;
    }
    public function editDetails($contractor_id,$image_id,$type,$id){
        $contractor_id=$this->mysqli->real_escape_string($contractor_id);
        $image_id=$this->mysqli->real_escape_string($image_id);
        $type=$this->mysqli->real_escape_string($type);
        $id=$this->mysqli->real_escape_string($id);
        $result = $this->mysqli->query("UPDATE ".$this->table." SET contractor_id='$contractor_id',image_id='$image_id',type='$type' WHERE ".$this->id."='$id'");
        if ($result){
            if($this->mysqli->affected_rows>0){
                return TRUE;
            }
        }
        return FALSE;
    }
}
?>
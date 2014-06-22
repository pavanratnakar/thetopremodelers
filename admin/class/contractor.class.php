<?php
include_once(Config::$site_path.'admin/class/general.class.php');
class Contractor extends General{
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
                $query="SELECT a.contractor_id,a.contractor_title,a.contractor_description,a.contractor_phone,a.contractor_address,a.contractor_name
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
                        $responce->rows[$i]['contractor_id']=$row1->contractor_id;
                        $responce->rows[$i]['cell'] =array($row1->contractor_id,$row1->contractor_title,$row1->contractor_description,$row1->contractor_phone,$row1->contractor_address,$row1->contractor_name);
                        $i++;
                    }
                    return $responce;
                }
            }
        }
    }
    public function addDetails($contractor_title,$contractor_description,$contractor_phone,$contractor_address,$contractor_name){
        $contractor_title=$this->mysqli->real_escape_string($contractor_title);
        $contractor_description=$this->mysqli->real_escape_string($contractor_description);
        $contractor_phone=$this->mysqli->real_escape_string($contractor_phone);
        $contractor_address=$this->mysqli->real_escape_string($contractor_address);
        $contractor_name=$this->mysqli->real_escape_string($contractor_name);
        $result = $this->mysqli->query("INSERT INTO ".$this->table."(contractor_title,contractor_description,contractor_phone,contractor_address,contractor_name) VALUES('$contractor_title','$contractor_description','$contractor_phone','$contractor_address','$contractor_name')");
        if ($result) {
            if($this->mysqli->affected_rows>0){
                return TRUE;
            }
        }
        return FALSE;
    }
    public function editDetails($contractor_title,$contractor_description,$contractor_phone,$contractor_address,$contractor_name,$id){
        $contractor_title=$this->mysqli->real_escape_string($contractor_title);
        $contractor_description=$this->mysqli->real_escape_string($contractor_description);
        $contractor_phone=$this->mysqli->real_escape_string($contractor_phone);
        $contractor_address=$this->mysqli->real_escape_string($contractor_address);
        $contractor_name=$this->mysqli->real_escape_string($contractor_name);
        $id=$this->mysqli->real_escape_string($id);
        echo "UPDATE ".$this->table." SET contractor_title='$contractor_title', contractor_description='$contractor_description', contractor_phone='$contractor_phone', contractor_address='$contractor_address', contractor_name='$contractor_name' WHERE ".$this->id."='".$id."'";
        $result = $this->mysqli->query("UPDATE ".$this->table." SET contractor_title='$contractor_title', contractor_description='$contractor_description', contractor_phone='$contractor_phone', contractor_address='$contractor_address', contractor_name='$contractor_name' WHERE ".$this->id."='".$id."'");
        if ($result){
            if($this->mysqli->affected_rows>0){
                return TRUE;
            }
        }
        return FALSE;
    }
}
?>
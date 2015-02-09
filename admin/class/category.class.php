<?php
include_once(Config::$site_path.'admin/class/general.class.php');
class Category extends General{
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
                $query="SELECT a.category_id,a.category_title,a.category_name,a.category_value,a.category_order,a.position,a.active
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
                        $responce->rows[$i]['category_id']=$row1->category_id;
                        $responce->rows[$i]['cell'] =array($row1->category_id,$row1->category_name,$row1->category_title,$row1->category_value,$row1->category_order,$row1->position,$row1->active);
                        $i++;
                    }
                    return $responce;
                }
            }
        }
    }
    public function addDetails($category_name,$category_title,$category_value,$category_order,$position,$active){
        $category_name=$this->mysqli->real_escape_string($category_name);
        $category_title=$this->mysqli->real_escape_string($category_title);
        $category_value=$this->mysqli->real_escape_string($category_value);
        $category_order=$this->mysqli->real_escape_string($category_order);
        $position=$this->mysqli->real_escape_string($position);
        $active=$this->mysqli->real_escape_string($active);
        $result = $this->mysqli->query("INSERT INTO ".$this->table."(category_name,category_title,category_value,category_order,position,active) VALUES('$category_name','$category_title','$category_value','$category_order','$position','$active')");
        if ($result) {
            if($this->mysqli->affected_rows>0){
                return TRUE;
            }
        }
        return FALSE;
    }
    public function editDetails($category_name,$category_title,$category_value,$category_order,$position,$active,$id){
        $category_name=$this->mysqli->real_escape_string($category_name);
        $category_title=$this->mysqli->real_escape_string($category_title);
        $category_value=$this->mysqli->real_escape_string($category_value);
        $category_order=$this->mysqli->real_escape_string($category_order);
        $position=$this->mysqli->real_escape_string($position);
        $active=$this->mysqli->real_escape_string($active);
        $id=$this->mysqli->real_escape_string($id);
        $result = $this->mysqli->query("UPDATE ".$this->table." SET category_name='$category_name', category_title='$category_title', category_value='$category_value', category_order='$category_order',position='$position',active='$active' WHERE ".$this->id."='".$id."'");
        if ($result){
            if($this->mysqli->affected_rows>0){
                return TRUE;
            }
        }
        return FALSE;
    }
    public function getSelect() {
        $i=1;
        $return_array = '';
        $query="SELECT category_id,category_value
                    FROM 
                    ".$this->table." a 
                    WHERE 
                    delete_flag=FALSE 
                    AND category_value IS NOT NULL";
        if ($result = $this->mysqli->query($query)){
            while ($row = $result->fetch_object()){
                $return_array .= $row->category_id.':'.$row->category_value;
                if($result->num_rows!=$i){
                    $return_array .=';';
                }
                $i++;
            }
        }
        return $return_array;
    }
}
?>
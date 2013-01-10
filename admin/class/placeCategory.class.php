<?php
include_once(Config::$site_path.'admin/class/general.class.php');
class PlaceCategory extends General{
    public function getDetails($page,$limit,$sidx,$sord,$wh=""){
        $page=$this->mysqli->real_escape_string($page);
        $limit=$this->mysqli->real_escape_string($limit);
        $sidx=$this->mysqli->real_escape_string($sidx);
        $sord=$this->mysqli->real_escape_string($sord);
        if ($result = $this->mysqli->query("SELECT COUNT(*) AS count FROM ".$this->table." WHERE 1=1 ".$wh)){
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
                $query="SELECT a.placeCategory_id,c.category_title as category_title,b.place_title as place_title,a.active
                    FROM 
                    ".$this->table." a
                    LEFT JOIN 
                    ".Config::$tables['place_table']." b ON b.place_id=a.place_id
                    LEFT JOIN
                    ".Config::$tables['category_table']." c ON a.category_id=c.category_id
                    WHERE 
                    a.delete_flag=FALSE 
                    AND b.delete_flag=FALSE
                    AND c.delete_flag=FALSE
                    ".$wh."
                    ORDER BY ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
                $result1 = $this->mysqli->query($query);
                if ($result1) {
                    $responce->page = $page;
                    $responce->total = $total_pages;
                    $responce->records = $count;
                    $i=0;
                    while ($row1 = $result1->fetch_object()) {
                        $responce->rows[$i]['placeCategory_id']=$row1->placeCategory_id;
                        $responce->rows[$i]['cell'] =array($row1->placeCategory_id,$row1->place_title,$row1->category_title,$row1->active);
                        $i++;
                    }
                    return $responce;
                }
            }
        }
    }
    public function getCategories() {
        $query="SELECT category_id
                    FROM 
                    ".Config::$tables['category_table']."";
        $result1 = $this->mysqli->query($query);
        if ($result1) {
            $i=0;
            while ($row1 = $result1->fetch_object()) {
                $responce[$row1->category_id]=$row1->category_id;
                $i++;
            }
            return $responce;
        }
    }
    public function getPlaceChildren($place_id) {
        $place_id=$this->mysqli->real_escape_string($place_id);
        $query="SELECT place_id
                    FROM 
                    ".Config::$tables['place_table']." a
                    WHERE 
                    a.delete_flag=FALSE
                    AND a.under=".$place_id."";
        $result1 = $this->mysqli->query($query);
        if ($result1) {
            $i=0;
            while ($row1 = $result1->fetch_object()) {
                $responce[$row1->place_id]=$row1->place_id;
                $i++;
            }
            return $responce;
        }
    }
    public function addDetails($place_id,$category_id,$active){
        $place_id=$this->mysqli->real_escape_string($place_id);
        $category_id=$this->mysqli->real_escape_string($category_id);
        $active=$this->mysqli->real_escape_string($active);

        if ($this->getPlaceChildren($place_id)) {
            $placeIds = $this->getPlaceChildren($place_id);  
        } else {
            $placeIds = array(
                $place_id => $place_id
            );
        }
        if ($category_id == 'All') {
            $categoryIds = $this->getCategories();  
        } else {
            $categoryIds = array(
                $category_id => $category_id
            );
        }
        foreach($placeIds as $placeKey=>$placeValue) {
            foreach($categoryIds as $categoryKey=>$categoryValue) {
                if ($result2 = $this->mysqli->query("SELECT COUNT(*) AS count FROM ".$this->table." WHERE place_id=".$placeValue." AND category_id=".$categoryValue."")){
                    while ($row2 = $result2->fetch_object()){
                        $count = $row2->count;
                        if ($count == 0) {
                            $query1 = "INSERT INTO ".$this->table."(place_id,category_id,active) VALUES('".$placeValue."','$categoryValue','$active')";
                            $result1 = $this->mysqli->query($query1);
                        }
                    }
                }
            }
        }
        if ($result) {
            if ($this->mysqli->affected_rows>0) {
                return TRUE;
            }
        }
        return FALSE;
    }
    public function editDetails($place_id,$category_id,$active,$id){
        $place_id=$this->mysqli->real_escape_string($place_id);
        $category_id=$this->mysqli->real_escape_string($category_id);
        $active=$this->mysqli->real_escape_string($active);
        $id=$this->mysqli->real_escape_string($id);
        $result = $this->mysqli->query("UPDATE ".$this->table." SET place_id='$place_id',category_id='$category_id',active='$active' WHERE id='$id'");
        if ($result){
            if($this->mysqli->affected_rows>0){
                return TRUE;
            }
        }
        return FALSE;
    }
    public function getSelect($place_id,$type='json') {
        $i=1;
        $place_id=$this->mysqli->real_escape_string($place_id);
        $query="SELECT a.placeCategory_id,b.category_name as category_name,a.active
            FROM 
            ".$this->table." a
            LEFT JOIN 
            ".Config::$tables['category_table']." b ON a.category_id=b.category_id
            WHERE 
            a.delete_flag=FALSE
            AND b.delete_flag=FALSE
            AND a.place_id=".$place_id."";
        if ($result = $this->mysqli->query($query)){
            while ($row = $result->fetch_object()){
                if ($type=='json') {
                    $return_array[$row->placeCategory_id] = $row->category_name;
                } else {
                    $return_array .= $row->placeCategory_id.':'.$row->category_name;
                    if($result->num_rows!=$i){
                        $return_array .=';';
                    }
                    $i++;
                }
            }
        }
        return $return_array;
    }
}
?>
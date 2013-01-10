<?php
include_once(Config::$site_path.'admin/class/general.class.php');
class CategorySection extends General{
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
                $query="SELECT a.categorySection_id,e.place_title as place_title,d.category_title as category_title,c.section_title as section_title,a.categorysection_order,a.active
                    FROM 
                    ".$this->table." a
                    LEFT JOIN
                    ".Config::$tables['placeCategory_table']." b ON b.placeCategory_id=a.placeCategory_id
                    LEFT JOIN 
                    ".Config::$tables['section_table']." c ON c.section_id=a.section_id
                    LEFT JOIN
                    ".Config::$tables['category_table']." d ON b.category_id=d.category_id                    
                    LEFT JOIN
                    ".Config::$tables['place_table']." e ON e.place_id=b.place_id
                    WHERE 
                    a.delete_flag=FALSE 
                    ANd b.delete_flag=FALSE 
                    AND c.delete_flag=FALSE 
                    AND d.delete_flag=FALSE 
                    AND e.delete_flag=FALSE 
                    ".$wh."
                    ORDER BY ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
                $result1 = $this->mysqli->query($query);
                if ($result1) {
                    $responce->page = $page;
                    $responce->total = $total_pages;
                    $responce->records = $count;
                    $i=0;
                    while ($row1 = $result1->fetch_object()) {
                        $responce->rows[$i]['categorySection_id']=$row1->categorySection_id;
                        $responce->rows[$i]['cell'] =array($row1->categorySection_id,$row1->place_title,$row1->category_title,$row1->section_title,$row1->categorysection_order,$row1->active);
                        $i++;
                    }
                    return $responce;
                }
            }
        }
    }
    public function getSections() {
        $query="SELECT a.section_id
                    FROM 
                    ".Config::$tables['section_table']." a
                    WHERE 
                    delete_flag=FALSE";
        $result1 = $this->mysqli->query($query);
        if ($result1) {
            $i=0;
            while ($row1 = $result1->fetch_object()) {
                $responce[$i]=$row1->section_id;
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
    public function getCategories() {
        $query="SELECT category_id
                    FROM 
                    ".Config::$tables['category_table']."
                    WHERE 
                    a.delete_flag=FALSE";
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
    public function getPlaceCategories($placeIdArray,$categoryIdArray) {
        $i = 0;
        foreach($placeIdArray as $placeIdKey=>$placeIdValue) {
            foreach($categoryIdArray as $categoryIdKey=>$categoryIdValue) {
                $query="SELECT a.placeCategory_id
                            FROM 
                            ".Config::$tables['placeCategory_table']." a
                            WHERE 
                            a.delete_flag=FALSE 
                            AND place_id=".$placeIdValue." 
                            AND category_id=".$categoryIdValue."";
                $result = $this->mysqli->query($query);
                if ($result) {
                    while ($row = $result->fetch_object()) {
                        $responce[$row->placeCategory_id]=$row->placeCategory_id;
                        $i++;
                    }
                }
            }
        }
        return $responce;
    }
    public function addDetails($place_id,$category_id,$section_id,$categorysection_order,$active){
        $place_id=$this->mysqli->real_escape_string($place_id);
        $category_id=$this->mysqli->real_escape_string($category_id);
        $section_id=$this->mysqli->real_escape_string($section_id);
        $categorysection_order=$this->mysqli->real_escape_string($categorysection_order);
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
        if ($section_id=="All" && $this->getSections()) {
            $sectionIds = $this->getSections();
        } else {
            $sectionIds = array(
                $section_id => $section_id
            ); 
        }
        $placeCategoryIds = $this->getPlaceCategories($placeIds,$categoryIds);
        foreach($placeCategoryIds as $placeCategoryIdKey=>$placeCategoryIdValue) {
            foreach($sectionIds as $sectionIdKey=>$sectionKeyValue) {
                $i = 1;
                $query = "SELECT COUNT(*) AS count FROM ".$this->table." 
                    WHERE 
                    section_id=".$sectionKeyValue." 
                    AND placeCategory_id=".$placeCategoryIdValue."";
                if ($result = $this->mysqli->query($query)) {
                    while ($row = $result->fetch_object()) {
                        $count = $row->count;
                        if ($count == 0) {
                            $query1 = "INSERT INTO ".$this->table."(placeCategory_id,section_id,categorysection_order,active) VALUES('$placeCategoryIdValue','$sectionKeyValue','$i','$active')";
                            $result1 = $this->mysqli->query($query1);
                        }
                    }
                }
                $i++;
            }
        }
    }
    public function editDetails($place_id,$category_id,$section_id,$categorysection_order,$active,$id){
        $place_id=$this->mysqli->real_escape_string($category_id);
        $category_id=$this->mysqli->real_escape_string($category_id);
        $section_id=$this->mysqli->real_escape_string($section_id);
        $categorysection_order=$this->mysqli->real_escape_string($categorysection_order);
        $active=$this->mysqli->real_escape_string($active);
        $id=$this->mysqli->real_escape_string($id);
        $result = $this->mysqli->query("UPDATE ".$this->table." SET category_id='$category_id',section_id='$section_id',categorysection_order='$categorysection_order',active='$active' WHERE ".$this->id."='$id'");
        if ($result){
            if($this->mysqli->affected_rows>0){
                return TRUE;
            }
        }
        return FALSE;
    }
    public function getSelect($placeCategory_id,$type='json') {
        $i=1;
        $return_array = '';
        $placeCategory_id=$this->mysqli->real_escape_string($placeCategory_id);
        $query="SELECT a.categorySection_id,b.section_title as section_title,a.active
            FROM 
            ".$this->table." a
            LEFT JOIN 
            ".Config::$tables['section_table']." b ON b.section_id=a.section_id
            WHERE 
            a.delete_flag=FALSE 
            AND b.delete_flag=FALSE 
            AND a.placeCategory_id=".$placeCategory_id."";
        if ($result = $this->mysqli->query($query)){
            while ($row = $result->fetch_object()){
                if ($type=='json') {
                    $return_array[$row->categorySection_id] = $row->section_title;
                } else {
                    $return_array .= $row->categorySection_id.':'.$row->section_title;
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
<?php
include_once(Config::$site_path.'admin/class/general.class.php');
class ContractorMapping extends General{
    public function getDetails($page,$limit,$sidx,$sord,$wh=""){
        $page=$this->mysqli->real_escape_string($page);
        $limit=$this->mysqli->real_escape_string($limit);
        $sidx=$this->mysqli->real_escape_string($sidx);
        $sord=$this->mysqli->real_escape_string($sord);
        if ($result = $this->mysqli->query("SELECT COUNT(*) AS count FROM ".$this->table." WHERE 1=1 ".$wh)){
            while ($row = $result->fetch_object()){
                $count = $row->count;
                if ($count >0) {
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
                $query="SELECT a.contractorMapping_id, e.contractor_title as contractor_title, d.category_title as category_title, c.section_title as section_title, b.place_title as place_title, a.active
                    FROM 
                    ".$this->table." a
                    LEFT JOIN
                    ".Config::$tables['place_table']." b ON a.place_id=b.place_id
                    LEFT JOIN
                    ".Config::$tables['section_table']." c ON a.section_id=c.section_id
                    LEFT JOIN
                    ".Config::$tables['category_table']." d ON c.category_id=d.category_id
                    LEFT JOIN
                    ".Config::$tables['contractor_table']." e ON a.contractor_id=e.contractor_id
                    WHERE 
                    a.delete_flag=FALSE 
                    AND b.delete_flag=FALSE 
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
                        $responce->rows[$i]['contractorMapping_id']=$row1->contractorMapping_id;
                        $responce->rows[$i]['cell'] =array($row1->contractorMapping_id,$row1->place_title,$row1->category_title,$row1->section_title,$row1->contractor_title,$row1->active);
                        $i++;
                    }
                    return $responce;
                }
            }
        }
    }
    public function getCategorySections($section_id) {
        $section_id=$this->mysqli->real_escape_string($section_id);
        $query="SELECT categorySection_id
                    FROM 
                    ".Config::$tables['categorySection_table']." a
                    WHERE
                    a.delete_flag=FALSE 
                    AND a.section_id=".$section_id."";
        $result1 = $this->mysqli->query($query);
        if ($result1) {
            $i=0;
            while ($row1 = $result1->fetch_object()) {
                $responce[$i]=$row1->categorySection_id;
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
                $responce[$i]=$row1->place_id;
                $i++;
            }
            return $responce;
        }
    }
    public function addDetails($place_id,$section_id,$contractor_id,$active){
        $place_id=$this->mysqli->real_escape_string($place_id);
        $section_id=$this->mysqli->real_escape_string($section_id);
        $contractor_id=$this->mysqli->real_escape_string($contractor_id);
        $active=$this->mysqli->real_escape_string($active);

        if ($this->getPlaceChildren($place_id)) {
            $placeIds = $this->getPlaceChildren($place_id);
        } else {
            $placeIds = array(
                $place_id => $place_id
            );
        }
        foreach($placeIds as $placeKey=>$placeValue) {
            $placeCategoryQuery = "SELECT placeCategory_id FROM ".Config::$tables['placeCategory_table']." WHERE place_id=".$placeValue." AND delete_flag=FALSE AND category_id=".$category_id."";
            echo $placeCategoryQuery;
            if ($placeResults = $this->mysqli->query($placeCategoryQuery)){
                while ($placeResultRow = $placeResults->fetch_object()){
                    if ($section_id == 'All') {
                        $categorySectionIds = $this->getCategorySections($placeResultRow->placeCategory_id);
                    } else {
                        $categorySectionQuery = "SELECT categorySection_id FROM ".Config::$tables['categorySection_table']." WHERE placeCategory_id=".$placeResultRow->placeCategory_id." AND delete_flag=FALSE AND section_id=".$section_id."";
                        if ($categorySectionResults = $this->mysqli->query($categorySectionQuery)) {
                            while ($categorySectionRow = $categorySectionResults->fetch_object()){
                                $categorySectionIds[$categorySectionRow->categorySection_id] = $categorySectionRow->categorySection_id;
                            }
                        }
                    }
                    if ($categorySectionIds) {
                        foreach($categorySectionIds as $categorySectionKey=>$categorySectionValue) {
                            $categorySectionQuery = "SELECT COUNT(*) AS count FROM ".$this->table." WHERE contractor_id=".$contractor_id." AND categorySection_id=".$categorySectionValue."";
                            if ($result2 = $this->mysqli->query($categorySectionQuery)){
                                while ($row2 = $result2->fetch_object()){
                                    $count = $row2->count;
                                    if ($count == 0) {
                                        $query = "INSERT INTO ".$this->table."(categorySection_id,contractor_id,active) VALUES('$categorySectionValue','$contractor_id','$active')";
                                        $result = $this->mysqli->query($query);
                                    }
                                }
                            }
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
    public function editDetails($place_id,$category_id,$section_id,$contractor_id,$active,$id){
        $place_id=$this->mysqli->real_escape_string($place_id);
        $category_id=$this->mysqli->real_escape_string($category_id);
        $section_id=$this->mysqli->real_escape_string($section_id);
        $contractor_id=$this->mysqli->real_escape_string($contractor_id);
        $active=$this->mysqli->real_escape_string($active);
        $id=$this->mysqli->real_escape_string($id);
        $query="UPDATE ".$this->table." SET contractor_id='$contractor_id',categorySection_id='$categorySection_id',active='$active' WHERE ".$this->id."='$id'";
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
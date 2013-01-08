<?php
include_once(Config::$site_path.'admin/class/general.class.php');
class CategorySection extends General
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
                $query="SELECT a.id,b.category_name as category_name,c.section_name as section_name,a.categorysection_order,a.active
                    FROM 
                    ".$this->table." a
                    LEFT JOIN
                    ".Config::$tables['category_table']." b ON a.category_id=b.category_id
                    LEFT JOIN 
                    ".Config::$tables['section_table']." c ON c.section_id=a.section_id
                    WHERE 1=1 ".$wh."
                    ORDER BY ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
                    //echo $query;
                $result1 = $this->mysqli->query($query);
                if ($result1) {
                    $responce->page = $page;
                    $responce->total = $total_pages;
                    $responce->records = $count;
                    $i=0;
                    while ($row1 = $result1->fetch_object()) {
                        $responce->rows[$i]['id']=$row1->id;
                        $responce->rows[$i]['cell'] =array($row1->id,$row1->category_name,$row1->section_name,$row1->categorysection_order,$row1->active);
                        $i++;
                    }
                    return $responce;
                }
            }
        }
    }
    public function addDetails($category_id,$section_id,$categorysection_order,$active)
    {
        $category_id=$this->mysqli->real_escape_string($category_id);
        $section_id=$this->mysqli->real_escape_string($section_id);
        $categorysection_order=$this->mysqli->real_escape_string($categorysection_order);
        $active=$this->mysqli->real_escape_string($active);
        $query = "INSERT INTO ".$this->table."(category_id,section_id,categorysection_order,active) VALUES('$category_id','$section_id','$categorysection_order','$active')";
        echo $query;
        $result = $this->mysqli->query($query);
        if ($result) {
            if($this->mysqli->affected_rows>0)
            {
                return TRUE;
            }
        }
        return FALSE;
    }
    public function editDetails($category_id,$section_id,$categorysection_order,$active,$id)
    {
        $category_id=$this->mysqli->real_escape_string($category_id);
        $section_id=$this->mysqli->real_escape_string($section_id);
        $categorysection_order=$this->mysqli->real_escape_string($categorysection_order);
        $active=$this->mysqli->real_escape_string($active);
        $id=$this->mysqli->real_escape_string($id);
        $result = $this->mysqli->query("UPDATE ".$this->table." SET category_id='$category_id',section_id='$section_id',categorysection_order='$categorysection_order',active='$active' WHERE id='$id'");
        if ($result){
            if($this->mysqli->affected_rows>0){
                return TRUE;
            }
        }
        return FALSE;
    }
}
?>
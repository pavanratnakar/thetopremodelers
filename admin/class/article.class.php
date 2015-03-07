<?php
include_once(Config::$site_path.'admin/class/general.class.php');
class Article extends General
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
                $query="SELECT a.article_id,a.name,a.title,a.keywords,a.description,a.content,a.active
                    FROM 
                    ".$this->table." a
                    WHERE 1=1 ".$wh."
                    ORDER BY ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
                $result1 = $this->mysqli->query($query);
                if ($result1) {
                    $responce->page = $page;
                    $responce->total = $total_pages;
                    $responce->records = $count;
                    $i=0;
                    while ($row1 = $result1->fetch_object()) {
                        $responce->rows[$i]['id']=$row1->article_id;
                        $responce->rows[$i]['cell'] =array($row1->article_id,$row1->name,$row1->title,$row1->keywords,$row1->description,$row1->content,$row1->active);
                        $i++;
                    }
                    return $responce;
                }
            }
        }
    }
    public function addDetails($name,$title,$keywords,$description,$content,$active)
    {
        $name=$this->mysqli->real_escape_string($name);
        $content=$this->mysqli->real_escape_string($content);
        $active=$this->mysqli->real_escape_string($active);
        $query = "INSERT INTO ".$this->table."(name,title,keywords,description,content,active) VALUES('$name','$title','$keywords','$description','$content','$active')";
        $result = $this->mysqli->query($query);
        if ($result) {
            if($this->mysqli->affected_rows>0)
            {
                return TRUE;
            }
        }
        return FALSE;
    }
    public function editDetails($name,$title,$keywords,$description,$content,$active,$id)
    {
        $name=$this->mysqli->real_escape_string($name);
        $title=$this->mysqli->real_escape_string($title);
        $keywords=$this->mysqli->real_escape_string($keywords);
        $description=$this->mysqli->real_escape_string($description);
        $content=$this->mysqli->real_escape_string($content);
        $active=$this->mysqli->real_escape_string($active);
        $id=$this->mysqli->real_escape_string($id);
        $result = $this->mysqli->query("UPDATE ".$this->table." SET name='$name',title='$title',keywords='$keywords',description='$description',content='$content',active='$active' WHERE article_id='$id'");
        if ($result){
            if($this->mysqli->affected_rows>0){
                return TRUE;
            }
        }
        return FALSE;
    }
    public function deleteDetails($id)
    {
        $id=$this->mysqli->real_escape_string($id);
        if ($result = $this->mysqli->query("DELETE FROM ".$this->table." WHERE ".$this->id."='".$id."'")) {
            if($this->mysqli->affected_rows>0) {
                return TRUE;
            }
        }
        return FALSE;
    }
}
?>
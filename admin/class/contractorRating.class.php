<?php
include_once(Config::$site_path.'admin/class/general.class.php');
class contractorRating extends General{
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
                $query="SELECT a.contractorRating_id,a.score,a.review,b.contractor_title,a.timestamp,a.person,a.place_id as place_title,a.project
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
                        $responce->rows[$i]['contractorRating_id']=$row1->contractorRating_id;
                        $responce->rows[$i]['cell'] =array($row1->contractorRating_id,$row1->score,$row1->review,$row1->contractor_title,$row1->timestamp,$row1->person,$row1->place_title,$row1->project);
                        $i++;
                    }
                    return $responce;
                }
            }
        }
    }
    public function addDetails($contractor_id,$score,$review,$timestamp,$person,$place_id,$project){
        $contractor_id=$this->mysqli->real_escape_string($contractor_id);
        $score=$this->mysqli->real_escape_string($score);
        $review=$this->mysqli->real_escape_string($review);
        $timestamp=date('Y-m-d H:i:s', strtotime($this->mysqli->real_escape_string($timestamp)));
        $person=$this->mysqli->real_escape_string($person);
        $place_id=$this->mysqli->real_escape_string($place_id);
        $project=$this->mysqli->real_escape_string($project);
        $query = "INSERT INTO ".$this->table."(score,review,contractor_id,timestamp,person,place_id,project) VALUES('$score','$review','$contractor_id','$timestamp','$person','$place_id','$project')";
        echo $query;
        $result = $this->mysqli->query($query);
        if ($result){
            if ($this->mysqli->affected_rows>0) {
                return TRUE;
            }
        }
        return FALSE;
    }
    public function editDetails($contractor_id,$score,$review,$timestamp,$person,$place_id,$project,$id){
        $contractor_id=$this->mysqli->real_escape_string($contractor_id);
        $score=$this->mysqli->real_escape_string($score);
        $review=$this->mysqli->real_escape_string($review);
        $timestamp=date('Y-m-d H:i:s', strtotime($this->mysqli->real_escape_string($timestamp)));
        $person=$this->mysqli->real_escape_string($person);
        $place_id=$this->mysqli->real_escape_string($place_id);
        $project=$this->mysqli->real_escape_string($project);
        $id=$this->mysqli->real_escape_string($id);
        $result = $this->mysqli->query("UPDATE ".$this->table." SET contractor_id='$contractor_id',score='$score',review='$review',timestamp='$timestamp',person='$person',place_id='$place_id',project='$project' WHERE ".$this->id."='$id'");
        if ($result){
            if($this->mysqli->affected_rows>0){
                return TRUE;
            }
        }
        return FALSE;
    }
}
?>
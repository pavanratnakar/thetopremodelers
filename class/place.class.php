<?php
class Place {
    public function __construct() {
        $this->mysqli=new mysqli(Config::$db_server,Config::$db_username,Config::$db_password,Config::$db_database);
        $this->utils=new Utils();
    }
    public function getPlaceDetails($placeName){
        $placeName=$this->mysqli->real_escape_string($placeName);
        $query="SELECT *
                    FROM 
                    ".Config::$tables['place_table']." a
                    WHERE 
                    place_name='".$placeName."'
                    AND a.delete_flag=FALSE";

        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['place_id']=$row->place_id;
                $response[$i]['place_title']=$row->place_title;
                $i++;
            }
        }
        return $response[0];
    }
    public function getPlaces($limit=null){
        $placeName=$this->mysqli->real_escape_string($placeName);
        if ($limit) {
            $limit = "LIMIT 0,".$limit;
        } else {
            $limit = "";
        }
        $query="SELECT *
                    FROM 
                    ".Config::$tables['place_table']." a 
                    WHERE 
                    a.delete_flag=FALSE
                    AND under!=0 
                    ORDER BY place_id ASC ".$limit."";
        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['place_title']=$row->place_title;
                $response[$i]['place_name']=$row->place_name;
                $i++;
            }
        }
        return $response;
    }
    public function getMeta($placeName) {
        $placeDetails = $this->getPlaceDetails($placeName);
        $keywords = ($placeName==='dallas_texas') ? 'general contractor dallas' : false;
        return array(
            'keywords'=>$keywords,
            'description'=>'We are the only company providing roofing contractors in Dallas ,with 5 Stars certified ratings ,giving you the confidence in choosing the right company',
            'title'=>$placeDetails['place_title']
        );
    }
    public function __destruct() {
        $this->mysqli->close();
    }
}
?>
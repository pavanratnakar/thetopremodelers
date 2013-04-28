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
                    AND a.delete_flag=FALSE AND a.active=TRUE";

        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['place_id']=$row->place_id;
                $response[$i]['place_title']=$row->place_title;
                $response[$i]['place_geo']=$row->place_geo;
                $response[$i]['place_geo_placename']=$row->place_geo_placename;
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
                    a.delete_flag=FALSE AND a.active=TRUE
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
        $keywords = ($placeName==='dallas_texas') ? 'dallas general contractors' : false;
        $placeTitle = $placeDetails['place_title'];
        $suffix = strrchr($placeTitle, ","); 
        $pos = strpos($placeTitle,$suffix); 
        $name = substr_replace ($placeTitle,"", $pos);
        return array(
            'keywords'=>$keywords,
            'description'=>'We are the only company providing general contractors in '.$name.',with 5 Stars certified ratings ,giving you the confidence in choosing the right company',
            'title'=>'General contractors in '.$name,
            'geo'=>$placeDetails['place_geo'],
            'geo_placename'=>$placeDetails['place_geo_placename']
        );
    }
    public function __destruct() {
        $this->mysqli->close();
    }
}
?>
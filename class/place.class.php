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
                $response[$i]['meta_id']=$row->meta_id;
                $i++;
            }
        }
        return $response[0];
    }
    public function getPlaces($limit=null){
        if ($limit) {
            $count = 0;
            $query1 = "SELECT count(*) as count
                        FROM
                        ".Config::$tables['place_table']." a
                        WHERE
                        a.delete_flag=FALSE AND a.active=TRUE
                        AND under!=0";
            if ($result1 = $this->mysqli->query($query1)) {
                while ($row1 = $result1->fetch_object()) {
                    $count = $row1->count;
                }
            }
            $random = rand ($limit - 1 , $count - 1);
            $limit = "LIMIT " . $limit . " OFFSET " . ($random - $limit);
        } else {
            $limit = "";
        }
        $query="SELECT *
                    FROM
                    ".Config::$tables['place_table']." a
                    WHERE
                    a.delete_flag=FALSE AND a.active=TRUE
                    AND under!=0
                    ORDER BY place_title ASC ".$limit."";
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
        $query="SELECT *
                    FROM
                    ".Config::$tables['meta_table']." a
                    WHERE
                    id='".$placeDetails['meta_id']."'";

        $placeTitle = $placeDetails['place_title'];
        $suffix = strrchr($placeTitle, ",");
        $pos = strpos($placeTitle,$suffix);
        $name = substr_replace ($placeTitle,"", $pos);
        $result = $this->mysqli->query($query);
        $totalRowcount = $result->num_rows;
        if ($totalRowcount > 0) {
            while ($row = $result->fetch_object()) {
                $keywords=$row->keywords;
                $title=$row->title;
            }
        } else {
            $keywords = ($placeName==='dallas_texas') ? 'dallas general contractors' : false;
            $title = 'General contractors in '.$name;
        }
        $desciprtion = 'We are the only company providing general contractors in '.$name.',with 5 Stars certified ratings ,giving you the confidence in choosing the right company';
        return array(
            'keywords'=>$keywords,
            'description'=>$desciprtion,
            'title'=>$title,
            'geo'=>$placeDetails['place_geo'],
            'geo_placename'=>$placeDetails['place_geo_placename']
        );
    }
    public function __destruct() {
        $this->mysqli->close();
    }
}
?>
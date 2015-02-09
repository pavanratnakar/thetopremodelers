<?php
class Contractor{
    private $mysqli;
    private $utils;
    private $categories;
    private $category;
    public function __construct() {
        $this->mysqli=new mysqli(Config::$db_server,Config::$db_username,Config::$db_password,Config::$db_database);
        $this->utils=new Utils();
    }
    public function getContractors($placeName, $sectionName, $sort, $page){
        $placeName=$this->mysqli->real_escape_string($placeName);
        $sectionName=$this->mysqli->real_escape_string($sectionName);
        $sort=$this->mysqli->real_escape_string($sort);
        $orderBy='';
        if ($sort === '') {
            $sort = 'average_score';
        }
        $orderBy="ORDER BY ".$sort." DESC";
        if ($page == 1) {
            $start = 1;
        } else {
            $allContractors = $this->getAllContractors($placeName, $sectionName);
            $start = min(Config::$paginationLimit*$page,$allContractors['total_count']);
        }
        $query="SELECT ROUND(SUM(score)/COUNT(score),1) as average_score,COUNT(review) as review_count,a.contractor_title,a.contractor_description,a.contractor_phone,a.contractor_address,a.contractor_name,d.section_title,e.place_title,e.place_geo,e.place_geo_placename,f.category_title,g.image_id,d.background_id
        FROM 
        ".Config::$tables['contractor_table']." a
        LEFT JOIN
        ".Config::$tables['contractorRating_table']." b ON a.contractor_id=b.contractor_id
        LEFT JOIN
        ".Config::$tables['contractorMapping_table']." c ON c.contractor_id=a.contractor_id
        LEFT JOIN
        ".Config::$tables['section_table']." d ON c.section_id=d.section_id
        LEFT JOIN
        ".Config::$tables['place_table']." e ON c.place_id=e.place_id
        LEFT JOIN
        ".Config::$tables['category_table']." f ON f.category_id=d.category_id
        LEFT JOIN
        ".Config::$tables['contractorImage_table']." g ON g.contractor_id=a.contractor_id
        WHERE
        a.delete_flag=FALSE
        AND b.delete_flag=FALSE 
        AND c.delete_flag=FALSE 
        AND d.delete_flag=FALSE 
        AND e.delete_flag=FALSE 
        AND f.delete_flag=FALSE 
        AND c.active=TRUE 
        AND d.delete_flag=FALSE 
        AND e.delete_flag=FALSE 
        GROUP BY a.contractor_id
        ".$orderBy."";
                    //LIMIT ".$start.", ".Config::$paginationLimit."";
        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['contractor_title']=$row->contractor_title;
                $response[$i]['contractor_description']=$row->contractor_description;
                $response[$i]['contractor_phone']=$row->contractor_phone;
                $response[$i]['contractor_address']=$row->contractor_address;
                $response[$i]['contractor_name']=$row->contractor_name;
                $response[$i]['category_title']=$row->category_title;
                $response[$i]['place_title']=$row->place_title;
                $response[$i]['place_geo']=$row->place_geo;
                $response[$i]['place_geo_placename']=$row->place_geo_placename;
                $response[$i]['section_title']=$row->section_title;
                $response[$i]['average_score']=$row->average_score;
                $response[$i]['review_count']=$row->review_count;
                $response[$i]['image_id']=$row->image_id;
                $response[$i]['background_id']=$row->background_id;
                $i++;
            }
        }
        return $response;
    }
    public function getAllContractors($placeName, $sectionName){
        $placeName=$this->mysqli->real_escape_string($placeName);
        $sectionName=$this->mysqli->real_escape_string($sectionName);
        $query="SELECT COUNT(*) as total_count
        FROM 
        ".Config::$tables['contractor_table']." a
        LEFT JOIN
        ".Config::$tables['contractorRating_table']." b ON a.contractor_id=b.contractor_id
        LEFT JOIN
        ".Config::$tables['contractorMapping_table']." c ON c.contractor_id=a.contractor_id
        LEFT JOIN
        ".Config::$tables['section_table']." d ON d.section_id=c.section_id
        LEFT JOIN
        ".Config::$tables['place_id']." e ON e.place_id=c.place_id
        LEFT JOIN
        ".Config::$tables['category_table']." f ON f.category_id=d.category_id
        WHERE
        a.delete_flag=FALSE
        AND b.delete_flag=FALSE 
        AND c.delete_flag=FALSE 
        AND d.delete_flag=FALSE 
        AND e.delete_flag=FALSE 
        AND f.delete_flag=FALSE 
        AND d.section_name='".$sectionName."'
        AND e.place_name='".$placeName."'
        GROUP BY a.contractor_id";
        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['total_count']=$row->total_count;
                $i++;
            }
        }
        return $response[0];
    }
    public function getContractor($contractorName){
        $contractorName=$this->mysqli->real_escape_string($contractorName);
        $query="SELECT ROUND(SUM(score)/COUNT(score),1) as average_score,COUNT(review) as review_count,a.contractor_id,a.contractor_title,a.contractor_description,a.contractor_phone,a.contractor_address,a.contractor_name,c.image_id
        FROM 
        ".Config::$tables['contractor_table']." a
        LEFT JOIN
        ".Config::$tables['contractorRating_table']." b ON a.contractor_id=b.contractor_id
        LEFT JOIN
        ".Config::$tables['contractorImage_table']." c ON c.contractor_id=a.contractor_id
        WHERE 
        a.contractor_name='".$contractorName."' 
        AND a.delete_flag=FALSE 
        GROUP BY a.contractor_id";
        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['contractor_id']=$row->contractor_id;
                $response[$i]['contractor_title']=$row->contractor_title;
                $response[$i]['contractor_description']=$row->contractor_description;
                $response[$i]['contractor_phone']=$row->contractor_phone;
                $response[$i]['contractor_address']=$row->contractor_address;
                $response[$i]['contractor_name']=$row->contractor_name;
                $response[$i]['category_title']=$row->category_title;
                $response[$i]['average_score']=$row->average_score;
                $response[$i]['review_count']=$row->review_count;
                $response[$i]['image_id']=$row->image_id;
                $i++;
            }
        }
        return $response[0];
    }
    public function getSectionsForContractor($details){
        $query="SELECT e.category_title, c.section_title, c.section_id, c.section_name
        FROM 
        ".Config::$tables['contractor_table']." a
        LEFT JOIN
        ".Config::$tables['contractorMapping_table']." b ON b.contractor_id=a.contractor_id
        LEFT JOIN
        ".Config::$tables['section_table']." c ON c.section_id=b.section_id
        LEFT JOIN
        ".Config::$tables['place_table']." d ON d.place_id=b.place_id
        LEFT JOIN
        ".Config::$tables['category_table']." e ON e.category_id=c.category_id
        WHERE
        a.delete_flag=FALSE
        AND b.delete_flag=FALSE
        AND c.delete_flag=FALSE
        AND d.delete_flag=FALSE
        AND e.delete_flag=FALSE ";
        if ($details['contractor_id']) {
            $contractor_id=$this->mysqli->real_escape_string($details['contractor_id']);
            $query .= "AND a.contractor_id='".$contractor_id."' ";
        }
        if ($details['contractor_name']) {
            $contractor_name=$this->mysqli->real_escape_string($details['contractor_name']);
            $query .= "AND a.contractor_name='".$contractor_name."' ";
        }
        if ($details['place_id']) {
            $place_id=$this->mysqli->real_escape_string($details['place_id']);
            $query .= "AND d.place_id='".$place_id."' ";
        }
        if ($details['category_id']) {
            $category_id=$this->mysqli->real_escape_string($details['category_id']);
            $query .= "AND f.category_id='".$category_id."' ";
        }
        $query .= "
        GROUP BY c.section_title";
        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['category_title']=$row->category_title;
                $response[$i]['section_title']=$row->section_title;
                $response[$i]['section_id']=$row->section_id;
                $response[$i]['section_name']=$row->section_name;
                $i++;
            }
        }
        return $response;
    }
    public function getPlacesForContractor($details){
        $query="SELECT f.place_title,f.place_id,f.place_name
        FROM 
        ".Config::$tables['contractor_table']." a
        LEFT JOIN
        ".Config::$tables['contractorMapping_table']." b ON b.contractor_id=a.contractor_id
        LEFT JOIN
        ".Config::$tables['section_table']." c ON c.section_id=b.section_id
        LEFT JOIN
        ".Config::$tables['place_table']." d ON d.place_id=c.place_id
        WHERE
        a.delete_flag=FALSE
        AND b.delete_flag=FALSE
        AND c.delete_flag=FALSE
        AND d.delete_flag=FALSE
        AND b.active=TRUE ";
        if ($details['contractor_id']) {
            $contractor_id=$this->mysqli->real_escape_string($details['contractor_id']);
            $query .= "AND a.contractor_id='".$contractor_id."' ";
        }
        if ($details['contractor_name']) {
            $contractor_name=$this->mysqli->real_escape_string($details['contractor_name']);
            $query .= "AND a.contractor_name='".$contractor_name."' ";
        }
        $query .= "GROUP BY f.place_id";
        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['place_title']=$row->place_title;
                $response[$i]['place_id']=$row->place_id;
                $response[$i]['place_name']=$row->place_name;
                $i++;
            }
        }
        return $response;
    }
    public function getCategoriesForContractor($details){
        $query="SELECT g.category_title,g.category_id,g.category_name
        FROM 
        ".Config::$tables['contractor_table']." a
        LEFT JOIN
        ".Config::$tables['contractorMapping_table']." b ON b.contractor_id=a.contractor_id
        LEFT JOIN
        ".Config::$tables['section_table']." c ON c.section_id=b.section_id
        LEFT JOIN
        ".Config::$tables['place_table']." d ON d.place_id=b.place_id
        LEFT JOIN
        ".Config::$tables['category_table']." e ON e.category_id=c.category_id
        WHERE
        a.delete_flag=FALSE
        AND b.delete_flag=FALSE
        AND c.delete_flag=FALSE
        AND d.delete_flag=FALSE
        AND e.delete_flag=FALSE
        AND b.active=TRUE 
        AND c.delete_flag=FALSE ";
        if ($details['contractor_id']) {
            $contractor_id=$this->mysqli->real_escape_string($details['contractor_id']);
            $query .= "AND a.contractor_id='".$contractor_id."' ";
        }
        if ($details['contractor_name']) {
            $contractor_name=$this->mysqli->real_escape_string($details['contractor_name']);
            $query .= "AND a.contractor_name='".$contractor_name."' ";
        }
        if ($details['place_id']) {
            $place_id=$this->mysqli->real_escape_string($details['place_id']);
            $query .= "AND f.place_id='".$place_id."' ";
        }
        $query .= "GROUP BY g.category_id";
        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['category_title']=$row->category_title;
                $response[$i]['category_id']=$row->category_id;
                $response[$i]['category_name']=$row->category_name;
                $i++;
            }
        }
        return $response;
    }
    public function getRatingForContractor($details){
        if ($details['contractor_id']) {
            $contractor_id=$this->mysqli->real_escape_string($details['contractor_id']);
            $query="SELECT b.score,b.review,b.person,b.timestamp,b.score,c.place_title,b.project
            FROM 
            ".Config::$tables['contractor_table']." a
            LEFT JOIN
            ".Config::$tables['contractorRating_table']." b ON b.contractor_id=a.contractor_id
            LEFT JOIN
            ".Config::$tables['place_table']." c ON c.place_id=b.place_id
            WHERE
            a.delete_flag=FALSE
            AND b.delete_flag=FALSE
            AND c.delete_flag=FALSE
            AND a.contractor_id='".$contractor_id."'";
            if ($result = $this->mysqli->query($query)) {
                $i=0;
                while ($row = $result->fetch_object()) {
                    $response[$i]['score']=$row->score;
                    $response[$i]['review']=$row->review;
                    $response[$i]['timestamp']=date("l jS F Y", strtotime($row->timestamp));
                    $response[$i]['person']=$row->person;
                    $response[$i]['place_title']=$row->place_title;
                    $response[$i]['project']=$row->project;
                    $i++;
                }
            }
        }
        return $response;
    }
    public function getRatingDistributionForContractor($details){
        if ($details['contractor_id']) {
            $contractor_id=$this->mysqli->real_escape_string($details['contractor_id']);
            $query="SELECT COUNT(score) as score_count,score
            FROM 
            ".Config::$tables['contractorRating_table']." a
            WHERE
            a.delete_flag=FALSE
            AND a.contractor_id='".$contractor_id."'
            GROUP BY a.score";
            if ($result = $this->mysqli->query($query)) {
                $i=0;
                while ($row = $result->fetch_object()) {
                    $response[$row->score]=$row->score_count;
                    $i++;
                }
            }
        }
        return $response;
    }
    public function getContractorsMeta ($details, $categoryTitle=null, $sectionTitle=null) {
        $keywords = false;
        $query="SELECT *
                    FROM 
                    ".Config::$tables['meta_table']." a
                    WHERE 
                    id='".$details[0]['meta_id']."'";

        $placeTitle = explode(' (',$details[0]['place_title']);
        $placeTitle = $placeTitle[0];
        $suffix = strrchr($placeTitle, ",");
        $pos = strpos($placeTitle,$suffix);
        $name = substr_replace ($placeTitle,"", $pos);
        $result = $this->mysqli->query($query);
        $totalRowcount = $result->num_rows;
        if ($totalRowcount > 0) {
            while ($row = $result->fetch_object()) {
                $keywords = $row->keywords;
                $title = $row->title;
                $desciprtion = $row->description;
            }
        } else {
            $keywords = ($placeName==='dallas_texas') ? 'dallas general contractors' : false;
            $title = 'Voted 12 best '.$name.' contractors in '.$placeTitle.' Topremodelers';
            if ($categoryTitle) {
                if ($sectionTitle) {
                    $title = 'Voted 12 best '.$categoryTitle.' contractors for '.$sectionTitle.' in '.$placeTitle.' Topremodelers';
                } else {
                    $title = 'Voted 12 best '.$categoryTitle.' contractors in '.$placeTitle.' Topremodelers';
                }
            } else {
                $title = $name.' Roofing Company - '.$name.' Roofing Contractors - TX Roofers';
            }
        }
        if (!trim($desciprtion)) {
            if ($categoryTitle) {
                if ($sectionTitle) {
                    $desciprtion = 'We are the only company providing '.$categoryTitle.' contractors for '.$sectionTitle.' in '.$placeTitle.', with 5 Stars certified reviews, Your trusted source to choose the right company.';
                } else {
                    $desciprtion = 'We are the only company providing '.$categoryTitle.' contractors in '.$placeTitle.', with 5 Stars certified reviews, Your trusted source to choose the right company.';
                }
            } else {
                $desciprtion = 'We are the only company providing roofing contractors in '.$name.', with 5 Stars certified ratings, giving you the confidence in choosing the right company.';
            }
        }
        return array(
            'keywords'=>$keywords,
            'description'=>$desciprtion,
            'title'=>$title,
            'geo'=> $details[0]['place_geo'],
            'geo_placename'=> $details[0]['place_geo_placename']
        );
    }
    public function getMeta($details){
        return array(
            'keywords'=>'',
            'description'=>$details['contractor_title'].' | The top remodelers prescreened roofing contractors',
            'title'=>$details['contractor_title'].' The top remodelers prescreened roofing contractors'
            );
    }
    public function getDescription($categoryTitle,$placeName) {
        return 'Get Matched to Top-Rated '.$categoryTitle.' for '.$placeName;
    }
    public function __destruct() {
        $this->mysqli->close();
    }
}
?>
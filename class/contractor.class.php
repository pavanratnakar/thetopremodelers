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
    public function getContractors($placeName,$categoryName,$sectionName,$sort,$page){
        $placeName=$this->mysqli->real_escape_string($placeName);
        $categoryName=$this->mysqli->real_escape_string($categoryName);
        $sectionName=$this->mysqli->real_escape_string($sectionName);
        $sort=$this->mysqli->real_escape_string($sort);
        $orderBy='';
        if ($sort && $sort !='default') {
            $orderBy="ORDER BY ".$sort." DESC";
        }
        if ($page == 1) {
            $start = 1;
        } else {
            $allContractors = $this->getAllContractors($placeName,$categoryName,$sectionName);
            $start = min(Config::$paginationLimit*$page,$allContractors['total_count']);
        }
        $query="SELECT ROUND(SUM(score)/COUNT(score),1) as average_score,COUNT(review) as review_count,a.contractor_title,a.contractor_description,a.contractor_phone,a.contractor_address,a.contractor_name,c.categorySection_id,f.section_title,g.place_title,h.category_title
                    FROM 
                    ".Config::$tables['contractor_table']." a
                    LEFT JOIN
                    ".Config::$tables['contractorRating_table']." b ON a.contractor_id=b.contractor_id
                    LEFT JOIN
                    ".Config::$tables['contractorMapping_table']." c ON c.contractor_id=a.contractor_id
                    LEFT JOIN
                    ".Config::$tables['categorySection_table']." d ON d.categorySection_id=c.categorySection_id
                    LEFT JOIN
                    ".Config::$tables['placeCategory_table']." e ON e.placeCategory_id=d.placeCategory_id
                    LEFT JOIN
                    ".Config::$tables['section_table']." f ON f.section_id=d.section_id
                    LEFT JOIN
                    ".Config::$tables['place_table']." g ON g.place_id=e.place_id
                    LEFT JOIN
                    ".Config::$tables['category_table']." h ON h.category_id=e.category_id
                    WHERE
                    a.delete_flag=0
                    AND f.section_name='".$sectionName."'
                    AND g.place_name='".$placeName."'
                    AND h.category_name='".$categoryName."'
                    GROUP BY a.contractor_title
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
                $response[$i]['section_title']=$row->section_title;
                $response[$i]['average_score']=$row->average_score;
                $response[$i]['review_count']=$row->review_count;
                $i++;
            }
        }
        return $response;
    }
    public function getAllContractors($placeName,$categoryName,$sectionName){
        $placeName=$this->mysqli->real_escape_string($placeName);
        $categoryName=$this->mysqli->real_escape_string($categoryName);
        $sectionName=$this->mysqli->real_escape_string($sectionName);
        $query="SELECT COUNT(*) as total_count
                    FROM 
                    ".Config::$tables['contractorMapping_table']." a
                    LEFT JOIN
                    ".Config::$tables['categorySection_table']." b ON a.categorySection_id=b.categorySection_id
                    LEFT JOIN
                    ".Config::$tables['placeCategory_table']." c ON b.placeCategory_id=c.placeCategory_id
                    LEFT JOIN
                    ".Config::$tables['category_table']." d ON c.category_id=d.category_id
                    LEFT JOIN
                    ".Config::$tables['place_table']." e ON e.place_id=c.place_id
                    LEFT JOIN
                    ".Config::$tables['section_table']." f ON f.section_id=b.section_id
                    LEFT JOIN
                    ".Config::$tables['contractor_table']." g ON g.contractor_id=a.contractor_id
                    LEFT JOIN
                    ".Config::$tables['contractorRating_table']." h ON h.contractor_id=g.contractor_id
                    WHERE f.section_name='".$sectionName."' AND d.category_name='".$categoryName."' AND e.place_name='".$placeName."' AND g.delete_flag=0
                    GROUP BY g.contractor_id";
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
        $query="SELECT ROUND(SUM(score)/COUNT(score),1) as average_score,COUNT(review) as review_count,a.contractor_id,a.contractor_title,a.contractor_description,a.contractor_phone,a.contractor_address,a.contractor_name
                    FROM 
                    ".Config::$tables['contractor_table']." a
                    LEFT JOIN
                    ".Config::$tables['contractorRating_table']." b ON a.contractor_id=b.contractor_id
                    WHERE a.contractor_name='".$contractorName."'AND a.delete_flag=0
                    GROUP BY a.contractor_name";
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
                $i++;
            }
        }
        return $response[0];
    }
    public function getSectionsForContractor($details){
        if ($details['contractor_id']) {
            $contractor_id=$this->mysqli->real_escape_string($details['contractor_id']);
            $query="SELECT f.category_title,e.section_title
                    FROM 
                    ".Config::$tables['contractor_table']." a
                    LEFT JOIN
                    ".Config::$tables['contractorMapping_table']." b ON b.contractor_id=a.contractor_id
                    LEFT JOIN
                    ".Config::$tables['categorySection_table']." c ON c.categorySection_id=b.categorySection_id
                    LEFT JOIN
                    ".Config::$tables['placeCategory_table']." d ON d.placeCategory_id=c.placeCategory_id
                    LEFT JOIN
                    ".Config::$tables['section_table']." e ON e.section_id=c.section_id
                    LEFT JOIN
                    ".Config::$tables['category_table']." f ON f.category_id=d.category_id
                    WHERE
                    a.delete_flag=0
                    AND a.contractor_id='".$contractor_id."'
                    GROUP BY e.section_title";
            if ($result = $this->mysqli->query($query)) {
                $i=0;
                while ($row = $result->fetch_object()) {
                    $response[$i]['category_title']=$row->category_title;
                    $response[$i]['section_title']=$row->section_title;
                    $i++;
                }
            }
        }
        return $response;
    }
    public function getPlacesForContractor($details){
        if ($details['contractor_id']) {
            $contractor_id=$this->mysqli->real_escape_string($details['contractor_id']);
            $query="SELECT f.place_title
                    FROM 
                    ".Config::$tables['contractor_table']." a
                    LEFT JOIN
                    ".Config::$tables['contractorMapping_table']." b ON b.contractor_id=a.contractor_id
                    LEFT JOIN
                    ".Config::$tables['categorySection_table']." c ON c.categorySection_id=b.categorySection_id
                    LEFT JOIN
                    ".Config::$tables['placeCategory_table']." d ON d.placeCategory_id=c.placeCategory_id
                    LEFT JOIN
                    ".Config::$tables['section_table']." e ON e.section_id=c.section_id
                    LEFT JOIN
                    ".Config::$tables['place_table']." f ON f.place_id=d.place_id
                    WHERE
                    a.delete_flag=0
                    AND a.contractor_id='".$contractor_id."'
                    GROUP BY f.place_id";
            if ($result = $this->mysqli->query($query)) {
                $i=0;
                while ($row = $result->fetch_object()) {
                    $response[$i]['place_title']=$row->place_title;
                    $i++;
                }
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
                    a.delete_flag=0
                    AND b.delete_flag=0
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
                    a.delete_flag=0
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
    public function getContractorsMeta($details){
        return array(
            'keywords'=>false,
            'description'=>'We are the only company providing roofing contractors in Dallas ,with 5 Stars certified ratings ,giving you the confidence in choosing the right company',
            'title'=>$details[0]['place_title'].' | '.$details[0]['category_title'].' | '.$details[0]['section_title'].' | Contractors'
        );
    }
    public function getMeta($details){
        return array(
            'keywords'=>'',
            'description'=>'We are the only company providing roofing contractors in Dallas ,with 5 Stars certified ratings ,giving you the confidence in choosing the right company. '.$details['contractor_description'],
            'title'=>$details['contractor_title']
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
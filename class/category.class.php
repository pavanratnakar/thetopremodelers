<?php
class Category{
    private $mysqli;
    private $utils;
    private $categories;
    private $category;
    public function __construct() {
        $this->mysqli=new mysqli(Config::$db_server,Config::$db_username,Config::$db_password,Config::$db_database);
        $this->utils=new Utils();
    }
    public function getCategory($place_name){
        $place_name=$this->mysqli->real_escape_string($place_name);
        $query="SELECT a.category_title,a.category_name,a.active
                    FROM 
                    ".Config::$tables['category_table']." a
                    LEFT JOIN 
                    ".Config::$tables['placeCategory_table']." b ON a.category_id=b.category_id
                    LEFT JOIN 
                    ".Config::$tables['place_table']." c ON c.place_id=b.place_id
                    WHERE 
                    a.delete_flag=FALSE
                    AND b.delete_flag=FALSE
                    AND c.delete_flag=FALSE
                    AND a.active=1 
                    AND c.place_name='".$place_name."'";
        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['category_title']=$row->category_title;
                $response[$i]['category_name']=$row->category_name;
                $response[$i]['active']=$row->active;
                $i++;
            }
        }
        return $response;
    }
    public function getCategories($position=null){
        $position_query='';
        if($position){
            $position_query = 'AND position='.$position;
        }
        $query="SELECT a.category_id, a.category_name, a.category_value, a.active
                    FROM 
                    ".Config::$tables['category_table']." a
                    WHERE 
                    a.delete_flag=FALSE 
                    ".$position_query."
                    ORDER BY a.category_order ASC";

        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['category_id']=$row->category_id;
                $response[$i]['category_name']=$row->category_name;
                $response[$i]['category_value']=$row->category_value;
                $response[$i]['active']=$row->active;
                $i++;
            }
        }
        $this->categories = $response;
    }
    public function getFormatedCategories($position,$placeName){
        $this->getCategories($position);
        $response = '';
        for($i=0;$i<sizeof($this->categories);$i++){
            $href= ($this->categories[$i]['active']) ? Config::$site_url.$placeName.'/'.$this->categories[$i]['category_name'].'/contractors' : Config::$site_url.'contact-us';
            $class= ($this->categories[$i]['active']) ? 'active' : 'inactive';
            $value = htmlspecialchars($this->categories[$i]['category_value']);
            $response .= '<li><a class="'.$class.'" title="'.$value.'" href="'.$href.'">'.$value.'</a></li>';
        }
        return $response;
    }
    public function getCategoryValueByName($categoryName){
        $categoryName=$this->mysqli->real_escape_string($categoryName);
        $query="SELECT category_value
                    FROM 
                    ".Config::$tables['category_table']." a
                    WHERE 
                    category_name='".$categoryName."'
                    AND a.delete_flag=FALSE";

        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['category_value']=$row->category_value;
                $i++;
            }
        }
        return $response[0]['category_value'];
    }
    public function getMeta($categoryName,$placeName){
        $categoryName=$this->mysqli->real_escape_string($categoryName);
        $query="SELECT category_title, category_value
                    FROM 
                    ".Config::$tables['category_table']." a
                    WHERE 
                    category_name='".$categoryName."'
                    AND a.delete_flag=FALSE";
        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $category_title=$row->category_title;
                $keywords=$row->category_value;
                $description=$this->getDescription($row->category_title,$placeName);
                $i++;
            }
        }
        $keywords = ($placeName==='Dallas, TX (Texas)') ? '' : false;
        return array(
            'keywords'=>$keywords,
            'description'=>$description,
            'title'=>$placeName.' | '.$category_title
        );
    }
    public function getDescription($category_title,$place_title) {
        return 'We are the only company providing roofing contractors in Dallas ,with 5 Stars certified ratings ,giving you the confidence in choosing the right company';
    }
    public function __destruct() {
        $this->mysqli->close();
    }
}
?>
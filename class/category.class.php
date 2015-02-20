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
    public function getCategory($categoryName){
        $categoryName=$this->mysqli->real_escape_string($categoryName);
        $query="SELECT a.category_id,a.category_title,a.category_name,a.active
                    FROM 
                    ".Config::$tables['category_table']." a
                    WHERE 
                    a.delete_flag=FALSE
                    AND a.active=1 
                    AND a.category_name='".$categoryName."'";
        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['category_id']=$row->category_id;
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
    public function getFormatedCategories($position, $placeName){
        $this->getCategories($position);
        $response = '';
        for ($i=0;$i<sizeof($this->categories);$i++) {
            $href= ($this->categories[$i]['active']) ? Config::$site_url.$placeName.'/'.$this->categories[$i]['category_name'].'/contractors' : Config::$site_url.'contact-us';
            $class= ($this->categories[$i]['active']) ? 'active' : 'inactive';
            $value = htmlspecialchars($this->categories[$i]['category_value']);
            $sections = null;
            $response .= '
                <li class="dropdown">
                    <a href="'.$href.'">'.$value;
                    if (sizeof($sections) > 0) {
                        $response .= '<b class="glyphicon glyphicon-chevron-right"></b>';
                    }
                    $response .= '</a>';
            if (sizeof($sections) > 0) {
                $response .= '<ul class="dropdown-menu">';
                foreach ($sections as $section) {
                    $href = Config::$site_url.$placeName.'/'.$this->categories[$i]['category_name'].'/need/'.$section['section_name'];

                    $response .= '<li><a href="'.$href.'">'.$section['section_title'].'</a></li>';
                }
                $response .= '</ul>';
            }
            $response .= '</li>';
        }
        return $response;
    }
    // public function getSectionsForCategory($category_name,$place_name) {
    //     $query="SELECT c.section_id, c.section_name, c.section_title , a.active
    //                 FROM
    //                 ".Config::$tables['categorySection_table']." a
    //                 LEFT JOIN
    //                 ".Config::$tables['placeCategory_table']." b ON b.placeCategory_id=a.placeCategory_id
    //                 LEFT JOIN 
    //                 ".Config::$tables['section_table']." c ON c.section_id=a.section_id
    //                 LEFT JOIN
    //                 ".Config::$tables['category_table']." d ON b.category_id=d.category_id                 
    //                 LEFT JOIN
    //                 ".Config::$tables['place_table']." e ON e.place_id=b.place_id
    //                 WHERE 
    //                 a.delete_flag=FALSE 
    //                 AND b.delete_flag=FALSE 
    //                 AND c.delete_flag=FALSE 
    //                 AND d.delete_flag=FALSE 
    //                 AND e.delete_flag=FALSE 
    //                 AND e.active=TRUE 
    //                 AND d.category_name='".$category_name."' 
    //                 AND e.place_name='".$place_name."' 
    //                 ORDER BY a.categorysection_order ASC";
    //     if ($result = $this->mysqli->query($query)) {
    //         $i=0;
    //         while ($row = $result->fetch_object()) {
    //             $response[$i]['section_id']=$row->section_id;
    //             $response[$i]['section_name']=$row->section_name;
    //             $response[$i]['section_title']=$row->section_title;
    //             $response[$i]['active']=$row->active;
    //             $i++;
    //         }
    //     }
    //     $this->sections = $response;
    //     return $response;
    // }
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
            'title'=>'The Top Remodelers | '.$placeName.' | '.$category_title
        );
    }
    public function getDescription($category_title,$place_title) {
        return 'We are only certified roofing company and providing dallas based roofers. We are 5 star rating company and giving you the confidence to hiring the right contractor in your area.';
    }
    public function __destruct() {
        $this->mysqli->close();
    }
}
?>
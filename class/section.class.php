<?php
class Section {
    private $mysqli;
    private $utils;
    private $section;
    private $sections;
    private $categoryName;
    private $placeName;
    public function __construct($categoryName,$placeName) {
        $this->mysqli=new mysqli(Config::$db_server,Config::$db_username,Config::$db_password,Config::$db_database);
        $this->utils=new Utils();
        $this->categoryName = $this->mysqli->real_escape_string($categoryName);
        $this->placeName = $this->mysqli->real_escape_string($placeName);
    }
    public function getSectionDetails($sectionName) {
        $sectionName=$this->mysqli->real_escape_string($sectionName);
        $query="SELECT *
                    FROM 
                    ".Config::$tables['section_table']." a
                    WHERE 
                    section_name='".$sectionName."'
                    AND a.delete_flag=FALSE";

        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['section_title']=$row->section_title;
                $i++;
            }
        }
        return $response[0];
    }
    public function getSections() {
        $query="SELECT a.section_id, a.section_name, a.section_title , a.active
                    FROM
                    ".Config::$tables['section_table']." a ON a.category_id=b.category_id
                    LEFT JOIN
                    ".Config::$tables['category_table']." b
                    WHERE 
                    a.delete_flag=FALSE 
                    AND b.delete_flag=FALSE 
                    AND b.category_name='".$this->categoryName."'
                    ORDER BY a.category_name ASC";
        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['section_id']=$row->section_id;
                $response[$i]['section_name']=$row->section_name;
                $response[$i]['section_title']=$row->section_title;
                $response[$i]['active']=$row->active;
                $i++;
            }
        }
        $this->sections = $response;
        return $response;
    }
    public function getFormatedSections() {
        $this->getSections();
        $response = '';
        for($i=0;$i<sizeof($this->sections);$i++){
            $href= ($this->sections[$i]['section_id']==1) ? Config::$site_url.$this->placeName.'/'.$this->categoryName.'/need/'.$this->sections[$i]['section_name'] : Config::$site_url.'contact-us';;
            $class= ($this->sections[$i]['section_id']==1) ? 'active' : 'inactive';
            $response .= '<li><a class="'.$class.'" title="'.$this->sections[$i]['section_title'].'" href="'.$href.'">'.$this->sections[$i]['section_title'].'</a></li>';
        }
        return $response;
    }
    public function getSectionTitleByName($sectionName) {
        $sectionName=$this->mysqli->real_escape_string($sectionName);
        $query="SELECT section_title
                    FROM 
                    ".Config::$tables['section_table']." a
                    WHERE 
                    section_name='".$sectionName."'
                    AND a.delete_flag=FALSE";

        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['section_title']=$row->section_title;
                $i++;
            }
        }
        return $response[0]['section_title'];
    }
    public function getMeta($contractorTitle) {
        return array(
            'keywords'=>false,
            'description'=>$contractorTitle.' | The top remodelers prescreened roofing contractors with certified ratings get a quote',
            'title'=>$contractorTitle.' | The top remodelers prescreened roofing quotes'
        );
    }
    public function __destruct() {
        $this->mysqli->close();
    }
}
?>
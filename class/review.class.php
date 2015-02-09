<?php
class Review{
    private $mysqli;
    private $utils;
    public function __construct() {
        $this->mysqli=new mysqli(Config::$db_server,Config::$db_username,Config::$db_password,Config::$db_database);
        $this->utils=new Utils();
    }
    public function getReviews(){
        $query="SELECT a.project, a.date, a.region, a.description, a.rating, a.id
                    FROM
                    ".Config::$tables['review_table']." a
                    WHERE
                    1=1
                    ORDER BY a.rating ASC";
        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['id']=$row->id;
                $response[$i]['project']=$row->project;
                $response[$i]['date']=$row->date;
                $response[$i]['region']=$row->region;
                $response[$i]['description']=$row->description;
                $response[$i]['rating']=$row->rating;
                $i++;
            }
        }
        return $response;
    }
    public function __destruct() {
        $this->mysqli->close();
    }
}
?>
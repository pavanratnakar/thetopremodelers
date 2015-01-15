<?php
class Article{
    private $mysqli;
    private $utils;
    public function __construct(){
        $this->mysqli=new mysqli(Config::$db_server,Config::$db_username,Config::$db_password,Config::$db_database);
        $this->utils=new Utils();
    }
    public function getArticleDetailsByName($articleName){
        $articleName=$this->mysqli->real_escape_string($articleName);
        $query="SELECT content, title
                    FROM 
                    ".Config::$tables['article_table']." a
                    WHERE name='".$articleName."'";
        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['title']=$row->title;
                $response[$i]['content']=$row->content;
                $i++;
            }
        }
        return $response[0];
    }
    public function getMeta($articleName){
        $articleName=$this->mysqli->real_escape_string($articleName);
        $query="SELECT title,keywords,description
                    FROM 
                    ".Config::$tables['article_table']." a
                    WHERE name='".$articleName."'";
        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $title=$row->title;
                $keywords=$row->keywords;
                $description=$row->description;
                $i++;
            }
        }
        return array(
            'keywords'=>$keywords,
            'description'=>$description,
            'title'=>'Topremodelers: articles on '.$title
        );
    }
    public function __destruct(){
        $this->mysqli->close();
    }
}
?>
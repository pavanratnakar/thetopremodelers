<?php
class Article{
    private $mysqli;
    private $utils;
    public function __construct(){
        $this->mysqli=new mysqli(Config::$db_server,Config::$db_username,Config::$db_password,Config::$db_database);
        $this->utils=new Utils();
    }
    public function getArticleDetailsByName ($articleName) {
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
    public function getMeta ($articleName) {
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
    public function getArticlesByCategory ($categoryName=NULL) {
        $query = "SELECT DISTINCT c.title, a.article_id, c.name, c.category
        FROM
        ".Config::$tables['article_table']." c
        LEFT JOIN
        ".Config::$tables['articleCategory_table']." a ON c.article_id=a.article_id
        LEFT JOIN
        ".Config::$tables['category_table']." b ON b.category_id=a.category_id";
        if ($categoryName) {
            $query .= " WHERE b.category_id='".$categoryName."'";
        }
        $query .= " ORDER BY c.category, c.title";
        echo $query;
        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['title']=$row->title;
                $response[$i]['article_id']=$row->article_id;
                $response[$i]['name']=$row->name;
                $response[$i]['category']=$row->category;
                $i++;
            }
        }
        return $response;
    }
    public function __destruct(){
        $this->mysqli->close();
    }
}
?>
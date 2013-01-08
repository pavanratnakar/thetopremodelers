<?php
class Question{
    private $mysqli;
    private $utils;
    public function __construct(){
        $this->mysqli=new mysqli(Config::$db_server,Config::$db_username,Config::$db_password,Config::$db_database);
        $this->utils=new Utils();
    }
    public function getQuestions($placeName,$categoryName,$sectionName){
        $placeName=$this->mysqli->real_escape_string($placeName);
        $categoryName=$this->mysqli->real_escape_string($categoryName);
        $sectionName=$this->mysqli->real_escape_string($sectionName);
        $query="SELECT a.question_id, a.question_text,a.question_validation, g.category_text, b.question_order
                    FROM 
                    ".Config::$tables['question_table']." a
                    LEFT JOIN 
                    ".Config::$tables['sectionQuestion_table']." b ON a.question_id=b.question_id
                    LEFT JOIN 
                    ".Config::$tables['section_table']." c ON b.section_id=c.section_id
                    LEFT JOIN 
                    ".Config::$tables['categorySection_table']." d ON b.section_id=d.section_id
                    LEFT JOIN
                    ".Config::$tables['placeCategory_table']." e ON e.placeCategory_id=d.placeCategory_id
                    LEFT JOIN 
                    ".Config::$tables['category_table']." f ON e.category_id=f.category_id
                    LEFT JOIN 
                    ".Config::$tables['question_category']." g ON a.question_type=g.category_id
                    LEFT JOIN 
                    ".Config::$tables['place_table']." h ON h.place_id=e.place_id
                    WHERE c.section_name='".$sectionName."' AND f.category_name='".$categoryName."' AND h.place_name='".$placeName."' AND a.delete_flag=0
                    ORDER BY b.question_order";
        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['question_id']=$row->question_id;
                $response[$i]['question_text']=$row->question_text;
                $response[$i]['category_text']=$row->category_text;
                $response[$i]['question_validation']=$row->question_validation;
                $response[$i]['values']=$this->getAnswers($row->question_id);
                $i++;
            }
            return $response;
        }
    }
    public function getAnswers($question_id){
        $question_id=$this->mysqli->real_escape_string($question_id);
        $query="SELECT a.answer_id, a.answer_text
                    FROM 
                    ".Config::$tables['answer_table']." a
                    LEFT JOIN 
                    ".Config::$tables['questionAnswer_table']." b ON a.answer_id=b.answer_id
                    LEFT JOIN 
                    ".Config::$tables['question_table']." c ON b.question_id=c.question_id
                    WHERE a.delete_flag=0 AND b.question_id=".$question_id."
                    ORDER BY b.answer_order ASC";

        if ($result = $this->mysqli->query($query)) {
            $i=0;
            while ($row = $result->fetch_object()) {
                $response[$i]['answer_id']=$row->answer_id;
                $response[$i]['answer_text']=$row->answer_text;
                $i++;
            }
        }
        return $response;
    }
    public function getQuestionResponse($questionAnswerArray){
        $key_pair = '';
        $i=0;
        foreach ($questionAnswerArray as $key => $value) {
            $question_id = $this->utils->checkValues($key);
            $value = $this->utils->checkValues($value);
            $key_pair.= $key.',';
        }
        $key_pair = substr($key_pair,0,-1);

        $query="SELECT a.question_text,b.category_text,a.question_id
                FROM 
                ".Config::$tables['question_table']." a
                LEFT JOIN 
                ".Config::$tables['question_category']." b ON a.question_type=b.category_id
                WHERE a.delete_flag=0 AND a.question_id in (".$key_pair.") order by a.question_id";
        if ($result = $this->mysqli->query($query)) {
            while ($row = $result->fetch_object()) {
                $response[$i]['question_text']=$row->question_text;
                if($row->category_text == 'text'){
                    $response[$i]['answer_test']=$questionAnswerArray[$row->question_id];
                } else {
                    $query1="SELECT a.answer_text
                            FROM 
                            ".Config::$tables['answer_table']." a
                            WHERE a.delete_flag=0 AND a.answer_id =".$questionAnswerArray[$row->question_id]."";
                    if ($result1 = $this->mysqli->query($query1)) {
                        while ($row1 = $result1->fetch_object()) {
                            $response[$i]['answer_test']=$row1->answer_text;
                        }
                    }
                }
                $i++;
            }
        }
        return $response;
    }
    public function formatQuestionResponse($questionAnswerArray){
        $questionAnswerArray = $this->getQuestionResponse($questionAnswerArray);
        $response = '';
        for($i=0;$i<sizeof($questionAnswerArray);$i++){
            $response .= '<b>Question</b> : '.$questionAnswerArray[$i]['question_text'].'<br/><b>Answer</b> : '.$questionAnswerArray[$i]['answer_test'].'<br/><br/>';
        }
        return $response;
    }
    public function __destruct(){
        $this->mysqli->close();
    }
}
?>
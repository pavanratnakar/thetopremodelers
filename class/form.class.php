<?php
Class Form{
    private $type;
    private $value;
    
    public function createElement($type,$label,$name,$validationClass,$answers=null){
        $this->type = $type;
        $this->label = $label;
        $this->name = $name;
        $this->answers = $answers;

        $response = '<li class="rowElem">';
        switch ($this->type) {
            case 'text':
                $response .= '<label for="'.$this->name.'">'.$this->label.'</label><input class="'.$validationClass.' shadow" id="'.$this->name.'" type="text" name="'.$this->name.'" />';
                break;
            case 'password':
                $response .= '<label for="'.$this->name.'">'.$this->label.'</label><input class="'.$validationClass.' shadow"  id="'.$this->name.'" type="checkbox" name="'.$this->name.'" />';
                break;
            case 'radio':
                $response .= '<label for="'.$this->name.'">'.$this->label.'</label>';
                    if($this->answers) {
                        $i=0;
                        foreach($this->answers as $answer){
                            if($i==0){
                                $checked = 'checked="checked"';
                            } else {
                                $checked = "";
                            }
                            $response .= '<div class="clear"></div><input class="'.$validationClass.' shadow"  type="radio" '.$checked.' name="'.$this->name.'" /><span>'.$answer['answer_text'].'</span>';
                            $i++;
                        }
                    }
                break;
            case 'select':
                $response .= '<label for="'.$this->name.'">'.$this->label.'</label><select class="'.$validationClass.' shadow" id="'.$this->name.'" name="'.$this->name.'">';
                    if($this->answers) {
                        foreach($this->answers as $answer){
                            $response .= '<option value="'.$answer['answer_id'].'" />'.$answer['answer_text'].'</option>';
                        }
                    }
                     $response .= '</select>';
                break;
        }
        $response .= '</li>';
         return $response;
    }
}
?>
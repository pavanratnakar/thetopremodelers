<?php
include_once(Config::$site_path.'global/Class/page.class.php');
class Page extends PageBase
{
    public function __construct()
    {
        $this->title='The Top Remodelers | Admin Module';
        $this->description='The Top Remodelers Admin Module';
        $this->keywords='';
    }
    public function printSubModules($subModule,$firstDate=null,$lastDate=null)
    {
        $return='';
        $return.='<div class="optionContainer left">';
        if($firstDate)
        {
            $return.='
            <div id="'.$subModule.'_date_container" class="left date_container">
                <form id="'.$subModule.'_date_form" method="post" action="">
                    To: <input type="text" class="toDate datePicker" value="'.$firstDate.'"/>
                    From: <input type="text" class="fromDate datePicker" value="'.$lastDate.'"/>
                    <input type="submit" value="Submit"/>
                </form>
            </div>';
        }
        $return.='<div id="'.$subModule.'_select_container" class="right select_container"></div>
        </div>
        <div class="clear"></div>
        <table id="'.$subModule.'"></table>
        <div id="p_'.$subModule.'"></div>
        <script type="text/javascript">
            herve_grid.jqgrid.'.$subModule.'.init();
        </script>';
        return $return;
    }
}
?>
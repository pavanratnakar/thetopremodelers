<?php
class Utils{
    public function checkValues($value){
        $value= nl2br($value);
        $value = trim($value);
        if (get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        }
        $value = strtr($value,array_flip(get_html_translation_table(HTML_ENTITIES)));
        $value = strip_tags($value,'<br>');
        return $value;
    }
    public function ip_address_to_number($IPaddress){
        if ($IPaddress == "") {
            return 0;
        } else {
            return ip2long($IPaddress);
        }
    }
    public function paginate($num_rows,$items,$page){
        $nrpage_amount = $num_rows/$items;
        $page_amount = ceil($num_rows/$items);
        $page_amount = $page_amount-1;
        $page = $page ? $page : '1';
        if ($page < "1") {
            $page = "0";
        }
        $p_num = $items*$page;
        $section;
        if ($page_amount != "0") {
            echo "<div class=paginate>";
                if ($page != "1" && $page != "1") {
                    $prev = $page-1;
                    $disabled = $prev==0 ? 'disabled' : '';
                    echo '<button data-page="'.$prev.'" class="'.$disabled.'" disabled="'.$disabled.'" type="button" title="Previous">&laquo;Prev</button>';
                }
                for ($counter = 0; $counter <= $page_amount; $counter += 1) {
                    echo '<button data-page="'.($counter+1).'" class="'.(($counter+1)==$page ? 'current' : '').'" type="button" title="'.($counter+1).'">'.($counter+1).'</button>';
                }
                if ($page*$num_rows < $items) {
                    $next = $page+1;
                    $disabled = $prev==0 ? 'disabled' : '';
                    echo '<button data-page="'.$page.'" class="'.$disabled.'" disabled="'.$disabled.'" type="button" title="Next">Next&raquo;</button>';
                }
            echo "</div>";
        }
    }
}
?>
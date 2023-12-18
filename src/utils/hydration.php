<?php

function loadJS($js_file)
{
    echo "<script type='module' defer>";
    echo "import { load } from '" . $js_file . "';";
    echo "load();";
    echo "</script>";
}

?>
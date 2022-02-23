<?php
    include "table.php";

if(isowner(12,2)){
    print "True";
}
else {
    print "False";
}
var_dump($_SESSION);
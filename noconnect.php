<?php

include "templateClass.php";

$page = new DBayPage( "Failed to connect to database", "noconnect-CONTENT.html" );
$page -> show();

?>
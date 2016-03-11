<?php
class DBayPage{
	private $pageTitle = "DBay - ";
	private $pageContent;
	public function __construct(){
		global $pageTitle;
		global $pageContent;
		if (func_num_args()>0){
			$pageTitle  .= func_get_arg(0);
			$pageContent = func_get_arg(1); 
		} else {
			$pageTitle  .= "Welcome to DBay!";
			$pageContent = "index.php";
		}
	}
	public function show(){
		global $pageTitle;
		global $pageContent;
		echo "<!DOCTYPE html>
	<html lang=\"en\">
    <head>
        <meta charset=\"utf-8\">
        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">

        <title>$pageTitle</title>

        <link href=\"css/bootstrap.min.css\" rel=\"stylesheet\">
        <link href=\"css/style.css\" rel=\"stylesheet\">

    </head>
    <body>";

           //<div class=\"container-fluid\">";
            include "include/header.php";
            echo "<section class=\"row\">
                <div class=\"col-md-12\">";
                    include $pageContent;
                echo "</div>
            </section>";
            include "footer.php";
        echo "</div>
        
        <script src=\"js/jquery.min.js\"></script>
        <script src=\"js/bootstrap.min.js\"></script>
        <script src=\"js/scripts.js\"></script>
    </body>
</html>";
		
	}
}

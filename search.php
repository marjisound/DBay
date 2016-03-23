<?php include 'include/sessions.php' ?>
<?php include 'include/connections.php' ?>
<?php require_once'include/functions.php' ?>

<!DOCTYPE html>
<html>

<!-- Bootstrap examples -->
<head>
	<meta charset="UFT-8" >
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/search.css">
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<title>Search</title>
</head>
<body>

	<div class="container">
	<?php include'include/header.php' ?>
	<?php

	function cat_search($category_id) {
		global $connection;
		$query = "SELECT item_name";
		$query .= " FROM item";
		$query .= " JOIN item_category";
		$query .= " ON item.item_id = item_category.item_id";
		$query .= " WHERE item_category.category_id = ?";
		$stmt = mysqli_prepare($connection, $query);

		mysqli_stmt_bind_param($stmt, "i", $category_id);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		mysqli_stmt_close($stmt);
		return $result;
	}


		if(isset($_GET['user_query'])):
			$user_query = htmlspecialchars($_GET['user_query']);
			// echo "MARJAN: ".$user_query;
		else:
			$user_query="";
		endif;
		if(isset($_GET['res'])):
			$rev_identifier = $_GET['res'];
			switch ($rev_identifier)
			 {
				case 'r1':
					$resString = " reserve_price BETWEEN 0 AND 30 ";
					$curr_res ="r1";
					break;
				case 'r2':
					$resString = " reserve_price BETWEEN 30 AND 100 ";
					$curr_res ="r2";
					break;
				case 'r3':
					$resString = " reserve_price >= 100 ";
					$curr_res ="r3";
					break;
				default:
					$resString= "";
					$curr_res ="";
					break;
			}
		else:
			$resString="";
			$curr_res ="";
		endif;
		if(isset($_GET['limit'])):
			$limit = $_GET['limit'];
			switch ($limit)
			 {
				case '5':
					$limString = " LIMIT 5";
					$curr_lim='5';
					break;
				case '10':
					$limString = " LIMIT 10";
					$curr_lim='10';
					break;
				case 'm':
					$limString = "";
					$curr_lim='m';
					break;
				default:
					$limString= "";
					$curr_lim ="";
					break;
			}
		else:
				$limString= "";
				$curr_lim='';
		endif;

		$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : '';

		$result = user_search($user_query,$limString,$resString, $category_id);


		?>
		<div class="row">
			<section class="col-sm-6">
				<h1></h1>
		 			<div class="">
		
						<ul class="search-dropdown">
							<li>
					 			<div class="dropdown">
    								<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Order By Category
    								<span class="caret"></span>
    								</button>
    								<ul class="dropdown-menu">
    								<?php
	                                    $query = "select * from category";
	                                    $result_set = mysqli_query($connection, $query);
	                                    while($row = mysqli_fetch_assoc($result_set)){
	                                        echo '<li value="'.$row['category_id'].'"><a href="search.php?category_id='.$row['category_id'].'&res='.$curr_res.'&limit='.$limString.'">'. $row['category_name'].'</a></li>';
	                                    }
	                                    mysqli_free_result($result_set);
                                	?>
   									<!--  <ul class="dropdown-menu">
    									  <li><a href="#">Cat1</a></li>
     									 <li><a href="#">Cat2</a></li>
     								 	<li><a href="#">Cat3</a></li>
   								 	</ul> -->

  								</div>		
                            </li>
							<li>
					 			<div class="dropdown" style="padding:2px">
    								<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" >Order By Reservation Price
    								<span class="caret"></span>
    								</button>
    								
   									 <ul class="dropdown-menu">
     									 <li><a href='search.php?user_query=<?php echo $user_query;?>&res=r1&limit=<?php echo $curr_lim;?>&category_id=<?php echo $category_id ?>'>0-30</a></li>
     								 	<li><a href='search.php?user_query=<?php echo $user_query;?>&res=r2&limit=<?php echo $curr_lim;?>&category_id=<?php echo $category_id ?>'>30-100</a></li>
     								 	<li><a href='search.php?user_query=<?php echo $user_query;?>&res=r3&limit=<?php echo $curr_lim;?>&category_id=<?php echo $category_id ?>'>100 &gt;</a></li>
   								    </form>
  								</div>		
                            </li>
                            <li>
					 			<div class="dropdown">
    								<button class="btn btn-primary dropdown-toggle" type="button" 		data-toggle="dropdown">Show Up To
    								<span class="caret"></span>
    								</button>
   									 <ul class="dropdown-menu">
    									  <li><a href='search.php?user_query=<?php echo $user_query;?>& limit=5&res=<?php echo $curr_res;?>&category_id=<?php echo $category_id ?>'>5 Items</a></li>
     									 <li><a href='search.php?user_query=<?php echo $user_query;?>& limit=10&res=<?php echo $curr_res;?>&category_id=<?php echo $category_id ?>'>10 Items</a></li>
     								 	<li><a href='search.php?user_query=<?php echo $user_query;?>& limit=m&res=<?php echo $curr_res;?>&category_id=<?php echo $category_id ?>'>More than 10</a></li>
   								 	</ul>
  								</div>		
                            </li>
						</ul>

					</div>

   			</section>
   			<div class="clearfix visible-sm"></div>
			<section class="col-md-6">
				
				<?php
				if(!empty($result)):
				if(!empty($result->fetch_assoc())):
					foreach($result as $rows):
						$name = $rows['item_name'];
						$curr_price = $rows['item_description'];
						$item_image = $rows['file_name'];
						$reserve_price = $rows['reserve_price'];
						$a_id = $rows['auction_id'];
						$enddate = $rows['end_date'];
						$img_id = $rows['image_id'];
						$timetoend = time_to_end($enddate);
						echo 

				'
					<div class="rows">
						<section class="col-xs-4">

							<!--for image -->';
							if (!empty($item_image)) {
								echo '<img src="uploads/'.$item_image.'" class="img-rounded" style="width:150px">';
							}else{
								echo '<img src="http://www.aviatorcameragear.com/wp-content/uploads/2012/07/placeholder.jpg" class="img-rounded" style="width:150px">';
							}
						echo	

						'</section>
						<section class="col-md-8">
							<h3 class="" style="margin-left: 20px;"><a href=auction.php?a_id='.$a_id.'>'.$name.'</a></h3>
							<ul class="search-list" style="margin-left: -15px;">
								<li class="">
									<span class="bold"><b>Description</b> :'. $curr_price.'</span>
								</li>
								<li>
									<span> <b>Reservation Price </b>:'. $reserve_price.'</span>
								</li>
								<li class=""> <b> Time to end :</b>'.$timetoend.
							'</ul>
						</section>
					</div>
				
				<div class="clearfix visible-sm"></div>
				 <hr/>';
				 endforeach;
				 freeresult($result);
				endif;
				else:
					echo '<div class=""> 
							"your search returned no result"
							</div>';
				endif;
				?>
 	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	</body>

	
</html>

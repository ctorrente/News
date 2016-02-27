<?php
	include('functions.php');
	include('connection.php');
	if(isset($_POST['login']) && $_POST['username'] != null && $_POST['password'] != null){
		login($conn, $_POST['username'], $_POST['password']);
	}

	if(isset($_POST['logout'])){
		session_unset();
		session_destroy();
	}

	//DELETE
	//Status - Finished
	if(isset($_POST['deleteNewsId'])){
		$query = 'SELECT * FROM news n WHERE n.news_id = ' . $_POST['deleteNewsId'] . ';' ;
		$exec = mysqli_query($conn, $query);
		$row = mysqli_fetch_assoc($exec);
		$query = 'DELETE FROM news WHERE news_id = ' . $row['news_id'] . ';';
		$exec = mysqli_query($conn, $query);
		$query = ' DELETE FROM picture WHERE picture_id = ' . $row['picture_id'] . ';';
		$exec = mysqli_query($conn, $query);
		echo '<script type="text/javascript">alert("Delete Success!")</script>';
	}

	
?>
<html>
	<script type="text/javascript">
		function logout(){
			var logoutForm = document.getElementById("logoutForm");
			logoutForm.logout = 1;
			logoutForm.submit();
		}

		function readMore(news_id){
			var form = document.getElementById("readMoreForm");
			form.news_id.value = news_id;
			form.submit();
		}

		function deleteNews(deleteNewsId){
			var tmp = confirm('Are your sure?');
			if(tmp){
				var form = document.getElementById("deleteNewsForm");
				form.deleteNewsId.value = deleteNewsId;
				form.submit();
			}
		}

		function editNewsFunction(edit_news_id){
			var form = document.getElementById("editNewsForm");
			form.editNewsId.value=edit_news_id;
			form.submit();
		}
	</script>
	<form action="login.php" method="POST" id="logoutForm">
		<input type="hidden" name="logout" value="">
	</form>
	<form action="readMore.php" method="POST" id="readMoreForm">
		<input type="hidden" name="news_id" value="">
	</form>	
	<form action="viewAllNews.php" method="POST" id="deleteNewsForm">
		<input type="hidden" name="deleteNewsId" value="">
	</form>	
	<form action="editNews.php" method="POST" id="editNewsForm">
		<input type="hidden" name="editNewsId" value="">
	</form>	
	<head>
		<!--<link href='https://fonts.googleapis.com/css?family=Lora' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>-->
		<link rel="stylesheet" href="functionalities/css/contactus.css">
		<link rel="stylesheet" href="functionalities/css/calendar.css">
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/navbar-footer.css">
		<link rel="stylesheet" href="css/calendar.css">
		<link rel="stylesheet" href="css/events.css">
		<link rel="stylesheet" href="css/eventlist.css">

		<script src="js/jquery-latest.min.js" type="text/javascript"></script>
		<script src="js/script.js"></script>
		<script src="js/home.js"></script>
		<title>Adnu DCS</title>
		<style type="text/css">
		</style>
	</head>
<body>
<div class="toplogo">
	<a href='home.php'><img src="css/temp/dcs-sign.png" /></a>
</div>

<!--start navbar-->

<div class="nav">
	<div class="container">
		<div id='cssmenu'>
			<ul>
				<li><a href='#'>About</a></li>
				<li><a href='#'>Admission</a></li>
				<li><a href='#'>Faculty</a></li>
				<?php 
					if($_SESSION['user_type'] != 'S'){
						echo '<li class="has-sub" style="float: left;"><a href="#">News</a>' . 
							'<ul>' . 
								'<li><a href="viewAllNews.php">All News</a></li>' . 
								'<li><a href="MyNews">My Posted News</a></li>' . 
							'<li><a href="addNews.php">Add News</a></li>'.
							'</ul>'.
						'</li>';
					}else
						echo '<li><a href="viewAllNews.php">News</a></li>';
				?>
				<li><a href='#'>Policies</a></li>

				<li class='has-sub'><a href='#'><?php echo $_SESSION['first_name']?></a>
					<ul>
						<li><a onclick="logout()">Log Out</a></li>
						 <li class='has-sub'><a href='#'>Option 1</a>
							<ul>
								 <li><a href='#'>Sub Option 1</a></li>
								 <li><a href='#'>Sub Option 2</a></li>
							</ul>
						 </li>
						 <li class='has-sub'><a href='#'>Option 1</a>
							<ul>
								 <li><a href='#'>Sub Option 1</a></li>
								 <li><a href='#'>Sub Option 2</a></li>
							</ul>
						 </li>
					</ul>
				</li>
				<li class="msg"><a data-toggle="open-modal" data-target="#contact">&#9993;</a></li>
				<li class="msg">
					<input type="search" placeholder="&#x1f50e; Search" /></li>
			</ul>
		</div>
	</div>
</div>

<!--end navbar-->

	<!--start content-->

	<div class="container">
		<div class="content-wrapper" >
		<?php 
			if($_SESSION['user_type'] != 'S')
				echo '<a href="addNews.php" class="btn" style="margin-top: 5%;">&#10133; Add</a>';
		?>
    	
			<div class="content">
				<div style="font-size: 30px; margin-left: 5%; margin-top: 2%;">
					<h2 >Headline</h2>
				</div>
				<?php
					$query = 'select * from news n, picture p where n.picture_id = p.picture_id';
					$exec =  mysqli_query($conn, $query);
					$num_rows = mysqli_num_rows($exec);

					if($num_rows == 0){
						echo '<i><div style="margin: 0 auto; margin-top: 20%;">Their are no News that are posted.<div></i>';
					}
					foreach($exec as $row){
						if($_SESSION['username'] != $row['username']){
							?>
							<div class="card" style="margin-top: 15%;">
							    <!-- Header -->
							    <div class="card-img">
							    	<img src="<?php echo $row['file_path']?>">
							      	<?php echo '<a class="rdmr" onclick="readMore(' .  $row['news_id'] . ')">Read More</a>';?>
							    </div>
							    <!-- Content-->
							    <div class="card-content">
							      	<div class="title"><?php echo $row['title']?></div>
							      	<div class="desc">
							      		<?php 
							      			$words = explode(' ', $row['details']);
							      			
							      			for($i = 0; $i < 5; $i++){
							      				echo $words[$i] . ' ';
							      			}
							      			echo '....';
							      		?>
							      	</div>
							    </div>

							    
							    <!-- Footer-->
							    <div class="div">
							    	<i class="i"><strong>Date posted: </strong><?php $tmp = strtotime($row['date_posted']); echo date("M/d/Y", $tmp);?></i>
							    	 <?php
								    	$query = 'SELECT * FROM users WHERE username = \'' . $row['username'] . '\'';
								    	
								    	$poster_row = mysqli_query($conn, $query);
								    	foreach($poster_row as $poster){
							   		 		echo '<i class="i"> <strong>By:</strong> ' . $poster['first_name'] . '</i>';
							    		}
							    	?>
							    </div>
							</div>
						
							<?php
						}else{
							?>
							<div class="card" style="margin-top: 15%;">
							    <!-- Header -->
							    <div class="card-img">
							    	<img  src="<?php echo $row['file_path']?>">
							      	<a href="#" class="rdmr" onclick="readMore('<?php echo $row['news_id']?>')">Read More</a>
							    </div>
							    <!-- Content-->
							    <div class="card-content">
							      	<div class="title"><?php echo $row['title']?></div>
							      	<div class="desc">
							      		<?php 
							      			$words = explode(' ', $row['details']);
							      			if(count($words) > 4){
								      			for($i = 0; $i < 5; $i++){
								      				echo $words[$i] . ' ';
								      			}
								      			echo '....';
							      			}else
							      				echo $row['details'];

							      		?>
							      	</div>
							    </div>
							    <!-- Footer-->
							    <div class="admin-btn">
							    	<div class="edit" onclick="editNewsFunction(<?php echo $row['news_id']?>)"> <span >Edit</span>
							        	<div class="label"></div>
							      	</div>
							      	<div class="delete" onclick="deleteNews(<?php echo $row['news_id']?>)"> <span >Delete</span>
							        	<div class="label"></div>
							      	</div>
							    </div>
							</div>
							<?php
						}
					}
				?>
 			</div>
		</div>
	</div>
	<!--end content-->

	<!--start footer-->
	<footer>
		<div class="lookWrap">
			<a class="btn btn-lg btn-success js-modal" href="#" role="button" data-toggle="modal" data-target="#demoModal"><h2>Contact Us</h2></a>
			<div id="look">
				<div class="contactus">
					<h3>Ateneo de Naga University</h3>
					<p>Ateneo de Naga University<br>
						Ateneo Avenue, 4400 <br>
						Naga City, Philippines<br><br>
						Tel. Nos: Trunkline (63 54) 472-2368<br>
						Fax No: (63 54) 473-9253</p>
				</div>
				<div class="contactus">
					<h3>Department of Computer Science</h3>
					<p>2F Rm P219 Phelan Building <br>
						Ateneo de Naga University <br>
						Ateneo Avenue, 4400 <br>
						Naga City, Philippines <br><br>
						Email: dcs@adnu.edu.ph <br>
						Phone: 054 472 2368 ext 2422 </p>
				</div>
			</div>
		</div>
		<div class="legality">
			&copy; Copyright 2016 by Your Company
		</div>
	</footer>
<div class="modal" id="contact">
		<div class="contactpop">
			<form class="contact-form">
				<div class="name">
					<input type="text" id="name" placeholder="Full Name"/>
				</div>  
				<div class="email">
					<input type="email" id="email" placeholder="Email"/>
				</div>
				<div class="message">
					<textarea name="message" id="message" placeholder="Message"></textarea>
				</div>
				<div class="submit">
					<input type="submit" value="Send" class="button" />
				</div>
			</form>
		</div>
	</div>
</body>
<script type="text/javascript">

	$("*").click(function(){
		var button = $(this);
		if(button.data('toggle') == "open-modal") {
			var target = button.data('target');
			$('body').append('<div class="modal-backdrop"></div>');
			$('.modal').css("overflow-y", "auto");
			$('html').css("overflow", "hidden");
			$('.modal-backdrop').fadeIn("fast");
			$(target).fadeIn("fast");
		}
	});
</script>
<html>

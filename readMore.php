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

	$num_rows = null;
	

	//UPDATE
	if(isset($_POST['news_idSave'])){
		$title = '\'' . $_POST['title'] . '\'';
		$details = '\'' . $_POST['details'] . '\'';
		$username = '\'' . $_SESSION['username'] . '\'';

		$query = 'SELECT * FROM news n, picture p WHERE n.news_id = ' . $_POST['news_idSave'] . ' AND n.picture_id = p.picture_id;';
		$exec = mysqli_query($conn, $query) or die(mysqli_error());
		$row = mysqli_fetch_assoc($exec);
		
		$file_path = $row['file_path'];

		$query = 'UPDATE news set title = ' . $title . ', details = ' . $details . ', date_posted = NOW();';
		$exec = mysqli_query($conn, $query) or die(Mysqli_error());

		move_uploaded_file($_FILES['picture']['tmp_name'], $file_path);

		$query = 'SELECT * FROM news n, picture p, users u WHERE n.news_id = ' .  $_POST['news_idSave'] . ' AND ' .
			'n.picture_id = p.picture_id AND n.username = u.username AND n.username = \'' . $_SESSION['username'] . '\'';
		$exec = mysqli_query($conn, $query) or die(Mysqli_error());
		$num_rows = mysqli_num_rows($exec);
	}

	if(isset($_POST['news_id'])){
		$query = 'SELECT * FROM news n, picture p, users u WHERE n.news_id = ' .  $_POST['news_id'] . ' AND ' .
			'n.picture_id = p.picture_id AND n.username = u.username AND n.username = \'' . $_SESSION['username'] . '\'';
		$exec = mysqli_query($conn, $query) or die(Mysqli_error());
		$num_rows = mysqli_num_rows($exec);
	}
?>
<html>
	<script type="text/javascript">
		function logout(){
			var logoutForm = document.getElementById("logoutForm");
			logoutForm.logout = 1;
			logoutForm.submit();
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
	<!--Core Modules -->
	<div class="container">
		<div class="content-wrapper" >
    	<?php 
			if($_SESSION['user_type'] != 'S')
				echo '<a href="addNews.php" class="btn" style="margin-top: 5%;">&#10133; Add</a>';

    		if(isset($_POST['news_id']) && $num_rows > 0 && $_SESSION['user_type'] != 'S' && !isset($_POST['news_idSave'])){
    	?>
				<a onclick="editNewsFunction(<?php echo $_POST['news_id']?>)" class="btn" style="margin-top: 5%;">Edit</a>
	    		<a onclick="deleteNews(<?php echo $_POST['news_id']?>)" class="btn" style="margin-top: 5%;">Delete</a>
    	<?php 
    		}
    		if(isset($_POST['news_idSave']) && $num_rows > 0 && $_SESSION['user_type'] != 'S'){
    	?>
				<a onclick="editNewsFunction(<?php echo $_POST['news_idSave']?>)" class="btn" style="margin-top: 5%;">Edit</a>
	    		<a onclick="deleteNews(<?php echo $_POST['news_idSave']?>)" class="btn" style="margin-top: 5%;">Delete</a>
    	<?php 
    		}
    	?>
			<div class="content">
				<div style="font-size: 30px; margin-left: 5%; margin-top: 2%;">
					<h2 >Headline</h2>
				</div>
				<div style="margin-top: 20%">
				<?php
					if(isset($_POST['news_id'])){
						$query = 'SELECT * FROM news n, picture p, users u WHERE n.news_id = ' .  $_POST['news_id'] . ' AND ' .
						'n.picture_id = p.picture_id AND n.username = u.username';
						$exec = mysqli_query($conn, $query);
						$row = mysqli_fetch_assoc($exec);

						echo '<h1 style="margin: 0 auto; text-align: center;">' .  $row['title'] . '</h1>';
						echo '<br><br><div><i><strong >By: </strong>' . $row['first_name'] . ' <strong>| Date posted: </strong>' . $row['date_posted'] . '</i></div>';
						echo '<img style="margin-top: 2%;" src="' . $row['file_path'] . '" width="900" height=300>'; 
						echo '<p><strong>Details: </strong>' . $row['details'] . '</p>';
						
					}else {
						if(isset($_POST['news_idSave'])){
							$query = 'SELECT * FROM news n, picture p, users u WHERE n.news_id = ' .  $_POST['news_idSave']. ' AND ' .
							'n.picture_id = p.picture_id AND n.username = u.username';
							$exec = mysqli_query($conn, $query);
							$row = mysqli_fetch_assoc($exec);

							echo '<h1 style="margin: 0 auto; text-align: center;">' .  $row['title'] . '</h1>';
							echo '<br><br><div><i><strong >By: </strong>' . $row['first_name'] . ' <strong>| Date posted: </strong>' . $row['date_posted'] . '</i></div>';
							echo '<img style="margin-top: 2%;" src="' . $row['file_path'] . '" width="900" height=300>'; 
							echo '<p><strong>Details: </strong>' . $row['details'] . '</p>';
						}else
							header('location: viewAllNews.php');
					}
				?>
				</div>
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

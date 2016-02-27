<?php
	include('functions.php');
	include('connection.php');
	if(!isset($_SESSION['loginStatus'])){
		header('location: login.php');
	}

?>

<html>
	<script type="text/javascript">
		function logout(){
			var logoutForm = document.getElementById("logoutForm");
			logoutForm.logout = 1;
			logoutForm.submit();
		}

		function Validate(form){
			var validFileExtension = [".jpg", ".jpeg", ".bmp", ".gif", ".png"];
			var inputs = document.getElementsByTagName("input");

			for(var i = 0; i < inputs.length; i++){
				if(inputs[i].type == "file"){
					var file_name = inputs[i].value;
					var match = false;
					for(var j = 0; j < validFileExtension.length; j++){
						if(file_name.substr(file_name.length - validFileExtension[j].length, validFileExtension[i].length) == validFileExtension[j]){
							match = true;
							break;
						}
					}

					if(match)
						return true;
					else{
						alert("Sorry, " + file_name + " is invalid, allowed extensions are: " + validFileExtension.join(", "));
                    	return false;
					}
				}
			}
			return false;
		}
	</script>
	<form action="login.php" method="POST" id="logoutForm">
		<input type="hidden" name="logout" value="">
	</form>
	<head>
		<!--<link href='https://fonts.googleapis.com/css?family=Lora' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>-->
		<link rel="stylesheet" href="functionalities/css/contactus.css">
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/navbar-footer.css">
		<link rel="stylesheet" href="css/carousel.css">
		<link rel="stylesheet" href="css/events.css">
		<link rel="stylesheet" href="css/addevent.css">
		<script src="js/jquery-latest.min.js" type="text/javascript"></script>
		<script src="js/script.js"></script>
		<script src="js/home.js"></script>
		

		<title>Adnu DCS</title>

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

<!--ADD NEWS FORM-->
<div class="container">
	<div class="content-wrapper">
		<div class="content">
			<?php 
				if($_POST['editNewsId']){
					$query = 'SELECT * FROM news n, picture p, users u WHERE n.news_id = ' .  $_POST['editNewsId'] . ' AND ' .
					'n.picture_id = p.picture_id AND n.username = u.username';
					$exec = mysqli_query($conn, $query);
					$row = mysqli_fetch_assoc($exec);
			?>
						<form class="addeve" onsubmit="return Validate(this);" enctype="multipart/form-data" method="POST" action="readMore.php">
							<h1>Edit News</h1>
							<input type="text" value="<?php echo $row['title']?>" name="title" placeholder="Title" required>
							<input type="file" name="picture" placeholder="Browse" required>
							<textarea name="details" placeholder="Details" required>
								<?php echo $row['details']?>
							</textarea>
							<input type="hidden" name="news_idSave" value="<?php echo $_POST['editNewsId']; ?>">
							<input type="submit" name="save" value="Save">
						</form>
			<?php
				}
			?>
		</div>
	</div>
</div>

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

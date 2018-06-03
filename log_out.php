<?php 
	// This file is used to log out from a session
	
session_start();

session_destroy();

?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>General Practioner</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="assets/main.css" />
		<link rel="icon" type="image/png" href="assets/Images/icon.ico" />
	</head>
	<body>

	<div class="top_logo_bar">
	<a href="index.php"><img src="assets/Images/logo1.png" class="logo_header"></a>

		<?php 
			// This PHP code is used to display at the top center of the page some information about the user.
			// It's works only if a session has been created before

			try {
			    $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
			}
			catch(Exception $e) {
			        die('Erreur : '.$e->getMessage());
			}

			if (isset($_SESSION) and !empty($_SESSION)) {
				if ($_SESSION['type'] == "patient") {
					// If the session exists and it's a patient

					$reponse = $bdd->query("SELECT * FROM appointment WHERE name=\"" . $_SESSION['name'] . "\" AND state=\"OK\" ");

					// Display a rectangle with the name of the patient and the number of appointments Ok with a link to see the appointments page.

					echo "<div class=\"div_number_1\">\n\t\t";
					echo "<a class=\"top_right\" href=\"./log_out.php\">";
					echo "<img class=\"small_photo\" src=\"assets/Images/cross_close.png\" alt=\"Log out\" title=\"Log out\"></a>\n";
					echo "\t\t<a href=\"Patient/appointments.php\" class=\"am_i_this_person\">\n\t\t\t" . $_SESSION['name'];
					echo "<br />\n";
					
					$cpt = 0;

					while ($reponse->fetch()) {
						$cpt ++;
					}
					
					if ($cpt > 1) {
						echo "\t\t\t<span>" . $cpt . " appointments OK</span>\n\t\t</a>\n\t</div>\n";
					}
					else {
						echo "\t\t\t<span>" . $cpt . "<span> appointment OK</span>\n\t\t</a>\n\t</div>\n";
					}
				}
				else if ($_SESSION['type'] == "doctor") {
					// If the session exists and it's a doctor

					$reponse = $bdd->query("SELECT * FROM appointment WHERE doctor=\"" . $_SESSION['name'] . "\" AND state=\"OK\" ");

					// Display a rectangle with the name of the patient and the number of appointments Ok with a link to see the doctor page.

					echo "<div class=\"div_number_1\">\n\t\t";
					echo "<a class=\"top_right\" href=\"log_out.php\">";
					echo "<img class=\"small_photo\" src=\"assets/Images/cross_close.png\" alt=\"Log out\" title=\"Log out\"></a>\n";
					echo "\t\t<a href=\"Doctor/doctor_index.php\" class=\"am_i_this_person\">\n\t\t\t Dr. " . $_SESSION['name'];
					echo "<br />\n";

					$cpt = 0;

					while ($reponse->fetch()) {
						$cpt ++;
					}
					
					if ($cpt > 1) {
						echo "\t\t\t<span>" . $cpt . " appointments OK</span>\n\t\t</a>\n\t</div>\n";
					}
					else {
						echo "\t\t\t<span>" . $cpt . "<span> appointment OK</span>\n\t\t</a>\n\t</div>\n";
					}
				}
				if ($_SESSION['type'] == "admin") {
					// If the session exists and it's a patient
					// Display a rectangle with the name of the admin with a link to see the admin page.

					echo "<div class=\"div_number_1\">\n\t\t";
					echo "<a class=\"top_right\" href=\"log_out.php\">";
					echo "<img class=\"small_photo\" src=\"assets/Images/cross_close.png\" alt=\"Log out\" title=\"Log out\"></a>\n";
					echo "\t\t<a href=\"Admin/admin_index.php\" class=\"am_i_this_person\">\n\t\t\t" . $_SESSION['name'];
					echo "<br />\n";
					
					echo "\t\t\t<span> admin</span>\n\t\t</a>\n\t</div>\n";
				}
			}

		?>
		<!-- Signup and login buttons of the top of the page -->

		<div class="top_logo_both">
			<a href="Patient/appointments.php" class="button-1">
				<div class="eff-1"></div>
				<p>Log in</p>
			</a>
			<a href="Patient/sign_up_patient.php" class="button-2">
				<div class="eff-2"></div>
				<p>Sign up</p>
			</a>
		</div>
	</div>

	<header class="top_header">
		<h1><a href="index.php">Princeton Plainsboro</a></h1>
		<p>Welcome to the General Practitioner Appointment Management System.</p>

		<nav>
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="Presentation/services.php">Services</a></li>
				<li><a href="Presentation/consultation.php">Consultation Fees</a></li>
				<li><a href="Presentation/resources.php">Resources</a></li>
				<li><a href="Patient/appointments.php">Appointments</a></li>
				<li><a href="Presentation/contact.php">Contact</a></li>
			</ul>
		</nav>
	</header>

	<?php echo "<script>alert(\"You succesfully log out.\")
			document.location.href = 'index.php'; 
			</script>"; 
	?>

	<footer>
		<div class="footer_1">
			<a href="index.php"><img src="assets/Images/logo1.png"></a>
			<p>Princeton Plainsboro trading as general practitioner group is regulated by the Central Bank of Ireland.</p>
		</div>
		<div class="footer_2">
			<div class="names">
				<p>COUTON Alexia | SN : 2940133 | ESME Lille</p>
				<p>FAUQUEMBERGUE Victor | SN : 2940129 | ESME Lille</p>
			</div>
			<div class="names">
				<p>ARBOIREAU Sebastien | SN : 2940123 | ESME Lyon</p>
				<p>POBEL-ALOTTE Thomas | SN : 2940125 | ESME Lyon</p>
			</div>
			<p><a href="Admin/log_in_private.php">Private access</a> --- <a href="Presentation/contact.php">Contact Us</a></p>
			<p class="tout_en_bas">Princeton Plainsboro, Server Side Web Development, Griffith College Dublin, 8 S Circular Rd, Ireland. All rights reserved. All contents © 2017 ESME Sudria</p>
		</div>
	</footer>

	</body>
</html>
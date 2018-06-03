<?php

session_start(); //Call to $_SESSION[]

function date_en() {
	// This function is used to display which day today is, with the open hours from the db

  	$tab_days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
  	$tab_months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
  	// We print the date with the day, number and month

    print_r($tab_days[date('N')-1]);
    echo date(', j');
    if (date('j') == 1 or date('j') == 21 or date('j') == 31) {
    	echo 'st ';
    }
    else if (date('j') == 2 or date('j') == 22) {
    	echo 'nd ';
    }
    else if (date('j') == 3 or date('j') == 23) {
    	echo 'rd ';
    }
    else {
    	echo 'th ';
    }
    print_r($tab_months[date('m')-1]);

    // We find the the right day to print the open hour in the db

    try {
	    $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
	}
	catch (Exception $e) {
		die('Erreur : ' . $e->getMessage());
	}

	$reponse = $bdd->query('SELECT * FROM timetable');
	$donnees = $reponse->fetch();

	if ($donnees[$tab_days[date('N')-1]] == "Closed") {
		echo ". Sorry, we are closed today";
	}
	else {
		echo ". We are opened between " . $donnees[$tab_days[date('N')-1]];
	}

}

function print_text($table) {

	// Return HTML code about an article of text type

	$html = "<div class=\"div_class\">\n";
	$html .= "\t<h3>" . $table['titre'] . "</h3>\n";
	$html .= "\t<p>" . $table['texte'] . "</p>\n";
	$html .= "</div>\n";

	return $html;
}

function print_link_text($table) {

	// Return HTML code about an article of link and text type

	$html = "<a class=\"div_class\" href=\"" . $table['link'] . "\">\n";
	$html .= "\t<h3>" . $table['titre'] . "</h3>\n";
	$html .= "\t<p>" . $table['texte'] . "</p>\n";
	$html .= "</a>\n";

	return $html;
}

function print_photo($table) {

	// Return HTML code about an article of photo type

	$html = "<div class=\"div_class\" href=\"" . $table['link'] . "\">\n";
	$html .= "\t<img src=\"assets/Images/" . $table['photo'] . "\">";
	$html .= "</div>\n";

	return $html;

}

function print_photo_text($table) {

	// Return HTML code about an article of photo and text type

	$html = "<div class=\"div_class\">\n";
	$html .= "\t<h3>" . $table['titre'] . "</h3>\n";
	$html .= "\t<div class=\"photo_text\">\n";
	$html .= "\t\t<img src=\"assets/Images/" . $table['photo'] . "\">";
	$html .= "\t\t<p>" . $table['texte'] . "</p>\n";
	$html .= "\t</div>\n";
	$html .= "</div>\n";

	return $html;
}

function print_link_photo($table) {

	// Return HTML code about an article of link and photo type

	$html = "<a class=\"div_class\" href=\"" . $table['link'] . "\">\n";
	$html .= "\t<h3>" . $table['titre'] . "</h3>\n";
	$html .= "\t<div class=\"photo_text\">\n";
	$html .= "\t\t<img src=\"assets/Images/" . $table['photo'] . "\">";
	$html .= "\t\t<p>" . $table['texte'] . "</p>\n";
	$html .= "\t</div>\n";
	$html .= "</a>\n";

	return $html;
}

function which_type($table) {

	// Find the type of the article and call the right function

	if ($table['type'] == "text") {
		return print_text($table);
	}
	else if ($table['type'] == "photo") {
		return print_photo($table);
	}
	else if ($table['type'] == "photo and text") {
		return print_photo_text($table);
	}
	else if ($table['type'] == "link and photo") {
		return print_link_photo($table);
	}
	else if ($table['type'] == "link and text") {
		return print_link_text($table);
	}
}

function print_page($page) {

	// Display the article form the db in the good order

	try {
	    $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
	}
	catch (Exception $e) {
		die('Erreur : ' . $e->getMessage());
	}

	// The code below will display the IDs in the same line if they are in the same ten

	$reponse = $bdd->query('SELECT * FROM ' .$page);

	// We first search the highest ID

	$max_id = -1;
	while ($donnees = $reponse->fetch()) {
		if ($donnees['id'] > $max_id) {
			$max_id = $donnees['id'];
		}
	}

	$reponse->closeCursor();
	$nb_it = (($max_id / 10)) + 1;

	// $nb_it is the number of the iterations that we need, to go to the ten of the highest ID

	for ($i=0; $i < $nb_it; $i++) {
		$reponse = $bdd->query('SELECT * FROM '. $page);
		$cpt = 0;
		while ($donnees = $reponse->fetch()) {
			// We browse the db and count the number of article we have

			if (( $donnees['id'] >= ($i*10) and $donnees['id'] < (($i+1)*10) )) {
				$cpt++;
			}
		}
		$reponse->closeCursor();

		if ($cpt > 0) {
			// If this ten in not empty we create a div to display everything in line
			$html = "<div class=\"div_print_page_article\">\n";

			$reponse = $bdd->query("SELECT * FROM " .$page . " WHERE id >= " . ($i*10) . " AND id < ". (($i+1)*10) . " ORDER BY id ");
			while ($donnees = $reponse->fetch()) {
				// We browse the ten and call the right functions to display the article
				$html .= which_type($donnees);
				$html .= "\n";
			}
			$html .= "</div>\n";
			$reponse->closeCursor();
			echo $html;
		}
	}
}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Princeton Plainsboro</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="main.css" />
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

	<h2 class="titles_h2">Home</h2>

	<div class="petit_texte_en_haut"><p>Today is <?php date_en(); ?>.</p></div>

	<?php print_page('homepage');?>

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
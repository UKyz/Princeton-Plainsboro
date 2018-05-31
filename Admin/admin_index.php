<?php

session_start(); //Call to $_SESSION[]

// Test on $_SESSION, cannot go on this page if you're not a admin

if (!isset($_SESSION) && empty($_SESSION)) {
	echo "<script>alert(\"You cannot access to this page without log in.\")
		document.location.href = './index.php'; 
		</script>";
}
else if ($_SESSION['type'] != "admin") {
	echo "<script>alert(\"You cannot access to this page without log in.\")
		document.location.href = './index.php'; 
		</script>";
}

function main() {
	// Many $_POST in this PHP file, let's see if a variable $_POST is given and which one.

	try {
	    $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
	}
	catch (Exception $e) {
		die('Erreur : ' . $e->getMessage());
	}

	if(isset($_POST) && !empty($_POST)) { 

		if ($_POST['choice'] == "add") {
			// If we use the form which add article, we search what kind of article and if everything needed is given

			if ($_POST['type'] == "text" and $_POST['texte'] == null) {
				echo "<script>alert(\"Text missed.\")</script>";
			}
			else if ($_POST['type'] == "photo and text" and ($_POST['texte'] == null or $_POST['photo'] == null)) {
				echo "<script>alert(\"Text or Photo missed.\")</script>";
			}
			else if ($_POST['type'] == "link and photo" and ($_POST['link'] == null or $_POST['photo'] == null)) {
				echo "<script>alert(\"Link or Photo missed.\")</script>";
			}
			else if ($_POST['type'] == "link and text" and ( $_POST['link'] == null)) {
				echo "<script>alert(\"Link missed.\")</script>";
			}
			else {

				// If everything is alright, we look if the ID given is not already used.

				$reponse = $bdd->query('SELECT * FROM ' . $_POST['page'] . ' WHERE id = ' . $_POST['id']);

				$donnees = $reponse->fetch();

				if ($donnees == null) {
					// If it's good, we add the article to db and refresh.

					add_to_DB($_POST);
					echo "<script>alert(\"The page will be update with your add.\")
						document.location.href = 'admin_index.php'; 
						</script>";
				}
				else {
					// If it's not, we print a message and refresh the page.

					echo "<script>alert(\"This ID is already given.\")
						document.location.href = './Admin/admin_index.php'; 
						</script>";
				}

				$reponse->closeCursor();
			}
		}
		else if ($_POST['choice'] == "update") {
			// Update form

			update($_POST);

		}
		else if ($_POST['choice'] == "delete") {
			// Delete form

			delete($_POST);

		}
		else if ($_POST['choice'] == "timetable") {
			// Timetable changes

			change_timetable();
		}
	}
}

function print_form_get() {

	// This function is used to "echo" some HTML code about an radio input. We use many of the same code in this file. We take from the variable $_GET the name page to display the input with the good value checked.

	if(isset($_GET) && !empty($_GET)) {
		if ($_GET['page'] == "homepage") {
			echo "<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"homepage\" checked/> Homepage</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"services\"/> Services</span>\n<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"consultation\"/> Consultation</span>\n<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"resources\"/> Resources</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"appointments\"/> Appointments</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"contact\"/> Contact</span>\n";
		}
		else if ($_GET['page'] == "services") {
			echo "<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"homepage\"/> Homepage</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"services\" checked/> Services</span>\n<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"consultation\"/> Consultation</span>\n<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"resources\"/> Resources</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"appointments\"/> Appointments</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"contact\"/> Contact</span>\n";
		}
		else if ($_GET['page'] == "consultation") {
			echo "<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"homepage\"/> Homepage</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"services\"/> Services</span>\n<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"consultation\" checked/> Consultation</span>\n<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"resources\"/> Resources</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"appointments\"/> Appointments</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"contact\"/> Contact</span>\n";
		}
		else if ($_GET['page'] == "resources") {
			echo "<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"homepage\"/> Homepage</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"services\"/> Services</span>\n<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"consultation\"/> Consultation</span>\n<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"resources\" checked/> Resources</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"appointments\"/> Appointments</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"contact\"/> Contact</span>\n";
		}
		else if ($_GET['page'] == "appointments") {
			echo "<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"homepage\"/> Homepage</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"services\"/> Services</span>\n<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"consultation\"/> Consultation</span>\n<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"resources\"/> Resources</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"appointments\" checked/> Appointments</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"contact\"/> Contact</span>\n";
		}
		else if ($_GET['page'] == "contact") {
			echo "<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"homepage\"/> Homepage</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"services\"/> Services</span>\n<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"consultation\"/> Consultation</span>\n<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"resources\"/> Resources</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"appointments\"/> Appointments</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"contact\" checked/> Contact</span>\n";
		}
	} 
	else {
		echo "<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"homepage\" checked/> Homepage</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"services\"/> Services</span>\n<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"consultation\"/> Consultation</span>\n<span class=\"align-left\"><input type=\"radio\" name=\"page\" value=\"resources\"/> Resources</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"appointments\"/> Appointments</span>\n<span class=\"align-right\"><input type=\"radio\" name=\"page\" value=\"contact\"/> Contact</span>\n";
	}
}

function print_text($table) {

	// Return HTML code about an article of text type

	$html = "<div class=\"div_class\">\n";
	$html .= "\t<h3>" . $table['titre'] . " ID = " . $table['id'] . "</h3>\n";
	$html .= "\t<p>" . $table['texte'] . "</p>\n";
	$html .= "</div>\n";

	return $html;
}

function print_photo($table) {

	// Return HTML code about an article of photo type

	$html = "<div class=\"div_class\" href=\"" . $table['link'] . "\">\n";
	$html .= "\t<img src=\"./assets/Images/" . $table['photo'] . "\">";
	$html .= "\t<p class=\"info_print_admin\"> Photo : " . $table['photo'] . "</p>\n";
	$html .= "\t<p class=\"info_print_admin\"> ID : " . $table['id'] . "</p>\n";
	$html .= "</div>\n";

	return $html;

}

function print_link_text($table) {

	// Return HTML code about an article of link and text type

	$html = "<a class=\"div_class\" href=\"" . $table['link'] . "\">\n";
	$html .= "\t<h3>" . $table['titre'] . " ID = " . $table['id'] . "</h3>\n";
	$html .= "\t<p>" . $table['texte'] . "</p>\n";
	$html .= "\t<p class=\"info_print_admin\"> Link : " . $table['link'] . "</p>\n";
	$html .= "</a>\n";

	return $html;
}

function print_photo_text($table) {

	// Return HTML code about an article of photo and text type

	$html = "<div class=\"div_class\">\n";
	$html .= "\t<h3>" . $table['titre'] . " ID = " . $table['id'] . "</h3>\n";
	$html .= "\t<div class=\"photo_text\">\n";
	$html .= "\t\t<img src=\"./assets/Images/" . $table['photo'] . "\">";
	$html .= "\t\t<p>" . $table['texte'] . "</p>\n";
	$html .= "\t<p class=\"info_print_admin\"> Photo : " . $table['photo'] . "</p>\n";
	$html .= "\t</div>\n";
	$html .= "</div>\n";

	return $html;
}

function print_link_photo($table) {

	// Return HTML code about an article of link and photo type

	$html = "<a class=\"div_class\" href=\"" . $table['link'] . "\">\n";
	$html .= "\t<h3>" . $table['titre'] . " ID = " . $table['id'] . "</h3>\n";
	$html .= "\t<div class=\"photo_text\">\n";
	$html .= "\t\t<img src=\"./assets/Images/" . $table['photo'] . "\">";
	$html .= "\t\t<p>" . $table['texte'] . "</p>\n";
	$html .= "\t<p class=\"info_print_admin\"> Photo : " . $table['photo'] . "</p>\n";
	$html .= "\t<p class=\"info_print_admin\"> Link : " . $table['link'] . "</p>\n";
	$html .= "\t</div>\n";
	$html .= "</a>\n";

	return $html;
}

function which_type($table) {

	// Find the type of the article and call the right function

	if ($table['type'] == "text") {
		return print_text($table);
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
	else if ($table['type'] == "photo") {
		return print_photo($table);
	}
}

function change_timetable() {

	// Take the timetable form with $_POST and update the timetable db

	try {
	    $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
	}
	catch (Exception $e) {
		die('Erreur : ' . $e->getMessage());
	}

	if (isset($_POST) && !empty($_POST)) {
		$bdd->exec("DELETE FROM timetable");

		$req = $bdd->prepare('INSERT INTO timetable (Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday) VALUES(:Monday, :Tuesday, :Wednesday, :Thursday, :Friday, :Saturday, :Sunday)');
		$req->execute(array(
			'Monday' => $_POST['Monday'],
			'Tuesday' => $_POST['Tuesday'],
			'Wednesday' => $_POST['Wednesday'],
			'Thursday' => $_POST['Thursday'],
			'Friday' => $_POST['Friday'],
			'Saturday' => $_POST['Saturday'],
			'Sunday' => $_POST['Sunday']
			));

		echo "<script>alert(\"You succesfully update the timetable.\")</script>";
	}

}

function add_to_DB($table) {

	// Add a article in the db

	try {
	    $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
	}
	catch(Exception $e) {
	        die('Erreur : '.$e->getMessage());
	}

	$req = $bdd->prepare('INSERT INTO ' . $table['page'] .'(id, titre, texte, type, photo, link) VALUES(:id, :titre, :texte, :type, :photo, :link)');
	$req->execute(array(
		'id' => $table['id'],
		'titre' => $table['titre'],
		'texte' => $table['texte'],
		'type' => $table['type'],
		'photo' => $table['photo'],
		'link' => $table['link']
		));
}

function delete($table) {

	// Delete an article in the db

	if ($_POST['id_1'] != $_POST['id_2']) {
		// We look if the ID is changed between the search and the delete
		echo "<script>alert(\"Please do not change the id before deleting.\")</script>";
	}
	else {
		try {
		    $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
		}
		catch(Exception $e) {
		        die('Erreur : '.$e->getMessage());
		}

		$bdd->exec("DELETE FROM " . trim($table['page']) . " WHERE id = \"" . trim($table['id_1']) . "\"");

		echo "<script>alert(\"You succesfully delete.\")</script>";
	}
}

function update($table) {

	// Update an article in the db

	try {
		    $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
		}
		catch(Exception $e) {
		        die('Erreur : '.$e->getMessage());
		}

	$req = $bdd->prepare("UPDATE " .$table['page']. " SET id = ".$table['id_2'].", titre = \"" .$table['titre']. "\", texte = \"" .$table['texte']. "\", type = \"" .$table['type']. "\", photo = \"" .$table['photo']. "\", link = \"" .$table['link']. "\" WHERE id = " .$table['id_1']);
	$req->execute(array(
	    'page' => $table['page'],
	    'id_2' => $table['id_2'],
	    'titre' => $table['titre'],
	    'texte' => $table['texte'],
	    'type' => $table['type'],
	    'photo' => $table['photo'],
	    'link' => $table['link'],
	    'id_1' => $table['id_1']
	    ));

	echo "<script>alert(\"You succesfully update.\")</script>";

}

function print_timetable() {

	// Display with HTML code the timetable from the db in a table

	try {
	    $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
	}
	catch (Exception $e) {
		die('Erreur : ' . $e->getMessage());
	}

	$reponse = $bdd->query('SELECT * FROM timetable');
	$donnees = $reponse->fetch();

	$html = "<form method=\"POST\" action=\"./Admin/admin_index.php\">";
	$html .= "<table class=\"tab_hour_style\">\n";
	$html .= "\t<thead><th>Days</th><th>Hours</th></thead>\n";
	$html .= "\t<tbody>\n";

	$html .= "\t\t<tr><td> Monday </td><td><input type=\"text\" name=\"Monday\" value=\"" . $donnees["Monday"] . "\"/></td></tr>\n";
	$html .= "\t\t<tr><td> Tuesday </td><td><input type=\"text\" name=\"Tuesday\" value=\"" . $donnees["Tuesday"] . "\"/></td></tr>\n";
	$html .= "\t\t<tr><td> Wednesday </td><td><input type=\"text\" name=\"Wednesday\" value=\"" . $donnees["Wednesday"] . "\"/></td></tr>\n";
	$html .= "\t\t<tr><td> Thursday </td><td><input type=\"text\" name=\"Thursday\" value=\"" . $donnees["Thursday"] . "\"/></td></tr>\n";
	$html .= "\t\t<tr><td> Friday </td><td><input type=\"text\" name=\"Friday\" value=\"" . $donnees["Friday"] . "\"/></td></tr>\n";
	$html .= "\t\t<tr><td> Saturday </td><td><input type=\"text\" name=\"Saturday\" value=\"" . $donnees["Saturday"] . "\"/></td></tr>\n";
	$html .= "\t\t<tr><td> Sunday </td><td><input type=\"text\" name=\"Sunday\" value=\"" . $donnees["Sunday"] . "\"/></td></tr>\n";
	$html .= "\t\t<input type=\"hidden\" name=\"choice\" value=\"timetable\"/>\n";

	$html .="<tr><td colspan=\"2\"><input class=\"button_like_form_style\" type=\"submit\" value=\"Update\"/></td></tr>\n";

	$html .= "\t</tbody>\n";

	$html .= "</table>\n";

	$html .= "</form>\n";

	echo $html;

}

function print_choice($table) {

	// Display the update and delete form after using the search form

	try {
	    $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
	}
	catch (Exception $e) {
		die('Erreur : ' . $e->getMessage());
	}

	$find = $bdd->query("SELECT * FROM " . trim($table['page']) . " WHERE id = " . trim($table['id']));

	$request = $find->fetch();

	if ($request == null) {
		echo "<script>alert(\"No article find for this id.\")
            document.location.href = './Admin/admin_index.php';
            </script>";
	}
	else {

		$html = "<form method=\"POST\" action=\"./Admin/admin_index.php\" class=\"form_style\">\n<ul>\n";

	    $html .="\t<div class=\"span_radio_3\">\n\t\t<span class=\"align-left\"><input type=\"radio\" name=\"choice\" value=\"update\" checked/> Update </span> <span class=\"align-right\"><input type=\"radio\" name=\"choice\" value=\"delete\" /> Delete </span>\n\t</div>\n";

	    $html .="<input type=\"hidden\" name=\"page\" value=\"" . $table['page'] . "\"/>\n";
	    $html .="<input type=\"hidden\" name=\"id_1\" value=\"" . $table['id'] . "\"/>\n";

	    $html .="<li>\n\t<input type=\"text\" name=\"id_2\" class=\"field-style field-split align-left\" placeholder=\"ID\" value=\"" . $request['id'] . "\"/><input type=\"text\" name=\"titre\" class=\"field-style field-split align-right\" placeholder=\"Title\" value=\"" . $request['titre'] . "\"/>\n</li>\n";

	    $html .="<li>\n\t<textarea name=\"texte\" class=\"field-style\" placeholder=\"Text\" value=\"" . $request['texte'] . "\">".$request['texte']."</textarea>\n</li>\n";

	    $html .="<li>\n\t<div class=\"span_radio_1\">\n\t\t<div class=\"span_radio_3\">\n";

	    if ($request["type"] == "text")
	    	$html .="<span class=\"align-left\"><input type=\"radio\" name=\"type\" value=\"text\" checked/> Text</span><span class=\"align-right\"><input type=\"radio\" name=\"type\" value=\"photo\"/> Photo</span><span class=\"align-left\"><input type=\"radio\" name=\"type\" value=\"photo and text\"/> Photo Text</span><span class=\"align-left\"><input type=\"radio\" name=\"type\" value=\"link and photo\"/> Link Photo Text</span><span class=\"align-right\"><input type=\"radio\" name=\"type\" value=\"link and text\"/> Link Text</span>";
	    else if ($request["type"] == "photo and text")
	    	$html .="<span class=\"align-left\"><input type=\"radio\" name=\"type\" value=\"text\"/> Text</span><span class=\"align-right\"><input type=\"radio\" name=\"type\" value=\"photo\"/> Photo</span><span class=\"align-left\"><input type=\"radio\" name=\"type\" value=\"photo and text\" checked/> Photo Text</span><span class=\"align-left\"><input type=\"radio\" name=\"type\" value=\"link and photo\"/> Link Photo Text</span><span class=\"align-right\"><input type=\"radio\" name=\"type\" value=\"link and text\"/> Link Text</span>";
	    else if ($request["type"] == "link and photo")
	    	$html .="<span class=\"align-left\"><input type=\"radio\" name=\"type\" value=\"text\"/> Text</span><span class=\"align-right\"><input type=\"radio\" name=\"type\" value=\"photo\"/> Photo</span><span class=\"align-left\"><input type=\"radio\" name=\"type\" value=\"photo and text\"/> Photo Text</span><span class=\"align-left\"><input type=\"radio\" name=\"type\" value=\"link and photo\" checked/> Link Photo Text</span><span class=\"align-right\"><input type=\"radio\" name=\"type\" value=\"link and text\"/> Link Text</span>";
	    else if ($request["type"] == "link and text")
	    	$html .="<span class=\"align-left\"><input type=\"radio\" name=\"type\" value=\"text\"/> Text</span><span class=\"align-right\"><input type=\"radio\" name=\"type\" value=\"photo\"/> Photo</span><span class=\"align-left\"><input type=\"radio\" name=\"type\" value=\"photo and text\"/> Photo Text</span><span class=\"align-left\"><input type=\"radio\" name=\"type\" value=\"link and photo\"/> Link Photo Text</span><span class=\"align-right\"><input type=\"radio\" name=\"type\" value=\"link and text\" checked/> Link Text</span>";
	    else if ($request["type"] == "photo")
	    	$html .="<span class=\"align-left\"><input type=\"radio\" name=\"type\" value=\"text\"/> Text</span><span class=\"align-right\"><input type=\"radio\" name=\"type\" value=\"photo\" checked/> Photo</span><span class=\"align-left\"><input type=\"radio\" name=\"type\" value=\"photo and text\"/> Photo Text</span><span class=\"align-left\"><input type=\"radio\" name=\"type\" value=\"link and photo\"/> Link Photo Text</span><span class=\"align-right\"><input type=\"radio\" name=\"type\" value=\"link and text\"/> Link Text</span>";


	    $html .="\t\t</div>\n\t</div>\n</li>";


	    $html .="<li>\n\t<input type=\"text\" name=\"photo\" class=\"field-style field-split align-left\" placeholder=\"Photo\" value=\"" . $request['photo'] . "\"/><input type=\"text\" name=\"link\" class=\"field-style field-split align-right\" placeholder=\"Link\" value=\"" . $request['link'] . "\"/>\n</li>\n";

	    $html .="<li class=\"button_middle\">\n\t<input type=\"submit\" value=\"Process\"/>\n</li>\n";

	    $html .="</ul>\n</form>\n";

	    echo $html;
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
			$html = "<div class=\"div_print_page\">\n";

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
		<title>(Admin) General Practioner</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="./assets/css/main.css" />
		<link rel="icon" type="image/png" href="./assets/Images/icon.ico" />
	</head>
	<body>

	<header class="top_header">
		<h1><a href="./index.php">Princeton Plainsboro</a></h1>
		<p>Welcome to the General Practitioner Appointment Management System.</p>

		<div id="form_admin_index">
			<form method="GET" action="./Admin/admin_index.php">
				<!-- This form is usefull to display the public page that we want -->
				<input type="radio" name="page" value="homepage" checked/> Homepage <input type="radio" name="page" value="services" /> Services <input type="radio" name="page" value="consultation" /> Consultation Fees <input type="radio" name="page" value="resources" /> Resources <input type="radio" name="page" value="appointments" /> Appointments <input type="radio" name="page" value="contact" /> Contact
				<input class="button_like_form_style" type="submit" value="Change the page" />
			</form>
		</div>
	</header>

	<?php main();?>

	<!-- We have the two forms, add and search at the top of the page -->

	<div class="div_2">

		<div class="in_div_2" id="point">

		    <form class="form_style" method="POST" action="./Admin/admin_index.php">
				<ul>
					<li>
						<div class="span_radio_1">
							<div class="span_radio_4">
								<?php 
									print_form_get();
								?>
							</div>
					    </div>
					</li>
					<li>
					    <input type="number" name="id" class="field-style field-split align-left" placeholder="ID" />
					    <input type="text" name="titre" class="field-style field-split align-right" placeholder="Title" />
					</li>
					<li>
					    <textarea name="texte" class="field-style" placeholder="Text"></textarea>
					</li>
					<li>
						<div class="span_radio_1">
							<div class="span_radio_3">
					    		<span class="align-left"><input type="radio" name="type" value="text" checked/> Text</span>
					    		<span class="align-right"><input type="radio" name="type" value="photo"/> Photo</span>
					    		<span class="align-left"><input type="radio" name="type" value="photo and text"/> Photo Text</span>
					    		<span class="align-left"><input type="radio" name="type" value="link and photo"/> Link Photo Text</span>
					    		<span class="align-right"><input type="radio" name="type" value="link text"/> Link Text</span>
					    	</div>
					    </div>
					</li>
					<li>
					    <input type="text" name="photo" class="field-style field-split align-left" placeholder="Photo" />
					    <input type="text" name="link" class="field-style field-split align-right" placeholder="Link" />
					</li>

					<input type="hidden" name="choice" value="add" />

					<li class="button_middle">
						<input type="submit" value="Add content" />
					</li>
				</ul>
			</form>
		</div>

		<div class="in_div_2">

		    <form class="form_style" method="POST" action="./Admin/admin_index.php#point">
				<ul>
					<li>
						<div class="span_radio_1">
							<div class="span_radio_4">
								<?php 
									print_form_get();
								?>
							</div>
					    </div>
					</li>
					<li>
					    <input type="number" name="id" class="field-style field-full align-non" placeholder="ID" />
					</li>

						<input type="hidden" name="choice" value="search" />

					<li class="button_middle">
						<input type="submit" value="Search content" />
					</li>
				</ul>
			</form>

			<?php
				// If we use the search form, a update and delete form will be diplayed 
				if(isset($_POST) && !empty($_POST)) {
					if ($_POST['choice'] == "search") {
						print_choice($_POST);
					}
				}
			?>

		</div>

    </div>

	<p class="div_print_page"><a href="./log_out.php" class="button_log_out">Log out</a></p>

	<p class="div_print_page"><a href="./Doctor/manage_doctor.php" class="button_log_out">Manage Doctor</a></p>

	<?php 
		// Display the page from the header form

		if(isset($_GET) && !empty($_GET)) {
			if ($_GET['page'] == "homepage") {
				echo "<h2 class=\"titles_h2\">Home</h2>\n";
				print_page('homepage');
			}
			else if ($_GET['page'] == "services") {
				echo "<h2 class=\"titles_h2\">Services</h2>\n";
				print_page('services');
			}
			else if ($_GET['page'] == "consultation") {
				echo "<h2 class=\"titles_h2\">Consultation Fees</h2>\n";
				print_page('consultation');
			}
			else if ($_GET['page'] == "resources") {
				echo "<h2 class=\"titles_h2\">Resources</h2>\n";
				print_page('resources');
			}
			else if ($_GET['page'] == "appointments") {
				echo "<h2 class=\"titles_h2\">Appointments</h2>\n";
				print_page('appointments');
			}
			else if ($_GET['page'] == "contact") {
				echo "<h2 class=\"titles_h2\">Contact</h2>\n";
				print_page('contact');
			}
		} 
		else {
			echo "<h2 class=\"titles_h2\">Home</h2>\n";
			print_page('homepage');
		}
	?>


	<?php print_timetable();?>

	<?php include('./Presentation/footer.php');?>

	</body>
</html>
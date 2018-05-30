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
	$html .= "\t<img src=\"Images/" . $table['photo'] . "\">";
	$html .= "</div>\n";

	return $html;

}

function print_photo_text($table) {

	// Return HTML code about an article of photo and text type

	$html = "<div class=\"div_class\">\n";
	$html .= "\t<h3>" . $table['titre'] . "</h3>\n";
	$html .= "\t<div class=\"photo_text\">\n";
	$html .= "\t\t<img src=\"Images/" . $table['photo'] . "\">";
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
	$html .= "\t\t<img src=\"Images/" . $table['photo'] . "\">";
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
		<link rel="icon" type="image/png" href="Images/icon.ico" />
	</head>
	<body>

	<?php include('header.php');?>

	<h2 class="titles_h2">Home</h2>

	<div class="petit_texte_en_haut"><p>Today is <?php date_en(); ?>.</p></div>

	<?php print_page('homepage');?>

	<?php include('footer.php');?>

	</body>
</html>
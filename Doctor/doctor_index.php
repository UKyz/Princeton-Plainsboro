<?php

session_start(); //Call to $_SESSION[]

if (!isset($_SESSION) && empty($_SESSION)) {
	// If there isn't a session, we cannot go in this page, we have to login as doctor
	echo "<script>alert(\"You cannot access to this page without log in.\")
		document.location.href = './index.php'; 
		</script>";
}
else if ($_SESSION['type'] != "doctor") {
	// If a session is already created and if it's not for a doctor you cannot access to this page
	echo "<script>alert(\"You cannot access to this page without log in.\")
		document.location.href = './index.php'; 
		</script>";
}

function print_appointment($doctor) {
	// This function is used to display appointments of the doctor

	try {
	    $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
	}
	catch (Exception $e) {
		die('Erreur : ' . $e->getMessage());
	}

	echo "<div class=\"div_print_page\">\n";

	/* OK or REFUSED */

	echo "<div>\n\t<h3 class=\"h3_center\">Already Approved or already Refused appointments</h3>\n";

	$reponse = $bdd->prepare("SELECT * FROM appointment WHERE doctor = :doctor AND state=\"OK\" OR state=\"Refused\" ORDER by day");
	$reponse->execute(array('doctor' => trim($_SESSION['name'])));

	if ($reponse->fetch() == null) {
		echo "<p>There is no refused or etablished appointments for now.</p>";
	}
	else {
		$reponse->closeCursor();
		$reponse = $bdd->prepare("SELECT * FROM appointment WHERE doctor = :doctor AND state=\"OK\" OR state=\"Refused\" ORDER by day");
		$reponse->execute(array('doctor' => trim($_SESSION['name'])));

		print_in_table($reponse);
	}

	$reponse->closeCursor();

	echo "</div>\n";

	/* Canceled */

	echo "<div>\n<h3 class=\"h3_center\">Canceled appointments</h3>";

	$reponse = $bdd->prepare("SELECT * FROM appointment WHERE doctor = :doctor AND state=\"Canceled\" ORDER by day");
	$reponse->execute(array('doctor' => trim($_SESSION['name'])));

	if ($reponse->fetch() == null) {
		echo "<p>There is no cancelled appointments for now.</p>";
	}
	else {
		$reponse->closeCursor();
		
		$reponse = $bdd->prepare("SELECT * FROM appointment WHERE doctor = :doctor AND state=\"Canceled\" ORDER by day");
		$reponse->execute(array('doctor' => trim($_SESSION['name'])));
		print_in_table($reponse);
	}

	$reponse->closeCursor();

	echo "</div>\n</div>\n";

	/* WAITING */

	echo "<h3 class=\"h3_center\">Waiting appointments</h3>";

	$reponse = $bdd->prepare("SELECT * FROM appointment WHERE doctor = :doctor AND state=\"Waiting\" ORDER by day");
	$reponse->execute(array('doctor' => trim($_SESSION['name'])));

	if ($reponse->fetch() == null) {
		echo "<p class=\"text_center\">There is no waiting appointment for now.</p>";
	}
	else {
		$reponse->closeCursor();
		$reponse = $bdd->prepare("SELECT * FROM appointment WHERE doctor = :doctor AND state=\"Waiting\" ORDER by day");
		$reponse->execute(array('doctor' => trim($_SESSION['name'])));

		print_waiting($reponse);
	}

	$reponse->closeCursor();

}

function print_in_table($table) {
	// This function display appointments information in a table

	$html = "<table class=\"tab_doctor_style\">\n";
	$html .= "\t<thead><th>Name</th><th>Reason</th><th>Date</th><th>Hour</th><th>State</th></thead>\n\t<tbody>\n";

	while ($donnees = $table->fetch()) {

		$html .= "\t\t<tr><td>" . $donnees['name'] . "</td>";
		$html .= "<td>" . $donnees['reason'] . "</td>";
		$html .= "<td>" . $donnees['day'] . "</td>";
		$html .= "<td>" . $donnees['time'] . "</td>";
		$html .= "<td>" . $donnees['state'] . "</td></tr>\n";
	}
	$html .= "\t</tbody>\n</table>\n";
	echo $html;

}

function print_waiting($table) {
	// This function display waiting appointments with a button to accept or refuse appointments

	$html = "<table class=\"tab_doctor_style\">\n";
	$html .= "\t<thead><th>Name</th><th>Reason</th><th>Date</th><th>Hour</th><th>State</th><th>Add Comment</th></thead>\n\t<tbody>\n";

	while ($donnees = $table->fetch()) {

		$html .= "\t\t<tr><form method=\"POST\" action=\"doctor_index.php\">";
		$html .= "<input type=\"hidden\" name=\"id\" value=\"" . $donnees['id'] . "\"/>";
		$html .= "<td>" . $donnees['name'] . "</td>";
		$html .= "<td>" . $donnees['reason'] . "</td>";
		$html .= "<td>" . $donnees['day'] . "</td>";
		$html .= "<td>" . $donnees['time'] . "</td>";
		$html .= "<td><input type=\"radio\" name=\"state\" value=\"OK\"/> Accept <input type=\"radio\" name=\"state\" value=\"Refused\" checked/> Refuse </td>";
		$html .= "<td><input type=\"text\" name=\"doctor_comment\" required/></td>";
		$html .= "<td><input type=\"submit\" value=\"Process\"/></td></form></tr>\n";

	}
	$html .= "\t</tbody>\n</table>\n";
	echo $html;

}

function update_waiting() {
	// This function is called when the doctor used the waiting appointment form, if he accepts or refuses.

	if (isset($_POST) && !empty($_POST)) {

		try {
	    $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
		}
		catch(Exception $e) {
		        die('Erreur : '.$e->getMessage());
		}

		$nb_modifs = $bdd->prepare('UPDATE appointment SET state = :state, doctor_comment = :doctor_comment WHERE id = :id');
		$nb_modifs->execute(array('state' => trim($_POST['state']), 'doctor_comment' => $_POST['doctor_comment'], 'id' => $_POST['id']));
		
		echo "<script>alert(\"You succesfully update the appointment.\")</script>";

	}

}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>(Doctor) General Practioner</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="./assets/css/main.css" />
	</head>
	<body>

	<?php include('./Presentation/header.php');?>

	<div class="petit_texte_en_haut"><p>Welcome back Dr. <?php echo $_SESSION['name'] ?> !</p></div>

	<?php update_waiting(); ?>

	<?php print_appointment($_SESSION['name']); ?>

	<?php include('./Presentation/footer.php');?>

	</body>
</html>
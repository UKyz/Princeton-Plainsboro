<?php 

session_start(); //Call to $_SESSION[]

if (isset($_POST) && !empty($_POST)) {
	// The variable $_POST is used to login 

	try {
	    $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
	}
	catch (Exception $e) {
		die('Erreur : ' . $e->getMessage());
	}
	// We enter an email account and the password

	$reponse = $bdd->query("SELECT * FROM private_member WHERE email = \"" . $_POST['mail'] . "\" AND password = \"" . $_POST['password'] . "\" AND type != \"Waiting\"");

	$donnees = $reponse->fetch();

	if ($donnees == null) {
		// If this is not the right email or password
		echo "<script>alert(\"Wrong email or wrong password.\")</script>";
	}
	else {
		// If the email and the password are good, we create a session 
		$_SESSION['id'] = $donnees['id'];
		$_SESSION['mail'] = $donnees['email'];
		$_SESSION['name'] = $donnees['name'];
		$_SESSION['type'] = $donnees['type'];
		$_SESSION['age'] = $donnees['age'];
	}
}

function connexion() {
	// Display a login form if there isn't a session created

	if (isset($_SESSION) and !empty($_SESSION)) {
		print_private_access();
	}
	else {

		$html = "<form method=\"POST\" action=\"log_in_private.php\" class=\"form_style\">\n\t<ul>\n";

	    $html .= "\t\t<li><input class=\"field-style field-full align-non\" type=\"email\" name=\"mail\" placeholder=\"Email\" /></li>\n";
		$html .= "\t\t<li><input class=\"field-style field-full align-non\" type=\"password\" name=\"password\" placeholder=\"•••••••\" /></li>\n";

	    $html .= "\t\t<li><input type=\"submit\" value=\"Log in\" /></li>\n";

		$html .= "\t</ul>\n</form>\n";

		echo $html;
	}
}

function print_private_access() {
	// This function is used if a session is created before clicking for this page
	// If we have a session for admin or doctor before, we are guided to the private page

	if ($_SESSION['type'] == "admin") {
		echo "<script>document.location.href = 'admin_index.php';</script>";
	}
	else if ($_SESSION['type'] == "doctor") {
		echo "<script>document.location.href = '../Doctor/doctor_index.php'; </script>";
	}
	else {
		// If there is a session but not for doctor or admin, you cannot access here
		echo "<script>alert(\"You cannot access private pages.\")
			document.location.href = '../index.php'; 
			</script>";
	}

}

?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>General Practioner</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="../assets/css/main.css" />
		<link rel="icon" type="image/png" href="../assets/Images/icon.ico" />
	</head>
	<body>

	<?php include('../Presentation/header.php');?>

	<h2 class="titles_h2">Private access</h2>

	<?php connexion();?>

	<?php include('../Presentation/footer.php');?>

	</body>
</html>
<?php 

session_start(); //Call to $_SESSION[]

function sign_up() {
	// The function is used to add a new patient account from the signup form

	if (isset($_POST) and !empty($_POST)) {
		// The POST is used by the signup form

		try {
	    $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
		}
		catch (Exception $e) {
			die('Erreur : ' . $e->getMessage());
		}
		// We look if the email given is available

		$reponse = $bdd->query("SELECT * FROM patients WHERE mail = \"" . $_POST['email'] . "\" ");
		$donnees = $reponse->fetch();

		if ($donnees != null) {
			// If it's not, we cannot add a new patient
			echo "<script>alert(\"This email is not available.\")</script>";
		}
		else if ($_POST['password'] != $_POST['password2']) {
			// If the passwords are not the same, we cannot add a new patient
			echo "<script>alert(\"The passwords are not the same.\")</script>";
		}
		else {
			// If everything is alright, we add a new patient
			add_to_DB($_POST);
			echo "<script>alert(\"You succesfully registered in our patients.\")
			document.location.href = '../Patient/appointments.php'; 
			</script>";
		}

		$reponse->closeCursor();

	}
}

function add_to_DB($table) {
	// This function is used to add a new patient in the db

	try {
	    $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
	}
	catch(Exception $e) {
	        die('Erreur : '.$e->getMessage());
	}

	$req = $bdd->prepare('INSERT INTO patients (name, mail, password, sexe, age, adress) VALUES(:name, :mail, :password, :sexe, :age, :adress)');
	$req->execute(array(
		'name' => $table['name'],
		'mail' => $table['email'],
		'password' => $table['password'],
		'sexe' => $table['sexe'],
		'age' => $table['age'],
		'adress' => $table['adress']
		));
}

?>


<!DOCTYPE HTML>
<html>
	<head>
		<title>(Patient) General Practioner</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="./assets/css/main.css" />
		<link rel="icon" type="image/png" href="../assets/Images/icon.ico" />
	</head>
	<body>

	<?php include('../Presentation/header.php');?>

	<h2 class="titles_h2">Sign up</h2>

	<?php sign_up(); ?>

	<!-- The signup form -->

    <form class="form_style" method="POST" action="../Patient/sign_up_patient.php">
		<ul>
			<li>
			    <input type="text" name="name" class="field-style field-full align-non" placeholder="Name" />
			</li>
			<li>
			    <input type="email" name="email" class="field-style field-full align-non" placeholder="Email" />
			</li>
			<li>
			    <input type="password" name="password" class="field-style field-split align-left" placeholder="Password" />
			    <input type="password" name="password2" class="field-style field-split align-right" placeholder="Confirm Password" />
			</li>
			<li>
				<div class="span_radio_1">
					<div class="span_radio_2">
			    		<span class="align-left"><input type="radio" name="sexe" value="M" checked/> Male </span>
			    		<span class="align-right"><input type="radio" name="sexe" value="F"/> Female </span>
			    	</div>
			    <input type="number" name="age" class="field-style field-split align-right" placeholder="Age" />
			    </div>
			</li>
			<li>
				<input type="text" name="adress" class="field-style field-full align-non" placeholder="Adress" />
			</li>
			<li class="button_middle">
				<div class="bouton_align">
					<span class="align-left"><input type="submit" value="Sign up" /></span>
					<span class="align-right"><a href="../Patient/appointments.php">Log in</a></span>
				</div>
			</li>
		</ul>
	</form>

	<?php include('../Presentation/footer.php');?>

	</body>
</html>
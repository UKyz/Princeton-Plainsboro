<?php 

session_start(); //Call to $_SESSION[]

function main() {

	if (isset($_GET) and !(empty($_GET))) {
		// GET is used to accept or refuse a new doctor, or delete a doctor

		if ($_GET['type'] == "doctor") {
			// If it's already a doctor, the form was used to delete the doctor

			try {
			    $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
			}
			catch(Exception $e) {
			        die('Erreur : '.$e->getMessage());
			}

			$req = $bdd->prepare("DELETE FROM private_member WHERE id = :id ");
			$req->execute(array(
			    'id' => $_GET['id']));

			echo "<script>alert(\"You succesfully delete the doctor\")</script>";
		}
		else if ($_GET['type'] == "Waiting") {
			// If it's waiting, the form was used to accept new doctor

			try {
			    $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
			}
			catch(Exception $e) {
			        die('Erreur : '.$e->getMessage());
			}

			$req = $bdd->prepare("UPDATE private_member SET type = \"doctor\" WHERE id = :id ");
			$req->execute(array(
			    'id' => $_GET['id']));

			echo "<script>alert(\"You succesfully accept a new doctor\")</script>";

		}

	}
	else if (isset($_POST) and !(empty($_POST))) {
		// POST is used to add a new doctor

		try {
		    $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
		}
		catch(Exception $e) {
		        die('Erreur : '.$e->getMessage());
		}
		// We look in the db if the email given is available 

		$reponse = $bdd->query("SELECT * FROM private_member WHERE email = \"" . $_POST['email'] . "\" ");
		$donnees = $reponse->fetch();

		if ($donnees != null) {
			// If it's not, we cannot add a new doctor
			echo "<script>alert(\"This email is not available\")</script>";
		}
		else if ($_POST['password'] != $_POST['password2']) {
			// We look if the passwords are the same, if it's not we cannot add a new doctor
			echo "<script>alert(\"The passwords are not the same.\")</script>";
		}
		else {
			// If everything is alright, we add a new doctor
			$req = $bdd->prepare('INSERT INTO private_member(type, name, email, password, speciality, image) VALUES(:type, :name, :email, :password, :speciality, :image)');
			$req->execute(array(
				'type' => 'doctor',
				'name' => $_POST['name'],
				'email' => $_POST['email'],
				'password' => $_POST['password'],
				'speciality' => $_POST['speciality'],
				'image' => $_POST['image']
				));

			echo "<script>alert(\"You succesfully add a new doctor.\")</script>";
		}

		$reponse->closeCursor();
	}
}

function print_doctor() {
	// This function is used to search all doctors, in waiting or not.

	try {
		$bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
	}
	catch (Exception $e) {
		die('Erreur : ' . $e->getMessage());
	}
	// We find all doctors in th db

	$reponse = $bdd->query("SELECT * FROM private_member WHERE type = \"doctor\"  ORDER by name");

	if (empty($reponse->fetch())) {
		// If there is zero doctor
		echo "<p>There is no doctor for now.</p>";
	}
	else {
		// If there at least one doctor, we display the list
		$reponse->closeCursor();
		$reponse = $bdd->query("SELECT * FROM private_member WHERE type = \"doctor\" OR type = \"Waiting\"  ORDER by name");

		print_list_doctor($reponse);
	}

	$reponse->closeCursor();

}

function print_list_doctor($table) {
	// This function is used to display all doctors in a table with a form to delete or accept a doctor

	$html = "<table class=\"tab_hour_style\">\n";
	$html .= "\t<thead><th></th><th>Name</th><th>Speciality</th><th>Email</th><th>Image</th></thead>\n\t<tbody>\n";

	while ($donnees = $table->fetch()) {

		$html .= "\t\t<tr><form method=\"GET\" action=\"./Doctor/manage_doctor.php\">";
		if ($donnees['type'] == "Waiting") {
			$html .= "<td class=\"red_info_left\">" . $donnees['type'] . "</td>";
		}
		else {
			$html .= "<td class=\"red_info_left\"></td>";
		}
		$html .= "<td>" . $donnees['name'] . "</td>";
		$html .= "<td>" . $donnees['speciality'] . "</td>";
		$html .= "<td>" . $donnees['email'] . "</td>";
		$html .= "<td>" . $donnees['image'] . "</td>";
		$html .= "<input type=\"hidden\" name=\"type\" value=\"" . $donnees['type'] . "\"/>";
		$html .= "<input type=\"hidden\" name=\"id\" value=\"" . $donnees['id'] . "\"/>";

		if ($donnees['type'] == "doctor") {
			$html .= "<td><input type=\"submit\" value=\"Delete\" class=\"button_like_form_style\"/></td></form></tr>\n";
		}
		else if ($donnees['type'] == "Waiting") {
			$html .= "<td><input type=\"submit\" value=\"Accept\" class=\"button_like_form_style\"/></td></form></tr>\n";
		}

	}

	$html .= "\t</tbody>\n</table>\n";
	echo $html;

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

	<?php include('./Presentation/header.php');?>

	<p class="div_print_page"><a href="./Admin/admin_index.php" class="button_log_out">Admin page</a></p>
	<p class="div_print_page"><a href="./log_out.php" class="button_log_out">Log out</a></p>

	<?php main();?>

	<!-- The add form, to add a new doctor -->

	<div>
	    <form class="form_style" method="POST" action="manage_doctor.php">
			<ul>
				<li>
				    <input type="text" name="name" class="field-style field-full align-non" placeholder="Name" />
				</li>
				<li>
				    <input type="email" name="email" class="field-style field-full align-non" placeholder="Email" />
				</li>
				<li>
				    <input type="text" name="speciality" class="field-style field-split align-left" placeholder="Speciality" />
				    <input type="text" name="image" class="field-style field-split align-right" placeholder="Photograph's Name" />
				</li>
				<li>
				    <input type="password" name="password" class="field-style field-split align-left" placeholder="Password" />
				    <input type="password" name="password2" class="field-style field-split align-right" placeholder="Confirm Password" />
				</li>
				<li>
					<input type="submit" value="Add a new doctor" />
				</li>
			</ul>
		</form>
    </div>

    <?php print_doctor();?>

	<?php include('./Presentation/footer.php');?>

	</body>
</html>
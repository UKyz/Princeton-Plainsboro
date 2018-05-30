<?php 
// This page is used to ask to be part of the system as a new doctor

session_start(); //Call to $_SESSION[]

if (isset($_SESSION['type']) and ($_SESSION['type'] == "patient" or $_SESSION['type'] == "doctor")) {
	// If a session is already created and if it's for a patient or a doctor you cannot access to this page

	echo "<script>alert(\"You are already log in. Please log out and try again.\")
			document.location.href = 'services.php'; 
			</script>"; 

}
else if (isset($_SESSION['type']) and $_SESSION['type'] == "admin") {
	// If a session is already created and if it's for an admin you cannot access to this page

	echo "<script>alert(\"To add a new doctor, please go to private page and click on \"Manage Doctor\"\")
			document.location.href = 'services.php'; 
			</script>"; 

}
else {
	// If there isn't a session 

	if (isset($_POST) and !empty($_POST)) {
		// If the signup form is completed

		if ($_POST['password'] != $_POST['password2']) {
			// We test if the passwords are the same

			echo "<script>alert(\"Your passwords are not the same.\")</script>";

		}
		else {
			// If the passwords are the same

			try {
			    $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
			}
			catch(Exception $e) {
			        die('Erreur : '.$e->getMessage());
			}
			// We search if the email given is not already in the db

			$reponse = $bdd->prepare("SELECT * FROM private_member WHERE email = ?");
			$reponse->execute(array($_POST['email']));

			if ($reponse->fetch() != null) {
				echo "<script>alert(\"Your email is already given.\")
				document.location.href = 'demands_new_doctor.php'; 
				</script>";
			}

			// We ad a new doctor in the db with the state Waiting

			$req = $bdd->prepare('INSERT INTO private_member(name, email, password, speciality, type) VALUES(:name, :email, :password, :speciality, :type)');
			$req->execute(array(
				'name' => $_POST['name'],
				'email' => $_POST['email'],
				'password' => $_POST['password'],
				'speciality' => $_POST['speciality'],
				'type' => 'Waiting'
				));

			// We find in the db, the ID of the new doctor

			$reponse = $bdd->query("SELECT * FROM private_member WHERE email = \"" . $_POST['email'] . "\" AND password = \"" . $_POST['password'] . "\" ");

			$donnee=$reponse->fetch();

			$id_doctor = $donnee['id'];

			// We check if the photo is alright and we put it in the right folder and with the good name

			$upload_image = upload('image_doctor','Images/',1048576, array('png','gif','jpg','jpeg'), $id_doctor);

			$reponse->closeCursor();
 
			if ($upload_image) {
				// If the upload of the image works, we put the name of the image in the db

				$req = $bdd->prepare("UPDATE private_member SET image = :image WHERE id = :id ");
				$req->execute(array(
					'image' => "image_doctor_" . $id_doctor . substr(strrchr($_FILES["image_doctor"]['name'],'.'),0),
			    	'id' => $id_doctor));

				echo "<script>alert(\"Thank you. Your request will be managed by the admin.\")
				document.location.href = 'index.php'; 
				</script>";
			}
			else {
				echo "<script>alert(\"There is a problem with your image. Your request will be managed by the admin.\")</script>";
			}
		}
	}
}

function upload($index, $destination, $maxsize=false, $extensions=false, $name) {

   //Test1: check with the upload
     if (!isset($_FILES[$index])) return false;
     if ($_FILES[$index]['error'] > 0) return false;
   //Test2: check size max
     if ($maxsize !== false AND $_FILES[$index]['size'] > $maxsize) return false;
   //Test3: check extension
     $ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
     if ($extensions !== false AND !in_array($ext,$extensions)) return false;
   //Name + destination
     $name = $destination . $index . "_" . $name . substr(strrchr($_FILES[$index]['name'],'.'),0);
   //Move the file
     return move_uploaded_file($_FILES[$index]['tmp_name'],$name);
}

?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>General Practioner</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="main.css" />
		<link rel="icon" type="image/png" href="Images/icon.ico" />
	</head>
	<body>

	<?php include('header.php');?>

	<h2 class="titles_h2">Job application</h2>

	<!-- New doctor form -->

	<form class="form_style" method="POST" action="demands_new_doctor.php" enctype="multipart/form-data">
		<ul>
			<li>
			    <input type="text" name="name" class="field-style field-full align-non" placeholder="Name" />
			</li>
			<li>
				<input type="email" name="email" class="field-style field-full align-non" placeholder="Email" />
			</li>
			<li>
			    <input type="text" name="speciality" class="field-style field-full align-non" placeholder="Speciality" />
			</li>
			<li>
				<input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
			    <input type="file" name="image_doctor" class="field-style field-full align-non"> <span class="span_input_file">Photograph (max 1 Mo)</span>
			</li>
			<li>
			    <input type="password" name="password" class="field-style field-split align-left" placeholder="Password" />
			    <input type="password" name="password2" class="field-style field-split align-right" placeholder="Confirm Password" />
			</li>
			<li class="button_middle">
				<input type="submit" value="Put in a request"/>
			</li>
		</ul>
	</form>

	<?php include('footer.php');?>

	</body>
</html>





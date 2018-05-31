<?php

session_start(); //Call to $_SESSION[]

if (isset($_POST) && !empty($_POST)) {

	if ($_POST['type'] == "login") {

		// The variable $_POST is for the login form

		try {
		    $db = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
		}
		catch (Exception $e) {
			die('Erreur : ' . $e->getMessage());
		}

		// We search if, in the db, there is a patient which has the mail and the password given by the form

		$reponse = $db->prepare("SELECT * FROM patients WHERE mail = :mail AND password = :password");
		$reponse->execute(array(
				'mail' => $_POST['mail'],
				'password' => $_POST['password']
				));

		$donnees = $reponse->fetch();

		if ($donnees == null) {
			// If the information are not matching with patients, we try for a doctor or an admin

			$private = $db->prepare("SELECT * FROM private_member WHERE email = :mail AND password = :password");
			$private->execute(array(
				'mail' => $_POST['mail'],
				'password' => $_POST['password']
				));

			$login = $private->fetch();

			if ($login != null) {
				// If it's matching we create a session for the private member

				$_SESSION['id'] = $login['id'];
				$_SESSION['mail'] = $login['email'];
				$_SESSION['name'] = $login['name'];
				$_SESSION['type'] = $login['type'];
				$_SESSION['age'] = $login['age'];

				if ($_SESSION['type'] == "admin") {
					echo "<script>alert(\"You succesfully log in as admin.\")
						document.location.href = './Admin/admin_index.php'; 
						</script>";
				}
				else if ($_SESSION['type'] == "doctor") {
					echo "<script>alert(\"You succesfully log in as a doctor.\")
						document.location.href = './Doctor/doctor_index.php'; 
						</script>";
				}
			}
			else {
				// If it's not matching again, then the email or the password or both are wrong

				echo "<script>alert(\"Wrong email or wrong password.\")</script>";
			}
		}
		else {
			// If it's matching with patients db, we create a session for the patient

			$_SESSION['id'] = $donnees['id'];
			$_SESSION['type'] = 'patient';
			$_SESSION['mail'] = $donnees['mail'];
			$_SESSION['name'] = $donnees['name'];
			$_SESSION['sexe'] = $donnees['sexe'];
			$_SESSION['age'] = $donnees['age'];

			echo "<script>alert(\"You succesfully log in as patient.\")</script>";
		}
	}
}
else if (isset($_GET) && !empty($_GET)) {
	// The variable $_GET is for the cancelled form

	try
    {
        $db = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

    // We update the appointment's state to cancelled and add a comment

    $db->exec("UPDATE appointment SET State = \"Canceled\", doctor_comment = \"Canceled by the patient\" WHERE id = \"" . $_GET['id'] . "\" ");

}


function connexion() {

	// This function is used to print the right message

	if (isset($_SESSION) and !empty($_SESSION) and $_SESSION['type']=='patient') {
		// If a session is already created and it's a patient, we display a message and his or her appointments

		echo "<div class=\"petit_texte_en_haut\"><p>Welcome back " . $_SESSION['name'] . " !</p></div>";

		print_take_appointment();
		echo "<div class=\"div_print_page\">\n";
			print_own_appointment();
			print_ref_can_appointment();
		echo "</div>\n";
	}
	else if (isset($_SESSION) and !empty($_SESSION)) {
		// If a session is already created and it's not a patient, we cannot go in this page.

		echo "<div class=\"petit_texte_en_haut\"><p>You cannot access to patient pages. Please <a href=\"log_out.php\">log out</a>.</p></div>";
	}
	else {
		// If there isn't a session, we display the login form with HTML code

		$html = "<form method=\"POST\" action=\"./Patient/appointments.php\" class=\"form_style\">\n\t<ul>\n";

		$html .= "\t\t<input type=\"hidden\" name=\"type\" value=\"login\"/>";

	    $html .= "\t\t<li><input class=\"field-style field-full align-non\" type=\"email\" name=\"mail\" placeholder=\"Email\" /></li>\n";
		$html .= "\t\t<li><input class=\"field-style field-full align-non\" type=\"password\" name=\"password\" placeholder=\"•••••••\" /></li>\n";

	    $html .= "\t\t<li><div class=\"bouton_align\"><span class=\"align-left\"><input type=\"submit\" value=\"Log in\" /></span><span class=\"align-right\"><a href=\"./Patient/sign_up_patient.php\">Sign up</a></span></div></li>\n";

		$html .= "\t</ul>\n</form>\n";

		echo $html;
	}

}

function print_own_appointment() {

	// This function display appointments in state of Waiting or OK, we put also a button to cancelled apointments

    try
    {
        $db = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
    }
    catch (Exception $e)
    {
        die('Error: ' . $e->getMessage());
    }

    $find = $db->prepare("SELECT * FROM appointment WHERE Name = :name AND (State=\"Waiting\" OR State=\"OK\" )");
    $find->execute(array('name' => $_SESSION['name']));

    $appointment = $find->fetch();

    if ($appointment != null) {
    	// If there is appointments for the patient in session and in state OK or Waiting

        $find->closeCursor();

        $find = $db->prepare("SELECT * FROM appointment WHERE Name = :name AND (State=\"Waiting\" OR State=\"OK\" )");
    	$find->execute(array('name' => $_SESSION['name']));

    	// We display a form with appointments and button to cancel

        $List_Appointments = "<div><table class=\"tab_hour_style\"><thead><tr><th>ID</th><th>Name</th><th>Doctor</th><th>Day</th><th>Time</th><th>State</th><th>Reason</th></tr></thead><tbody>";

        while ($appointment = $find->fetch()) {

            $List_Appointments .= "
	            <tr><form method=\"GET\">
	                <td>" . $appointment['id'] . "</td>
	                <input type=\"hidden\" name=\"id\" value=\"" . $appointment['id'] . "\"/>
	                <td>" . $appointment['name'] . "</td>
	                <td>" . $appointment['doctor'] . "</td>
		            <td>" . $appointment['day'] . "</td>
		            <td>" . $appointment['time'] . "</td>
		            <td>" . $appointment['state'] . "</td>
	                <td>" . $appointment['reason'] . "</td>
	                <td class=\"button\"><input type=\"submit\" name=\"submit\" class=\"button_like_form_style\" value=\"Cancel\"/></td>
	            </form></tr>";
        }

        $List_Appointments .= "</tbody></table></div>";
    
    $find->closeCursor();
    echo $List_Appointments;

    }
    else {
    	// If there is no appointments for the patient in session and in state OK or Waiting

    	echo "<p>You don't have any waiting appointment.</p>";
    }

}

function print_take_appointment() {

	// This function is used to display a form to take appointments

	try
    {
        $db = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
    }
    catch (Exception $e)
    {
        die('Error: ' . $e->getMessage());
    }
    $find = $db->query("SELECT * FROM private_member WHERE type = \"doctor\"");

    // We search all doctors to show to the patients, he needs to select a doctor and a day

	$Find_Doctor = "<form method=\"POST\" action=\"./Patient/appointments.php\" class=\"form_style\">\n
			\t<ul>

				\t\t<input type=\"hidden\" name=\"type\" value=\"take_appointment\"/>

                \t\t<li><div class=\"span_radio_1\">

                <select name=\"doctor\" class=\"field-style field-split align-left\">";
                    	while ($doctors = $find->fetch()) $Find_Doctor .= "<option>". $doctors['name'] ." </option>";
                    	$Find_Doctor .= "
                    	<input type=\"date\" name=\"date\" class=\"field-style field-split align-right\" placeholder=\"Choose a day : yyyy-mm-dd\" required/>
                </div></li>\n

                \t\t<li class=\"button_middle\"><input type=\"submit\" value=\"Take an appointment\"/></li>\n

            \t</ul>\n
        </form>\n";


    $find->closeCursor();
    echo $Find_Doctor;

    if (isset($_POST['type']) and $_POST['type'] == "take_appointment") {

    	if ($_POST['date'] == null) {
    		echo "<script>alert(\"Error, you have to put a date.\")
                document.location.href = './Patient/appointments.php'; 
                </script>";
    	}

		$tableau_jours = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

		try
        {
            $db = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
        }
        catch (Exception $e)
        {
            die('Error: ' . $e->getMessage());
        }
        // We display a form to show to the patient all the hours where the doctor is free

    	$Find_Appointment = "
    		<form method=\"POST\" action=\"./Patient/appointments.php\" class=\"form_style\">\n
                \t<ul>

                    \t\t<li>Choose your hour with the Dr. " . trim($_POST['doctor']) . " for the " . $_POST['date'] ." :
                    </li>\n

                    \t\t<input type=\"hidden\" name=\"type\" value=\"process_appointment\"/>

                    \t\t<input type=\"hidden\" name=\"doctor\" value=\"" . trim($_POST['doctor']) . "\"/>

                    \t\t<input type=\"hidden\" name=\"day\" value=\"" . $_POST['date'] . "\"/>

                    \t\t<li><div class=\"span_radio_3\">
                            <p>What hour ?</p>
                            <select name=\"hour\" class=\"field-style field-split align-right\">";

                            // We look for all appointments for the day and the select doctor

                            $find = $db->prepare("SELECT * FROM appointment WHERE doctor = :doctor AND day = :day AND state = \"OK\" ORDER BY time");
                            $find->execute(array('doctor' => trim($_POST['doctor']), 'day' => $_POST['date']));

                            $hour = $find->fetch();

                            // We find the open hours of the the chosen day ---

                            $tabDate = explode('-', $_POST['date']);
                            $timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
                            $jour = date('w', $timestamp);

                            $find_hour = $db->query("SELECT * FROM timetable");
                            $time_t = $find_hour->fetch();

                            $table_time = str_split($time_t[$tableau_jours[$jour]]);

                            // --------

                            if (trim($time_t[$tableau_jours[$jour]]) == "Closed") {
                                // If the day chosen is closed, we print a message
                                echo "<script>alert(\"Sorry, it's closed on the day you have chosen.\")
                                    document.location.href = './Patient/appointments.php'; 
                                    </script>";
                            }

                            // Else, we put the hours in a list, we remove all the hours where is an appointment and display in the form

                            for ($i = intval(trim($table_time[0]),0); $i < (intval(trim($table_time[6]),0) + 12) ; $i++) {

                                if ($i.":00:00" == $hour['time'] or "0".$i.":00:00" == $hour['time']) $hour = $find->fetch();
                                else {
                                    if ($i < 10) {
                                        $Find_Appointment .= "<option>0". $i .":00</option>";
                                    }
                                    else {
                                        $Find_Appointment .= "<option>". $i .":00</option>";
                                    }
                                }
                            }

                            $find->closeCursor();

                            $Find_Appointment .= "</select>\n\t\t</div></li>\n

                    \t\t<li><input type=\"text\" name=\"reason\" placeholder=\"Write the reason\" class=\"field-style field-full align-none\" required/></li>\n

                    \t\t<li class=\"button_middle\"><input type=\"submit\" value=\"Take an appointment\"/></li>\n

                \t</ul>\n
            </form>\n";

        echo $Find_Appointment;
	}
	else if (isset($_POST['type']) and $_POST['type'] == 'process_appointment') {

		try {
            $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
        }
        catch(Exception $e) {
                die('Error: '.$e->getMessage());
        }

        $insert = $bdd->prepare('INSERT INTO appointment(name, doctor, day, time, state, reason) VALUES(:name, :doctor, :day, :time, :state, :reason)');
        $insert->execute(array(
            'name' => $_SESSION['name'],
            'doctor' => $_POST['doctor'],
            'day' => $_POST['day'],
            'time' => $_POST['hour'],
            'state' => 'Waiting',
            'reason' => $_POST['reason']
            ));
        
        echo "<script>alert(\"You succesfully take an appointment, the appointment is waiting for the doctor.\")
	        document.location.href = './Patient/appointments.php'; 
	        </script>";
	}
    

}

function print_ref_can_appointment() {

	// This function is used to display appointments in state of Refused or Cancelled

	try
    {
        $bdd = new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root');
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

    $find = $bdd->prepare("SELECT * FROM appointment WHERE Name = :name AND (State=\"Refused\" OR State=\"Canceled\" )");
    $find->execute(array('name' => $_SESSION['name']));

    // We search all appointments for the patient in session in state Refused or Cancelled

    $appointment = $find->fetch();

    if ($appointment != null) {
    	// If there is appointments for the patient in session in state Refused or Cancelled

        $find->closeCursor();
        $find = $bdd->prepare("SELECT * FROM appointment WHERE Name = :name AND (State=\"Refused\" OR State=\"Canceled\" )");
    	$find->execute(array('name' => $_SESSION['name']));

    	// We display in a table the appointments

    	$List_Appointments = "<div><table class=\"tab_hour_style\"><thead><tr><th>Doctor</th><th>Day</th><th>Time</th><th>State</th><th>Reason</th><th>Comment</th></tr></thead><tbody>";

	    while ($appointment = $find->fetch()) {

	        $List_Appointments .= "
	        <tr>
	            <td>" . $appointment['doctor'] . "</td>
	            <td>" . $appointment['day'] . "</td>
	            <td>" . $appointment['time'] . "</td>
	            <td>" . $appointment['state'] . "</td>
	            <td>" . $appointment['reason'] . "</td>
	            <td>" . $appointment['doctor_comment'] . "</td>";
	    }

	    $List_Appointments = $List_Appointments . "</tbody></table></div>";

	    $find->closeCursor();
	    echo $List_Appointments;
	}

	else {
		// If there isn't appointments for the patient in session in state Refused or Cancelled

		echo "<p>You don't have any canceld or refused appointment.</p>";
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
	$html .= "\t<img src=\"./assets/Images/" . $table['photo'] . "\">";
	$html .= "</div>\n";

	return $html;

}

function print_photo_text($table) {

	// Return HTML code about an article of photo and text type

	$html = "<div class=\"div_class\">\n";
	$html .= "\t<h3>" . $table['titre'] . "</h3>\n";
	$html .= "\t<div class=\"photo_text\">\n";
	$html .= "\t\t<img src=\"./assets/Images/" . $table['photo'] . "\">";
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
	$html .= "\t\t<img src=\"./assets/Images/" . $table['photo'] . "\">";
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
		<title>Appointments</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="./assets/main.css" />
		<link rel="icon" type="image/png" href="./assets/Images/icon.ico" />
	</head>
	<body>

	<?php include('./Presentation/header.php');?>

	<h2 class="titles_h2">Appointments</h2>

	<?php
	if (!isset($_SESSION['type'])) {
		print_page('appointments');
	}
	?>

	<?php connexion();?>

	<?php include('./Presentation/footer.php');?>

	</body>
</html>
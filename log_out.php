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
		<link rel="stylesheet" href="main.css" />
		<link rel="icon" type="image/png" href="Images/icon.ico" />
	</head>
	<body>

	<?php include('header.php');?>

	<?php echo "<script>alert(\"You succesfully log out.\")
			document.location.href = 'index.php'; 
			</script>"; 
	?>

	<?php include('footer.php');?>

	</body>
</html>
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
		<link rel="stylesheet" href="assets/main.css" />
		<link rel="icon" type="image/png" href="assets/Images/icon.ico" />
	</head>
	<body>

	<?php include('Presentation/header.php');?>

	<?php echo "<script>alert(\"You succesfully log out.\")
			document.location.href = 'index.php'; 
			</script>"; 
	?>

	<?php include('Presentation/footer.php');?>

	</body>
</html>
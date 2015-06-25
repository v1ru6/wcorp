<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>

<!-- Processing the form -->

<?php  
	if (isset($_POST['submit'])) {
		// Process the form
		$menu_name = mysqli_prep($_POST["menu_name"]);
		$position = (int) $_POST["position"];
		$visible = (int) $_POST["visible"];

		// Escape all strings
		$menu_name = mysqli_real_escape_string($connection, $menu_name);

		// Validations
		$required_fields = array("menu_name", "position", "visible");
		validate_presences($required_fields);

		$fields_with_max_lengths = array("menu_name" => 30);
		validate_max_lengths($fields_with_max_lengths);

		if (!empty($errors)) {
			$_SESSION["errors"] = $errors;
			redirect_to("new_subject.php");
		} 
		// Perform DB query
		$query = "INSERT INTO mysqlsubjects (menu_name, position, visible)
				  VALUES ('{$menu_name}', {$position}, {$visible})";
		$result = mysqli_query($connection, $query);

		if ($result) {
			$_SESSION["message"] = "Subject created!";
			redirect_to("manage_content.php");
		} else {
			$_SESSION["message"] = "Subject creation failed!";
			redirect_to("new_subject.php");
		}
	}
?>

<?php if (isset($connection)) { mysqli_close($connection); } ?>
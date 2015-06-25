<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php 
	$current_subject = find_subject_by_id($_GET["subject"]); 
	if (!$current_subject) { // find the subject, make sure that it's there
		redirect_to("manage_content.php");
	}

	$pages_set = find_pages_for_subject($current_subject["id"]);
	if (mysqli_num_rows($pages_set) > 0) {
		$_SESSION["message"] = "ERROR: You cannot delete a subject that is still connected to pages!";
		redirect_to("manage_content.php?subject={$current_subject["id"]}");
	}

	$id = $current_subject["id"];
	$query = "DELETE FROM mysqlsubjects
			  WHERE id = {$id}
			  LIMIT 1";
	$result = mysqli_query($connection, $query);

	if ($result && mysqli_affected_rows($connection) == 1) {
		$_SESSION["message"] = "Subject deleted!";
		redirect_to("manage_content.php");
	} else {
		$_SESSION["message"] = "Subject deletion failed!";
		redirect_to("manage_content.php?subject={$id}");
	}
?>
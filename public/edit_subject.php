<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php find_selected_page(); ?>
<?php  
	if (!$current_subject) {
		redirect_to("manage_content.php");
	}
?>

<!-- Form processing -->
<?php  
	if (isset($_POST['submit'])) {
		// Validations
		$required_fields = array("menu_name", "position", "visible");
		validate_presences($required_fields);

		$fields_with_max_lengths = array("menu_name" => 30);
		validate_max_lengths($fields_with_max_lengths);

		if (empty($errors)) {

			// Process the form
			$id = $current_subject["id"];
			$menu_name = mysqli_prep($_POST["menu_name"]);
			$position = (int) $_POST["position"];
			$visible = (int) $_POST["visible"];

			// Escape all strings
			$menu_name = mysqli_real_escape_string($connection, $menu_name);

			// Perform DB query
			$query = "UPDATE mysqlsubjects
					  SET menu_name = '{$menu_name}', 
					      position = {$position}, 
					      visible = {$visible}
					  WHERE id = {$id}
					  LIMIT 1";
			$result = mysqli_query($connection, $query);

			if ($result && mysqli_affected_rows($connection) == 1) {
				$_SESSION["message"] = "Editted successfully!";
				redirect_to("manage_content.php");
			} else {
				$message = "Edit failed!";
			}
		} 
	} else {
		// This is prolly a GET request
	}
?>
<!-- End of form processing -->
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>

<div id="main">
	<div id="nav">
		<?php echo navigation($current_subject, $current_page); ?>
	</div>
	<div id="page">
		<?php 
			if (!empty($message)) {
				echo "<div class=\"message\">" . htmlentities($message) . "</div>";
			} 
		?>
		<?php echo form_errors($errors); ?>
		<h2>Edit Subject: <?php echo htmlentities($current_subject["menu_name"]); ?></h2>
		<form action="edit_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>" method="post">
			<p>Menu name:
				<input type="text" name="menu_name" value="<?php echo $current_subject["menu_name"]; ?>"/>
			</p>
			<p>Position:
				<select name="position" id="">
					<?php 
						$subject_set = find_subjects(false);
						$subject_count = mysqli_num_rows($subject_set);
						for ($count=1; $count <= $subject_count; $count++) { 
							echo "<option value=\"{$count}\"";
							if ($current_subject["position"] == $count) {
								echo " selected";
							}
							echo ">{$count}</option>";
						}
					?>
				</select>
			</p>
			<p>Visible:
				<input type="radio" name="visible" value="0" <?php if ($current_subject["visible"] == 0) { echo "checked"; } ?>/>No
				&nbsp;
				<input type="radio" name="visible" value="1" <?php if ($current_subject["visible"] == 1) { echo "checked"; } ?>/>Yes
			</p>
			<input type="submit" value="Edit Subject" name="submit"/>
		</form>
		<br/>
		<a href="manage_content.php">Cancel</a>
		&nbsp; 
		&nbsp;
		<a href="delete_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>" onclick="return confirm('Are you sure?');">Delete subject</a>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>
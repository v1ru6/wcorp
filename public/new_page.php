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
		$required_fields = array("menu_name", "position", "visible", "content");
		validate_presences($required_fields);

		$fields_with_max_lengths = array("menu_name" => 30);
		validate_max_lengths($fields_with_max_lengths);

		if (empty($errors)) {

			// Process the form
			$subject_id = $current_subject["id"];
			$menu_name = mysqli_prep($_POST["menu_name"]);
			$position = (int) $_POST["position"];
			$visible = (int) $_POST["visible"];
			$content = mysqli_prep($_POST["content"]);

			// Escape all strings
			$menu_name = mysqli_real_escape_string($connection, $menu_name);

			// Perform DB query
			$query = "INSERT INTO pages (subject_id, menu_name, position, visible, content)
					  VALUES ({$subject_id}, '{$menu_name}', {$position}, {$visible}, '{$content}')";
			$result = mysqli_query($connection, $query);

			if ($result) {
				$_SESSION["message"] = "Page created!";
				redirect_to("manage_content.php");
			} else {
				$_SESSION["message"] = "Page creation failed!";
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
		<?php echo message(); ?>
		<?php echo form_errors($errors); ?>

		<h2>Create Page</h2>
		<form action="new_page.php?subject=<?php echo urlencode($current_subject["id"]); ?>" method="post">
			<p>Menu name:
				<input type="text" name="menu_name" value="">
			</p>
			<p>Position
				<select name="position">
					<?php  
						$page_set = find_pages_for_subject($current_subject["id"]);
						$page_count = mysqli_num_rows($page_set);
						for ($count=1; $count <= ($page_count + 1); $count++) { 
							echo "<option value=\"{$count}\">{$count}</option>";
						}
					?>
				</select>
			</p>
			<p>Visible:
				<input type="radio" name="visible" value="0">No
				&nbsp;
				<input type="radio" name="visible" value="1">Yes
			</p>
			<p>Content<br/>	
				<textarea name="content" cols="80" rows="20"></textarea>
			</p>
			<input type="submit" name="submit" value="Create Page">
		</form>
		<br/>
		<a href="manage_content.php?subject=<?php echo urlencode($current_subject["id"]); ?>">Cancel</a>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>
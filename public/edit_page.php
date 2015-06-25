<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php find_selected_page(); ?>
<?php  
	if (!$current_page) {
		redirect_to("manage_content.php");
	}
?>

<!-- Form processing -->
<?php  
	if (isset($_POST['submit'])) {
		// Process the form
		$id = $current_page["id"];
		$menu_name = mysqli_prep($_POST["menu_name"]);
		$position = (int) $_POST["position"];
		$visible = (int) $_POST["visible"];
		$content = mysqli_prep($_POST["content"]);

		// Escape all strings
		$menu_name = mysqli_real_escape_string($connection, $menu_name);

		// Validations
		$required_fields = array("menu_name", "position", "visible", "content");
		validate_presences($required_fields);

		$fields_with_max_lengths = array("menu_name" => 30);
		validate_max_lengths($fields_with_max_lengths);
		// print_r($errors);
		if (empty($errors)) {

			// Perform DB query
			$query = "UPDATE pages
					  SET menu_name = '{$menu_name}', 
					      position = {$position}, 
					      visible = {$visible},
					      content = '{$content}'
					  WHERE id = {$id}
					  LIMIT 1";
			$result = mysqli_query($connection, $query);

			if ($result && mysqli_affected_rows($connection) == 1) {
				$_SESSION["message"] = "Editted successfully!";
				redirect_to("manage_content.php");
			} else {
				$_SESSION["message"] = "Edit failed!";
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
		<h2>Edit Page: <?php echo htmlentities($current_page["menu_name"]); ?></h2>
		<form action="edit_page.php?page=<?php echo urlencode($current_page["id"]); ?>" method="post">
			<p>Menu name:
				<input type="text" name="menu_name" value="<?php echo $current_page["menu_name"]; ?>"/>
			</p>
			<p>Position:
				<select name="position" id="">
					<?php 
						$page_set = find_pages_for_subject($current_page["subject_id"]);
						$page_count = mysqli_num_rows($page_set);
						for ($count=1; $count <= $page_count; $count++) { 
							echo "<option value=\"{$count}\"";
							if ($current_page["position"] == $count) {
								echo " selected";
							}
							echo ">{$count}</option>";
						}
					?>
				</select>
			</p>
			<p>Visible:
				<input type="radio" name="visible" value="0" <?php if ($current_page["visible"] == 0) { echo "checked"; } ?>/>No
				&nbsp;
				<input type="radio" name="visible" value="1" <?php if ($current_page["visible"] == 1) { echo "checked"; } ?>/>Yes
			</p>
			<p>Content:<br/>
				<textarea name="content" cols="80" rows="20"><?php echo htmlentities($current_page["content"]); ?></textarea>
			</p>
			<input type="submit" value="Edit Page" name="submit"/>
		</form>
		<br/>
		<a href="manage_content.php">Cancel</a>
		&nbsp; 
		&nbsp;
		<a href="delete_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>" onclick="return confirm('Are you sure?');">Delete subject</a>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>
<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php $layout_context = "public"; ?>
<?php include("../includes/layouts/header.php"); ?>
<?php find_selected_page(); ?>

<div id="main">
	<div id="nav">
		<?php echo public_navigation($current_subject, $current_page); ?>
	</div>
	<div id="page">
		<?php if ($current_subject) { ?>
			<h2>Manage Subject</h2>
			Menu Name: <?php echo htmlentities($current_subject["menu_name"]); ?><br/>
		<?php } elseif ($current_page) { ?>
			<?php echo htmlentities($current_page["content"]); ?>
		<?php } else { ?>
			</p>Please select a page subject.</p>
		<?php } ?>
	</div>
</div>
<?php include("../includes/layouts/footer.php"); ?>
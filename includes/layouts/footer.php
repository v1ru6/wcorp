	<div id="footer">&copy; <?php echo date("Y"); ?>, attitudesign</div>
</body>
</html>
<?php  
	if (isset($connection)) {
		mysqli_close($connection);
	}
?>
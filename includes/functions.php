<?php  
	function redirect_to($new_location) {
		header("Location: " . $new_location);
		exit;
	}

	function mysqli_prep($string) {
		global $connection;
		$escaped_string = mysqli_real_escape_string($connection, $string);
		return $escaped_string;
	}

	function confirm_query($result_set) {
		if(!$result_set) { // if we get no result
			die("Database query failed.");
		}
	}

	function form_errors($errors=array()) {
		$output = "";
		if (!empty($errors)) {
			$output .= "<div class=\"error\">";
			$output .= "Please fix the following errors:";
			$output .= "<ul>";
			foreach ($errors as $key => $error) {
				$output .= "<li>";
				$output .= htmlentities($error);
				$output .= "</li>";
			}
			$output .= "</ul>";
			$output .= "</div>";
		}
		return $output;
	}

	function find_subjects($public=true) {
		global $connection;
		$query = "SELECT * ";
		$query .= "FROM mysqlsubjects ";
		if ($public) {
			$query .= "WHERE visible = 1 ";
		}
		$query .= "ORDER BY position ASC";
		$subject_set = mysqli_query($connection, $query);
		confirm_query($subject_set);
		return $subject_set;
	}

	function find_pages_for_subject($subject_id, $public=true) {
		global $connection;
		$safe_subject_id = mysqli_real_escape_string($connection, $subject_id);
		$query = "SELECT * ";
		$query .= "FROM pages ";
		$query .= "WHERE subject_id = {$safe_subject_id} ";
		if ($public) {
		$query .= "AND visible = 1 ";
		}
		$query .= "ORDER BY position ASC";
		$page_set = mysqli_query($connection, $query); 
		confirm_query($page_set); // the functions that confirms that the connection was succesful
		return $page_set;
	}

	function navigation($subject_array, $page_array) {
		$output = "<ul class=\"subjects\">";
		$subject_set = find_subjects(false);
		while($subject = mysqli_fetch_assoc($subject_set)) {  // output data from each row through the while loop
			$output .= "<li"; 
			if ($subject_array && $subject["id"] === $subject_array["id"]) {
				$output .= " class=\"selected\"";
			 }
			$output .= ">"; 
			$output .= "<a href=\"manage_content.php?subject=";
			$output .= urlencode($subject["id"]); 
			$output .= "\">";
			$output .= htmlentities($subject["menu_name"]); 
			$output .= "</a>";	

			$page_set = find_pages_for_subject($subject["id"], false);
			$output .= "<ul class=\"pages\">";
			while($page = mysqli_fetch_assoc($page_set)) {
		 		$output .= "<li"; 
				if ($page_array && $page["id"] === $page_array["id"]) {
				  	$output .= " class=\"selected\"";
				}
				$output .= ">"; 
				$output .= "<a href=\"manage_content.php?page=";
				$output .= urlencode($page["id"]); 
				$output .= "\">";
				$output .= htmlentities($page["menu_name"]); 
				$output .= "</a>";
				$output .= "</li>";
			} 
			mysqli_free_result($page_set);
			$output .= "</ul>";
			$output .= "</li>";
		} 
		mysqli_free_result($subject_set); 
		$output .= "</ul>";
		return $output;
	}

	function public_navigation($subject_array, $page_array) {
		$output = "<ul class=\"subjects\">";
		$subject_set = find_subjects();
		while($subject = mysqli_fetch_assoc($subject_set)) {  // output data from each row through the while loop
			$output .= "<li"; 
			if ($subject_array && $subject["id"] === $subject_array["id"]) {
				$output .= " class=\"selected\"";
			 }
			$output .= ">"; 
			$output .= "<a href=\"index.php?subject=";
			$output .= urlencode($subject["id"]); 
			$output .= "\">";
			$output .= htmlentities($subject["menu_name"]); 
			$output .= "</a>";	
			if ($subject_array["id"] == $subject["id"] || $page_array["subject_id"] == $subject[
			"id"]) {
				$page_set = find_pages_for_subject($subject["id"]);
				$output .= "<ul class=\"pages\">";
				while($page = mysqli_fetch_assoc($page_set)) {
			 		$output .= "<li"; 
					if ($page_array && $page["id"] === $page_array["id"]) {
					  	$output .= " class=\"selected\"";
					}
					$output .= ">"; 
					$output .= "<a href=\"index.php?page=";
					$output .= urlencode($page["id"]); 
					$output .= "\">";
					$output .= htmlentities($page["menu_name"]); 
					$output .= "</a>";
					$output .= "</li>";
				}
				$output .= "</ul>"; 
				mysqli_free_result($page_set);
			}
			$output .= "</li>";
		} 
		mysqli_free_result($subject_set); 
		$output .= "</ul>";
		return $output;
	}

	function find_selected_page() {
		global $current_subject; // creating them in the global scope
		global $current_page; // creating them in the global scope
		if (isset($_GET["subject"])) {
		$current_subject = find_subject_by_id($_GET["subject"]);
		$current_page = null;
		} elseif (isset($_GET["page"])) {
			$current_page = find_page_by_id($_GET["page"]);
			$current_subject = null;
		} else {
			$current_subject = null;
			$current_page = null;
		}
	}

	function find_subject_by_id($subject_id) {
		global $connection;
		$safe_subject_id = mysqli_real_escape_string($connection, $subject_id);
		$query = "SELECT * FROM mysqlsubjects
				  WHERE id = {$safe_subject_id}
				  LIMIT 1";
		$subject_set = mysqli_query($connection, $query);
		confirm_query($subject_set);
		if($subject = mysqli_fetch_assoc($subject_set)) {
			return $subject;
		} else {
			return null;
		}	
	}

	function find_page_by_id($page_id) { // every time you read a single subject form the database you can use this function just replace page_id with what you need
		global $connection;
		$safe_page_id = mysqli_real_escape_string($connection, $page_id);
		$query = "SELECT * FROM pages
				  WHERE id = {$safe_page_id}
				  LIMIT 1";
		$page_set = mysqli_query($connection, $query);
		confirm_query($page_set);
		if ($page = mysqli_fetch_assoc($page_set)) {
			return $page;
		} else {
			return null;
		}
	}

?>
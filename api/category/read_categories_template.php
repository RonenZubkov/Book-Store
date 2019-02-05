<div class='container row'>
	<!-- search category function -->
	<form role="search" action='search_categories.php'>
		<div class="input-group col-md-3 pull-left">
			<input type="text" class="form-control" placeholder="Type category name..." name="s" id="srch-term" required <?php echo isset($search_term) ? "value='$search_term'" : ""; ?> />
			<div class="input-group-btn">
				<button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-search"></i></button>
			</div>
		</div>
	</form>

	<!-- create category button -->
	<a href='create_category.php' class="btn btn-primary pull-right margin-bottom-1em">
		<span class="glyphicon glyphicon-plus"></span> Create Category
	</a>
</div>
<?php
// if number of categories returned is greater than zero
if($num>0){
	
	echo "<table class='table table-hover table-responsive table-bordered'>";
	
		// table headings
		echo "<tr>";
			echo "<th>Name</th>";
			echo "<th>Description</th>";
			echo "<th>Actions</th>";
		echo "</tr>";
		
		// loop through the categories and show details
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		
			extract($row);
			
			echo "<tr>";
				echo "<td>{$name}</td>";
				echo "<td>{$description}</td>";
				echo "<td>";
					echo "<a href='category.php?id={$id}' class='btn btn-primary margin-right-1em'>";
						echo "<span class='glyphicon glyphicon-list'></span> View Products";
					echo "</a>";
					
					// edit category button
					echo "<a href='update_category.php?id={$id}' class='btn btn-info margin-right-1em'>";
						echo "<span class='glyphicon glyphicon-edit'></span> Edit";
					echo "</a>";
					
					// delete category button
					echo "<a delete-id='{$id}' delete-file='delete_category.php' class='btn btn-danger delete-object'>";
						echo "<span class='glyphicon glyphicon-remove'></span> Delete";
					echo "</a>";
				echo "</td>";
				
			echo "</tr>";
			
		}
		
	echo "</table>";
	
	// pagination, identify $page_dom and $total_rows
	// $page_dom is the page where pagination was used
	// $total_rows is the number of rows retrieved on that page
	$total_rows=0;
	
	if($page_url=='read_categories.php?'){
		$total_rows=$category->countAll();
	}
	
	else if($page_url=="search_categories.php?s={$search_term}&"){
		$total_rows=$category->countAll_WithSearch($search_term);
	}
	
	// actual paging buttons
	include_once 'paging.php';

}

// tell the user if there's no categories in the database
else{
	echo "<div class='alert alert-danger'>";
		echo "<strong>No categories found</strong>";
	echo "</div>";
}
?>
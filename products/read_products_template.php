<div class='container row margin-bottom-1em'>

	<form role="search" action='search_products.php'>
		<div class="input-group col-md-3 pull-left margin-right-1em">
			<input type="text" class="form-control" placeholder="Type product name or description..." name="s" id="srch-term" required <?php echo isset($search_term) ? "value='$search_term'" : ""; ?> />
			<div class="input-group-btn">
				<button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-search"></i></button>
			</div>
		</div>
	</form>

	<form role="search" action='search_products_by_date_range.php'>
		<div class="input-group col-md-3 pull-left">
			<input type="text" class="form-control" placeholder="Date from..." name="date_from" id="date-from" required
				<?php echo isset($date_from) ? "value='$date_from'" : ""; ?> />
			<span class="input-group-btn" style="width:0px;"></span>

			<input type="text" class="form-control" placeholder="Date to..." name="date_to" id="date-to"
				required <?php echo isset($date_to) ? "value='$date_to'" : ""; ?> />
			<div class="input-group-btn">
				<button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-search"></i></button>
			</div>
		</div>
	</form>

	<!-- create product button -->
	<a href='create_product.php' class="btn btn-primary pull-right margin-bottom-1em">
		<span class="glyphicon glyphicon-plus"></span> Create Product
	</a>

	<!-- export products to CSV -->
	<a href='export_csv.php' class="btn btn-info pull-right margin-right-1em">
		<span class="glyphicon glyphicon-download-alt"></span> Export CSV
	</a>

	<!-- delete selected records -->
	<button class="btn btn-danger pull-right margin-bottom-1em margin-right-1em" id="delete-selected">
		<span class="glyphicon glyphicon-remove-circle"></span> Delete Selected
	</button>

</div>
<?php
// display the products if there are any
if($num>0){

	// order opposite of the current order
	$reverse_order=isset($order) && $order=="asc" ? "desc" : "asc";

	// field name
	$field=isset($field) ? $field : "";

	// field sorting arrow
	$field_sort_html="";

	if(isset($field_sort) && $field_sort==true){
		$field_sort_html.="<span class='badge'>";
			$field_sort_html.=$order=="asc"
					? "<span class='glyphicon glyphicon-arrow-up'></span>"
					: "<span class='glyphicon glyphicon-arrow-down'></span>";
		$field_sort_html.="</span>";
	}

	echo "<table class='table table-hover table-responsive table-bordered'>";
		echo "<tr>";
			echo "<th class='text-align-center'><input type='checkbox' id='checker' /></th>";
			echo "<th style='width:20%;'>";
				echo "<a href='read_products_sorted_by_fields.php?field=name&order={$reverse_order}'>";
					echo "Name ";
					echo $field=="name" ? $field_sort_html : "";
				echo "</a>";
			echo "</th>";
            echo "<th>";
				echo "<a href='read_products_sorted_by_fields.php?field=price&order={$reverse_order}'>";
					echo "Price ";
					echo $field=="price" ? $field_sort_html : "";
				echo "</a>";
			echo "</th>";
			echo "<th>";
				echo "<a href='read_products_sorted_by_fields.php?field=category_name&order={$reverse_order}'>";
					echo "Category ";
					echo $field=="category_name" ? $field_sort_html : "";
				echo "</a>";
			echo "</th>";
			echo "<th>";
				echo "<a href='read_products_sorted_by_fields.php?field=created&order={$reverse_order}'>";
					echo "Created ";
					echo $field=="created" ? $field_sort_html : "";
				echo "</a>";
			echo "</th>";
			echo "<th>Actions</th>";
		echo "</tr>";

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

			extract($row);

			echo "<tr>";
				echo "<td class='text-align-center'><input type='checkbox' name='item[]' class='checkboxes' value='{$id}' /></td>";
				echo "<td>{$name}</td>";
				echo "<td>&#36;" . number_format($price, 2) . "</td>";
				echo "<td>{$category_name}</td>";
				echo "<td>{$created}</td>";
				echo "<td>";

					// read product button
					echo "<a href='read_one_product.php?id={$id}' class='btn btn-primary right-margin'>";
						echo "<span class='glyphicon glyphicon-eye-open'></span> Read";
					echo "</a>";

					// edit product button
					echo "<a href='update_product.php?id={$id}' class='btn btn-info right-margin'>";
						echo "<span class='glyphicon glyphicon-edit'></span> Edit";
					echo "</a>";

					// delete product button
					echo "<a delete-id='{$id}' delete-file='delete_product.php' class='btn btn-danger delete-object'>";
						echo "<span class='glyphicon glyphicon-remove'></span> Delete";
					echo "</a>";

				echo "</td>";

			echo "</tr>";

		}

	echo "</table>";

	// needed for paging
	$total_rows=0;

	if($page_url=="index.php?"){
		$total_rows=$product->countAll();
	}

	else if(isset($category_id) && $page_url=="category.php?id={$category_id}&"){
		$total_rows=$product->countAll_ByCategory();
	}

	else if(isset($search_term) && $page_url=="search_products.php?s={$search_term}&"){
		$total_rows=$product->countAll_BySearch($search_term);
	}

	else if(isset($field) && isset($order) && $page_url=="read_products_sorted_by_fields.php?field={$field}&order={$order}&"){
		$total_rows=$product->countAll();
	}

	// search by date range
	else if(isset($date_from) && isset($date_to)
				&& $page_url=="search_products_by_date_range.php?date_from={$date_from}&date_to={$date_to}&"){
		$total_rows=$product->countSearchByDateRange($date_from, $date_to);
	}

	// paging buttons
	include_once '../router/paging.php';
}

// tell the user there are no products
else{
	echo "<div class=\"alert alert-danger alert-dismissable\">";
		echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
		echo "No products found.";
	echo "</div>";
}
?>

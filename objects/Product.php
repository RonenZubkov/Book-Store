<?php
class Product{

	// database connection and table name
	private $conn;
	private $table_name = "books";

	// object properties
	public $id;
	public $name;
	public $price;
	public $description;
	public $image;
	public $category_id;
	public $category_name;
	public $timestamp;

	public function __construct($db){
		$this->conn = $db;
	}

	// will upload image file to server
	function uploadPhoto(){

	    $result_message="";

	    // now, if image is not empty, try to upload the image
		if(!empty($_FILES["image"]["tmp_name"])){

	        // sha1_file() function is used to make a unique file name
	        $target_directory = "uploads/";
	        $target_file = $target_directory . $this->image;
	        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

	        // error message is empty
	        $file_upload_error_messages="";

			// make sure that file is a real image
			$check = getimagesize($_FILES["image"]["tmp_name"]);
			if($check!==false){
			   // submitted file is an image
			}else{
			   $file_upload_error_messages.="<div>Submitted file is not an image.</div>";
			}

			// make sure certain file types are allowed
			$allowed_file_types=array("jpg", "jpeg", "png", "gif");
			if(!in_array($file_type, $allowed_file_types)){
			   $file_upload_error_messages.="<div>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
			}

			// make sure file does not exist
			if(file_exists($target_file)){
			   $file_upload_error_messages.="<div>Image already exists. Try to change file name.</div>";
			}

			// make sure submitted file is not too large, can't be larger than 1 MB
			if($_FILES['image']['size'] > (1024000)){
			   $file_upload_error_messages.="<div>Image must be less than 1 MB in size.</div>";
			}

			// make sure the 'uploads' folder exists
			// if not, create it
			if(!is_dir($target_directory)){
			   mkdir($target_directory, 0777, true);
			}

			// if $file_upload_error_messages is still empty
			if(empty($file_upload_error_messages)){
			    // it means there are no errors, so try to upload the file
			    if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
			        // it means photo was uploaded
			    }else{
			        $result_message.="<div class='alert alert-danger'>";
			            $result_message.="<div>Unable to upload photo.</div>";
			            $result_message.="<div>Update the record to upload photo.</div>";
			        $result_message.="</div>";
			    }
			}

			// if $file_upload_error_messages is NOT empty
			else{
			    // it means there are some errors, so show them to user
			    $result_message.="<div class='alert alert-danger'>";
			        $result_message.="{$file_upload_error_messages}";
			        $result_message.="<div>Update the record to upload photo.</div>";
			    $result_message.="</div>";
			}
	    }

	    return $result_message;
	}


	// read products by search term
	public function search($search_term, $from_record_num, $records_per_page){

		// select query
		$query = "SELECT
					c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
				FROM
					" . $this->table_name . " p
					LEFT JOIN
						categories c
							ON p.category_id = c.id
				WHERE
					p.name LIKE ? OR p.description LIKE ?
				ORDER BY
					p.name ASC
				LIMIT
					?, ?";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind variable values
		$search_term = "%{$search_term}%";
		$stmt->bindParam(1, $search_term);
		$stmt->bindParam(2, $search_term);
		$stmt->bindParam(3, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(4, $records_per_page, PDO::PARAM_INT);

		// execute query
		$stmt->execute();

		// return values from database
		return $stmt;
	}


	// create product
	public function create(){

		// to get time-stamp for 'created' field
		$this->getTimestamp();

		//write query
		$query = "INSERT INTO " . $this->table_name . "
				SET name=:name, price=:price, description=:description,
					image=:image, category_id=:category_id, created=:created";

		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->name=htmlspecialchars(strip_tags($this->name));
		$this->price=htmlspecialchars(strip_tags($this->price));
		$this->description=htmlspecialchars(strip_tags($this->description));
		$this->image=htmlspecialchars(strip_tags($this->image));
		$this->category_id=htmlspecialchars(strip_tags($this->category_id));
		$this->timestamp=htmlspecialchars(strip_tags($this->timestamp));

		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":price", $this->price);
		$stmt->bindParam(":description", $this->description);
		$stmt->bindParam(":image", $this->image);
		$stmt->bindParam(":category_id", $this->category_id);
		$stmt->bindParam(":created", $this->timestamp);

		if($stmt->execute()){
			return true;
		}

		return false;
	}

	// read products with field sorting
	public function readAll_WithSorting($from_record_num, $records_per_page, $field, $order){

		$query = "SELECT p.id, p.name, p.description, p.price, c.name as category_name, p.created
					FROM books p
						LEFT JOIN categories c
							ON p.category_id=c.id
					ORDER BY {$field} {$order}
					LIMIT :from_record_num, :records_per_page";

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(":from_record_num", $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(":records_per_page", $records_per_page, PDO::PARAM_INT);
		$stmt->execute();

		// return values from database
		return $stmt;
	}

	// read products
	public function readAll($from_record_num, $records_per_page){

		// select query
		$query = "SELECT
					c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
				FROM
					" . $this->table_name . " p
					LEFT JOIN
						categories c
							ON p.category_id = c.id
				ORDER BY
					p.created DESC
				LIMIT
					?, ?";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind variable values
		$stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

		// execute query
		$stmt->execute();

		// return values from database
		return $stmt;
	}


	// used for paging products
	public function countAll(){
		$query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row['total_rows'];
	}

	// used when filling up the update product form
	public function readOne(){

		$query = "SELECT books.name, books.price, books.description, books.image, books.category_id, categories.name as categories_name
				FROM
					" . $this->table_name . " books
						LEFT JOIN categories categories
							ON books.category_id=categories.id
				WHERE books.id = ?
				LIMIT 0,1";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$this->name = $row['name'];
		$this->price = $row['price'];
		$this->description = $row['description'];
		$this->image = $row['image'];
		$this->category_id = $row['category_id'];
		$this->category_name = $row['categories_name'];
	}

	// update the product
	public function update(){

		$query="UPDATE " . $this->table_name . "
				SET name=:name,
					price=:price,
					description=:description,
					image=:image,
					category_id=:category_id
				WHERE id=:id";

		$stmt = $this->conn->prepare($query);

		$stmt->bindParam(':name', $this->name);
		$stmt->bindParam(':price', $this->price);
		$stmt->bindParam(':description', $this->description);
		$stmt->bindParam(':image', $this->image);
		$stmt->bindParam(':category_id', $this->category_id);
		$stmt->bindParam(':id', $this->id);

		// execute the query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	// delete the product
	public function delete(){

		$query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->id=htmlspecialchars(strip_tags($this->id));

		$stmt->bindParam(1, $this->id);

		if($result = $stmt->execute()){
			return true;
		}else{
			return false;
		}
	}

	// used for the 'created' field when creating a product
	public function getTimestamp(){
		date_default_timezone_set('Asia/Jerusalem');
		$this->timestamp = date('Y-m-d H:i:s');
	}
}

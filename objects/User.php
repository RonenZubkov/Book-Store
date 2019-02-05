<?php

// 'User' object
class User{

    // database connection and table name
    private $conn;
    private $table_name = "users";

    // object properties
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $contact_number;
    public $address;
    public $password;
    public $access_level;
    public $access_code;
    public $status;
    public $created;
    public $modified;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    //Checking if email exists method
    // check if given email exist in the database
    function emailExists(){

        // query to check if email exists
        $query = "SELECT id, first_name, last_name, password
            FROM " . $this->table_name . "
            WHERE email = ?
            LIMIT 0,1";

        // prepare the query
        $stmt = $this->conn->prepare( $query );

        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));

        // bind given email value
        $stmt->bindParam(1, $this->email);

        // execute the query
        $stmt->execute();

        // get number of rows
        $num = $stmt->rowCount();

        // if email exists, assign values to object properties for easy access and use for php sessions
        if($num>0){

            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // assign values to object properties
            $this->id = $row['id'];
            $this->firstname = $row['first_name'];
            $this->lastname = $row['last_name'];
            $this->password = $row['password'];

            // return true because email exists in the database
            return true;
        }

        // return false if email does not exist in the database
        return false;
    }

// update() method will be here
    // create new user record
    function create(){

        // to get time stamp for 'created' field
        $this->created=date('Y-m-d H:i:s');
        $this->status='';

        // insert query
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                firstname = :first_name,
                lastname = :last_name,
                email = :email,
                contact_number = :contact_number,
                address = :address,
                password = :password,
                access_level = :access_level,
                status = :status,
//              created = :created";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->contact_number=htmlspecialchars(strip_tags($this->contact_number));
        $this->address=htmlspecialchars(strip_tags($this->address));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->access_level=htmlspecialchars(strip_tags($this->access_level));
        $this->status=htmlspecialchars(strip_tags($this->status));

        // bind the values
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':contact_number', $this->contact_number);
        $stmt->bindParam(':address', $this->address);

        // hash the password before saving to database
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);

        $stmt->bindParam(':access_level', $this->access_level);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':created', $this->created);

        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }else{
            $this->showError($stmt);
            return false;
        }

    }
//     id = :id"
    // update a user record
    public function update(){

        // if password needs to be updated
        $password_set=!empty($this->password) ? ", password = :password" : "";

        // if no posted password, do not update the password
        $query = "UPDATE " . $this->table_name . "
            SET
                firstname = :firstname,
                lastname = :lastname,
                email = :email
                {$password_set}
            WHERE id = :id";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));

        // bind the values from the form
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);

        // hash the password before saving to database
        if(!empty($this->password)){
            $this->password=htmlspecialchars(strip_tags($this->password));
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        }

        // unique ID of record to be edited
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;
    }

    // read all user records
    function readAll($from_record_num, $records_per_page){

        // query to read all user records, with limit clause for pagination
        $query = "SELECT
                `id`,
                `first_name`,
                `last_name`,
                email,
                contact_number,
                access_level,
                created
            FROM " . $this->table_name . "
            ORDER BY id DESC
            LIMIT ?, ?";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // bind limit clause variables
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }

    // used for paging users
    public function countAll(){

        // query to select all user records
        $query = "SELECT id FROM " . $this->table_name . "";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        // get number of rows
        $num = $stmt->rowCount();

        // return row count
        return $num;
    }

//    function read(){
//
//        // select all query
//        $query = "SELECT `id`,
//                `first_name`,
//                `last_name`,
//                email,
//                contact_number,
//                access_level
//            FROM
//                " . $this->table_name . "WHERE
//                id = $this->user_id
//            LIMIT
//                0,1";
//
//        // prepare query statement
//        $stmt = $this->conn->prepare($query);
//
//        // execute query
//        $stmt->execute();
//
//        return $stmt;
//    }

    function findUserByEmail($email) {

            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);

    }


    public function showError($stmt){
        echo "<pre>";
        print_r($stmt->errorInfo());
        echo "</pre>";
    }

}
<?php

// Include the database connection
require_once 'connection.php'; // Adjust path if needed

class AddPropertyController
{

    private $conn;

    public function __construct()
    {
        global $conn; // Access the global connection from connection.php
        $this->conn = $conn;
    }

    public function processForm()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Retrieve and sanitize form data
            $property_type = $this->conn->real_escape_string($_POST['property_type']);
            $title = $this->conn->real_escape_string($_POST['title']);
            $description = $this->conn->real_escape_string($_POST['description']);
            $price = $this->conn->real_escape_string($_POST['price']);
            $province = $this->conn->real_escape_string($_POST['province']);
            $city = $this->conn->real_escape_string($_POST['city']);
            $barangay = $this->conn->real_escape_string($_POST['barangay']);
            $full_address = $this->conn->real_escape_string($_POST['full_address']);
            $property_owner = $this->conn->real_escape_string($_POST['property_owner']); // From the hidden input
            $property_availability = $this->conn->real_escape_string($_POST['availability']);

            // Handle image uploads (now storing binary data)
            $uploaded_files = $this->handleImageUploads();

            // Prepare the SQL statement
            $sql = "INSERT INTO Listings (property_type, property_title, property_desc, property_price, property_pic1, property_pic2, property_pic3, property_pic4, property_pic5, property_province, property_city, property_barangay, property_address, property_owner, property_availability) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // Prepare the statement
            $stmt = $this->conn->prepare($sql);

            // Check for errors in preparation
            if ($stmt === false) {
                die('MySQL prepare failed: ' . $this->conn->error);
            }

            // Bind the parameters (binary data for images)
            $stmt->bind_param(
                'sssssssssssssss',  // Define the types for each parameter (all are strings in this case)
                $property_type, 
                $title, 
                $description, 
                $price, 
                $uploaded_files[0], 
                $uploaded_files[1], 
                $uploaded_files[2], 
                $uploaded_files[3], 
                $uploaded_files[4], 
                $province, 
                $city, 
                $barangay, 
                $full_address, 
                $property_owner, 
                $property_availability
            );

            // Execute the statement
            if ($stmt->execute()) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        }
    }

    private function handleImageUploads()
    {
        $uploaded_files = array();

        for ($i = 1; $i <= 5; $i++) {
            $file_key = 'photo' . $i;
            if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] == 0) {
                // Read the file content
                $file_content = file_get_contents($_FILES[$file_key]['tmp_name']);
                // Store the binary data (image content)
                $uploaded_files[] = $file_content;
            } else {
                $uploaded_files[] = null; // No image uploaded, set as null
            }
        }

        return $uploaded_files;
    }

    public function __destruct()
    {
        $this->conn->close();
    }
}

// Instantiate the controller and process the form
$controller = new AddPropertyController();
$controller->processForm();

?>

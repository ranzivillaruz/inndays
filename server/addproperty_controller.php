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

            // Handle image uploads
            $uploaded_files = $this->handleImageUploads();

            // Insert data into the database
            $sql = "INSERT INTO Listings (property_type, property_title, property_desc, property_price, property_pic1, property_pic2, property_pic3, property_pic4, property_pic5, property_province, property_city, property_barangay, property_address, property_owner, property_availability)
                    VALUES ('$property_type', '$title', '$description', '$price', 
                            '" . (isset($uploaded_files[0]) ? $uploaded_files[0] : '') . "',
                            '" . (isset($uploaded_files[1]) ? $uploaded_files[1] : '') . "',
                            '" . (isset($uploaded_files[2]) ? $uploaded_files[2] : '') . "',
                            '" . (isset($uploaded_files[3]) ? $uploaded_files[3] : '') . "',
                            '" . (isset($uploaded_files[4]) ? $uploaded_files[4] : '') . "',
                            '$province', '$city', '$barangay', '$full_address', '$property_owner', '$property_availability')";

            if ($this->conn->query($sql) === TRUE) {
                echo "New record created successfully"; // Or redirect
            } else {
                echo "Error: " . $sql . "<br>" . $this->conn->error; // Log the error for debugging
            }
        }
    }

    private function handleImageUploads()
    {
        $uploaded_files = array();
        $upload_dir = "uploads/"; // Directory where you want to save images

        // Check if directory exists, if not, create it
        if (!is_dir($upload_dir) && !mkdir($upload_dir, 0755, true)) { // Changed to 0755
            die("Error creating upload directory. Check permissions."); // More specific error message
        }

        for ($i = 1; $i <= 5; $i++) {
            $file_key = 'photo' . $i;
            if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] == 0) {
                $file_name = basename($_FILES[$file_key]['name']); // Use original file name
                $target_file = $upload_dir . $file_name; // Use original file name
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Check if file is a actual image or fake image
                $check = getimagesize($_FILES[$file_key]["tmp_name"]);
                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    $uploadOk = 0;
                }

                // Check file size
                if ($_FILES[$file_key]["size"] > 5000000) { // 5MB
                    $uploadOk = 0;
                }

                // Allow certain file formats
                $allowed_types = array("jpg", "png", "jpeg", "gif");
                if (!in_array($imageFileType, $allowed_types)) {
                    $uploadOk = 0;
                }

                // If everything is ok, try to upload file
                if ($uploadOk == 1) {
                    if (move_uploaded_file($_FILES[$file_key]["tmp_name"], $target_file)) {
                        $uploaded_files[] = $target_file; // Store file path
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                } else {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed, and file size must be under 5MB.";
                }
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
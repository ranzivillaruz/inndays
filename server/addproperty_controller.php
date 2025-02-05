<?php
session_start(); // Start session to store popup message

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
            $property_owner = $this->conn->real_escape_string($_POST['property_owner']);
            $property_availability = $this->conn->real_escape_string($_POST['availability']);

            // Handle image uploads (now storing binary data)
            $uploaded_files = $this->handleImageUploads();

            // Prepare the SQL statement
            $sql = "INSERT INTO Listings (property_type, property_title, property_desc, property_price, property_pic1, property_pic2, property_pic3, property_pic4, property_pic5, property_province, property_city, property_barangay, property_address, property_owner, property_availability) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);

            if ($stmt === false) {
                $_SESSION['popupMessage'] = 'Database error: ' . $this->conn->error;
                $_SESSION['popupType'] = 'error';
                header('Location: ../addproperty.php');
                exit();
            }

            $stmt->bind_param(
                'sssssssssssssss',
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

            if ($stmt->execute()) {
                $_SESSION['popupMessage'] = 'Sucessfully published!';
                $_SESSION['popupType'] = 'success';
            } else {
                $_SESSION['popupMessage'] = 'Error: ' . $stmt->error;
                $_SESSION['popupType'] = 'error';
            }

            $stmt->close();
            header('Location: ../addproperty.php'); // Redirect back to the form
            exit();
        }
    }

    private function handleImageUploads()
    {
        $uploaded_files = array();

        for ($i = 1; $i <= 5; $i++) {
            $file_key = 'photo' . $i;
            if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] == 0) {
                $file_content = file_get_contents($_FILES[$file_key]['tmp_name']);
                $uploaded_files[] = $file_content;
            } else {
                $uploaded_files[] = null;
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

<?php
include 'connect.php';

if(isset($_POST['update_listing'])) {
    $listing_id = $_POST['listing_id'];
    $property_name = $_POST['property_name'];
    $price = $_POST['price'];
    $address = $_POST['address'];
    $description = $_POST['description'];

    // Update the property details in the database
    $update_listing = $conn->prepare("UPDATE property SET property_name = ?, price = ?, address = ?, description = ? WHERE id = ?");
    $update_listing->execute([$property_name, $price, $address, $description, $listing_id]);

    echo "Listing updated successfully!";
}
?>

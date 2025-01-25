<?php 
include '../components/connect.php';

if(isset($_GET['get_id'])){
   $listing_id = $_GET['get_id'];
   $listing_id = filter_var($listing_id, FILTER_SANITIZE_STRING);

   // Fetch the current property details
   $select_listing = $conn->prepare("SELECT * FROM property WHERE id = ?");
   $select_listing->execute([$listing_id]);
   $fetch_listing = $select_listing->fetch(PDO::FETCH_ASSOC);
   if(!$fetch_listing){
      header('location:admin_listings.php'); // Redirect if the listing doesn't exist
   }
}

if(isset($_POST['update'])){
   $property_name = $_POST['property_name'];
   $price = $_POST['price'];
   $address = $_POST['address'];
   $description = $_POST['description'];

   // Handle image uploads (if necessary)
   $image_01 = $_FILES['image_01']['name'];
   $image_01_tmp = $_FILES['image_01']['tmp_name'];
   $image_01_size = $_FILES['image_01']['size'];

   if($image_01 != ""){
      $image_01_ext = pathinfo($image_01, PATHINFO_EXTENSION);
      $image_01_new_name = uniqid().".".$image_01_ext;
      move_uploaded_file($image_01_tmp, "../uploaded_files/".$image_01_new_name);

      // Delete old image if new one is uploaded
      unlink('../uploaded_files/'.$fetch_listing['image_01']);
   } else {
      $image_01_new_name = $fetch_listing['image_01']; // Keep the existing image if no new image is uploaded
   }

   // Update the property details
   $update_property = $conn->prepare("UPDATE property SET property_name = ?, price = ?, address = ?, description = ?, image_01 = ? WHERE id = ?");
   $update_property->execute([$property_name, $price, $address, $description, $image_01_new_name, $listing_id]);

   $success_msg[] = 'Listing updated successfully!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Edit Listing</title>
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
   
<?php include '../components/admin_header.php'; ?>

<section class="edit-property">
   <h1 class="heading">Edit Listing</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <input type="text" name="property_name" placeholder="Property Name" value="<?= htmlspecialchars($fetch_listing['property_name']); ?>" required>
      <input type="text" name="price" placeholder="Price" value="<?= htmlspecialchars($fetch_listing['price']); ?>" required>
      <input type="text" name="address" placeholder="Address" value="<?= htmlspecialchars($fetch_listing['address']); ?>" required>
      <textarea name="description" placeholder="Description" required><?= htmlspecialchars($fetch_listing['description']); ?></textarea>
      
      <!-- Image upload -->
      <label for="image_01">Property Image 1:</label>
      <input type="file" name="image_01">
      <img src="../uploaded_files/<?= $fetch_listing['image_01']; ?>" alt="Current Image" style="max-width: 200px; margin-top: 10px;">
      
      <input type="submit" value="Update Listing" name="update" class="btn">
   </form>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>
<?php include '../components/message.php'; ?>
</body>
</html>

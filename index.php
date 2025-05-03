<?php
session_start();


include 'conn.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $uniqueid = uniqid(mt_rand(1000, 999999), true); // Set minimum random number to 1000
    
    // Password validation
    $password = $_POST['password'];
    if (strlen($password) < 8 || 
        !preg_match("#[0-9]+#", $password) || 
        !preg_match("#[A-Za-z]+#", $password) || 
        !preg_match("#[\W]+#", $password)) {
        echo "Password must be at least 8 characters and contain at least one number, one letter, and one special character";
        exit();
    }
    
    
    $img = $_FILES['img']['name'];
    $img_tmp = $_FILES['img']['tmp_name'];
    $img_folder = "images/".$img;

    // Create images directory if it doesn't exist
    if (!file_exists('images')) {
        mkdir('images', 0777, true);
    }

    if (move_uploaded_file($img_tmp, $img_folder)) {
        
        $sql = "INSERT INTO users (uniqueid, fname, lname, email, password, img) 
                VALUES ('$uniqueid', '$fname', '$lname', '$email', '$password', '$img')";
        if (mysqli_query($conn, $sql)) {
           header("location: login.php");
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Failed to upload image";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Application</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
       <section class="form signup bg-white p-4 rounded shadow">
        <header class="h3 mb-4 pb-3 border-bottom">Realtime Chat App</header>
        <form action="#" method="POST" enctype="multipart/form-data">
            <div class="alert alert-danger error-txt">this is an error message!</div>
            <div class="name-details">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="fname" class="form-control" placeholder="First name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="lname" class="form-control" placeholder="Last name" required>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name ="email" class="form-control" placeholder="Email address" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Password</label>
                    <div class="password-field">
                        <input type="password" name="password" class="form-control" 
                               placeholder="Password" 
                               pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$"
                               title="Must contain at least 8 characters, one number, one letter, and one special character"
                               required>
                        <i class="eye-icon fas fa-eye"></i>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Select Image</label>
                    <input type="file" name="img" class="form-control" required>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" class="btn btn-primary w-100 py-2" value="Continue to Chat">
                </div>
            </div>
        </form>
        <div class="text-center mt-4">
            Already have an account? <a href="login.php" class="text-primary">Login now</a>
        </div>
       </section>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-code.js"></script>
   
</body>
</html>
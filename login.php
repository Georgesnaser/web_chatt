<?php
session_start();
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email' and password = '$password'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['uniqueid'] = $row["uniqueid"];
        $_SESSION['fname'] = $row['fname'];
        $_SESSION['lname'] = $row['lname'];
        $_SESSION['status'] = $row['status'];
        $_SESSION['img'] = $row['img'];
        header("Location: user.php");
    }else {
        echo "<script>alert('Invalid email or password');</script>";
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
        <form method="POST">
            <div class="alert alert-danger error-txt">this is an error message!</div>
            <div class="name-details">
                <div class="row">
                    <div class="form-group mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="Email address" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary w-100 py-2" value="Continue to Chat">
                    </div>
                </div>
            </div>
        </form>
        <div class="text-center mt-4">
          not yet signed up? <a href="index.php" class="text-primary">signup now</a>
        </div>
       </section>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
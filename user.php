<?php
session_start();
include "conn.php";

// Validate and retrieve session variables
$uniqueid = isset($_SESSION['uniqueid']) ? $_SESSION['uniqueid'] : '';
$fname = isset($_SESSION['fname']) ? $_SESSION['fname'] : '';
$lname = isset($_SESSION['lname']) ? $_SESSION['lname'] : '';
$status = isset($_SESSION['status']) ? $_SESSION['status'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$img = isset($_SESSION['img']) ? $_SESSION['img'] : '';

if (!isset($uniqueid)) {
    header("Location: login.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
       :root {
    --primary-color:rgb(97, 206, 231);
    --secondary-color: #ffffff;
    --background-color: #f7f9fc;
    --text-color: #333;
    --muted-text: #6c757d;
    --status-online: #28a745;
    --status-offline: #dc3545;  /* Changed to red */
    --shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    --radius: 12px;
    --transition: 0.3s ease;
}

body {
    margin: 0;
    background-color: var(--background-color);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: var(--text-color);
}

.wrapper {
    max-width: 500px;
    margin: 40px auto;
    background-color: var(--secondary-color);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.user header {
    background-color: var(--primary-color);
    padding: 20px;
    color: #fff;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.user .content img {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.details {
    margin-left: 15px;
}

.details span {
    font-weight: 600;
    font-size: 18px;
    display: block;
}

.details p {
    margin: 0;
    font-size: 14px;
    color: rgba(255, 255, 255, 0.85);  /* Brighter, more readable color */
}

.logout {
    background: rgba(255, 255, 255, 0.9);
    color: #2c3e50;  /* Dark blue-gray */
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    transition: var(--transition);
    text-decoration: none;
}

.logout:hover {
    background: #f8f9fa;  /* Light gray on hover */
    color: #1a252f;  /* Darker shade for contrast */
}

.search {
    padding: 20px;
    border-bottom: 1px solid #e0e0e0;
    background: #fafafa;
}

.search .text {
    color: var(--muted-text);
    font-size: 1rem;
}

.input-group input {
    border-radius: 6px 0 0 6px;
    border: 1px solid #ccc;
}

.input-group .btn {
    background-color: var(--primary-color);
    color: white;
    border-radius: 0 6px 6px 0;
}

.input-group .btn:hover {
    background-color: #1f5dc1;
}

.user-list {
    max-height: 350px;
    overflow-y: auto;
}

.user-list::-webkit-scrollbar {
    width: 6px;
}

.user-list::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}

.user-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid #f0f0f0;
    text-decoration: none;
    color: inherit;
    transition: background var(--transition);
}

.user-item:hover {
    background-color: #f0f4ff;
}

.user-item .content {
    display: flex;
    align-items: center;
}

.user-item .content img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 15px;
}

.user-name {
    font-weight: 500;
    font-size: 15px;
}

.user-item p {
    margin: 0;
    font-size: 13px;
    color: var(--muted-text);
}

.status-dot i {
    font-size: 12px;
}

    </style>
</head>
<body>
    <div class="wrapper">
        <section class="user">
            <header class="d-flex justify-content-between align-items-center">
                <div class="content d-flex align-items-center">
                    <img src="<?php echo htmlspecialchars($img); ?>" alt="User">
                    <div class="details">
                        <span><?php echo htmlspecialchars($fname . " " . $lname); ?></span>
                        <p>Status: <?php echo htmlspecialchars($status); ?></p>
                    </div>
                </div>
                <a href="logout.php" class="logout">Logout</a>
            </header>
                 
            <div class="search">
                <span class="text">Select a user to start chat</span>
                <div class="input-group mt-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Enter name to search...">
                    <button class="btn"><i class="fas fa-search"></i></button>
                </div>
            </div>

            <div class="user-list">
                <?php
                $sql = "SELECT * FROM users WHERE uniqueid != '{$uniqueid}'";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {

                ?>
                    <a href="chat.php?user_id=<?php echo $row['uniqueid']; ?>" class="user-item">
                        <div class="content">
                            <img src="<?php echo htmlspecialchars($row['img']); ?>" alt="User">
                            <div class="details">
                                <span class="user-name"><?php echo htmlspecialchars($row['fname'] . " " . $row['lname']); ?></span>
                                <p>Status: <?php echo htmlspecialchars($row['status']); ?></p>
                            </div>
                        </div>
                        <div class="status-dot">
                            <i class="fas fa-circle" style="color: <?php echo ($row['status'] == 'offline') ? '#dc3545' : '#28a745'; ?>"></i>
                        </div>
                    </a>
                <?php 
                    }

                } else {
                    echo "<p class='text-center'>No users available to chat</p>";
                }
                ?>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script >
    const searchInput = document.getElementById('searchInput');
const userItems = document.querySelectorAll('.user-item');

searchInput.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();

    userItems.forEach(item => {
        const userName = item.querySelector('.user-name').textContent.toLowerCase();
        if (userName.includes(searchTerm)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>
</body>
</html>
<?php
session_start();
include "conn.php";

$uniqueid = isset($_SESSION['uniqueid']) ? $_SESSION['uniqueid'] : '';
$fname = isset($_SESSION['fname']) ? $_SESSION['fname'] : '';
$lname = isset($_SESSION['lname']) ? $_SESSION['lname'] : '';
$status = isset($_SESSION['status']) ? $_SESSION['status'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$img = isset($_SESSION['img']) ? $_SESSION['img'] : '';

$incoming_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
$outcoming = isset($_SESSION['uniqueid']) ? $_SESSION['uniqueid'] : '';
$incoming =  $_GET['user_id'];

$sql = "SELECT * FROM messages 
        WHERE (outcoming = '{$outcoming}' AND incoming = '{$incoming}')
        OR (outcoming = '{$incoming}' AND incoming = '{$outcoming}')
        ORDER BY mesgid ASC";
$query = mysqli_query($conn, $sql);
$output = "";


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="chat.css">
</head>
<body>
    <div class="wrapper">
        <section class="chat-area">
        <?php
                $sql = "SELECT * FROM users WHERE uniqueid = '{$incoming_id}'";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                ?>
             <header class="d-flex justify-content-between align-items-center p-3 border-bottom">
                <div class="content d-flex align-items-center">
                      <img src="<?php echo $row['img']; ?>" alt="profile" class="rounded-circle me-3" style="width: 45px; height: 45px;">
                      <div class="details">
                        <span class="fw-medium fs-5"><?php echo htmlspecialchars($row['fname'] . " " . $row['lname']); ?></span>
                        <p class="text-muted mb-0"><?php echo  $row['status']; ?></p>
                      </div>
                </div>
                <?php } ?>
             </header>
             <div class="chat-box p-3">
                <?php include "get-chat.php"; ?>
             </div>
             <form id="chatForm" class="typing-area p-3 d-flex gap-2 bg-white">
                <input type="hidden" name="outcoming" value="<?php echo $uniqueid; ?>">
                <input type="hidden" name="incoming" value="<?php echo $incoming_id; ?>">
                <input type="text" name="msg" class="form-control border" placeholder="Type a message here..." required>
                <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center">
                    <i class="fab fa-telegram-plane"></i>
                </button>
             </form>
        </section>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const form = document.querySelector("#chatForm");
        const chatBox = document.querySelector(".chat-box");

        form.onsubmit = (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const inputField = form.querySelector("input[name='msg']");

            fetch("insert-chat.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                inputField.value = ""; // Clear only the message input
                fetch("get-chat.php?user_id=<?php echo $incoming_id; ?>")
                    .then(response => response.text())
                    .then(messages => {
                        chatBox.innerHTML = messages;
                        chatBox.scrollTop = chatBox.scrollHeight;
                    });
            });
        };
    </script>
</body>
</html>
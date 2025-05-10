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
$outcoming = $uniqueid;
$incoming = $incoming_id;

if (!$uniqueid || !$incoming_id) {
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
    <link rel="stylesheet" href="chat.css">
    <style>
        .incoming.message, .outgoing.message {
            display: flex;
            margin-bottom: 15px;
        }
        .incoming.message {
            justify-content: flex-start;
        }
        .outgoing.message {
            justify-content: flex-end;
        }
        .message p {
            padding: 10px 15px;
            border-radius: 12px;
            max-width: 70%;
            word-break: break-word;
        }
        .incoming.message p {
            background: #f0f2f5;
            color: #333;
        }
        .outgoing.message p {
            background: #0d6efd;
            color: white;
        }
    </style>
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
                <a href="user.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
                <div class="content d-flex align-items-center">
                      <img src="<?php echo $row['img']; ?>" alt="profile" class="rounded-circle me-3" style="width: 45px; height: 45px;">
                      <div class="details">
                        <span class="fw-medium fs-5"><?php echo htmlspecialchars($row['fname'] . " " . $row['lname']); ?></span>
                        <p class="text-muted mb-0"><?php echo $row['status']; ?></p>
                      </div>
                </div>
                <?php } ?>
             </header>
             <div class="chat-box p-3">
                <!-- Messages will be loaded here -->
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
        const inputField = form.querySelector("input[name='msg']");
        
        // Function to load messages
        function loadMessages() {
            const formData = new FormData();
            formData.append("outcoming", "<?php echo $outcoming; ?>");
            formData.append("incoming", "<?php echo $incoming; ?>");
            
            fetch("get-chat.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                chatBox.innerHTML = data;
                chatBox.scrollTop = chatBox.scrollHeight;
            });
        }
        
        // Load messages when page loads
        window.onload = loadMessages;
        
        // Auto refresh messages every 3 seconds
        setInterval(loadMessages, 3000);
        
        // Send message when form is submitted
        form.onsubmit = (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            
            fetch("insert-chat.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                inputField.value = ""; // Clear message input
                loadMessages(); // Reload messages to show the new one
            });
        };
    </script>
</body>
</html>
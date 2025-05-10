<?php
include "conn.php";

if(isset($_POST['outcoming']) && isset($_POST['incoming']) && isset($_POST['msg'])) {
    $outcoming = $_POST['outcoming'];
    $incoming = $_POST['incoming'];
    $message = mysqli_real_escape_string($conn, $_POST['msg']);

    // Get the userid from uniqueid
    $sql_outcoming = "SELECT userid FROM users WHERE uniqueid = '{$outcoming}'";
    $sql_incoming = "SELECT userid FROM users WHERE uniqueid = '{$incoming}'";
    
    $result_outcoming = mysqli_query($conn, $sql_outcoming);
    $result_incoming = mysqli_query($conn, $sql_incoming);
    
    if($result_outcoming && $result_incoming) {
        $row_outcoming = mysqli_fetch_assoc($result_outcoming);
        $row_incoming = mysqli_fetch_assoc($result_incoming);
        
        if($row_outcoming && $row_incoming) {
            $outcoming_userid = $row_outcoming['userid'];
            $incoming_userid = $row_incoming['userid'];

            if(!empty($message)) {
                $sql = "INSERT INTO messages (incoming, outcoming, msg) 
                        VALUES ('{$incoming_userid}', '{$outcoming_userid}', '{$message}')";
                if(mysqli_query($conn, $sql)) {
                    echo "success";
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            }
        }
    }
} else {
    echo "Missing required parameters";
}
?>

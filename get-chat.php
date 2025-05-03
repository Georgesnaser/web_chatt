<?php
include "conn.php";

if(isset($_POST['outcoming']) && isset($_POST['incoming'])) {
    $outcoming = $_POST['outcoming'];
    $incoming = $_POST['incoming'];
    
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
            
            $sql = "SELECT * FROM messages 
                    WHERE (outcoming = '{$outcoming_userid}' AND incoming = '{$incoming_userid}')
                    OR (outcoming = '{$incoming_userid}' AND incoming = '{$outcoming_userid}')
                    ORDER BY msg_id";
                    
            $result = mysqli_query($conn, $sql);
            $output = "";
            
            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    if($row['outcoming'] == $outcoming_userid) {
                        $output .= '<div class="outgoing">'.$row['msg'].'</div>';
                    } else {
                        $output .= '<div class="incoming">'.$row['msg'].'</div>';
                    }
                }
                echo $output;
            }
        }
    }
}
?>

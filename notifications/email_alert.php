<?php
function send_alert_example($email,$message){
    @mail($email,"New Feedback",$message,"From: noreply@feedbackflow");
}
?>
<title>PeachPits - Contact</title>
<?php 
  include "header.php";
  include "footer.php";
?>
<div class="page-head">
  <div style="margin-left: 60px; display:inline-block" class="container">
      <h1>Contact Us</h1>
  </div>
</div>
<div style="font-size: 18" class="container content">

<?php
  if(isset($_POST['email'])) {

    $email_to = "cmcdaniel@automationdirect.com,derek@comella.net,gabrielarkanum@byomes.com";

    function died($error) {
      // your error code can go here
      echo'<div class="container page-header">We are very sorry, but there were error(s). Please try again. If you keep getting this error, feel free to contact us directly, support@peachpits.com <br /><br /></div>';
      echo $error."<br /><br />";

      die();
    }
     
    $name = $_POST['name'];
    $email_from = $_POST['email'];
	  $topic = "[PeachPits Contact Form] " . $_POST['topic'];
    $event_name = $_POST['events'];
    $event_admin = $_POST['eventadmin'];
    $event_message = $_POST['eventmessage'];
    $bug_topic = $_POST['bugtopic'];
    $bug_device = $_POST['bugdevice'];
    $bug_desc = $_POST['bugdesc'];
    $other_msg = $_POST['othermsg'];
    $error_message = "";

    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
    $string_exp = "/^[A-Za-z .'-]+$/";

    function clean_string($string) {
        $bad = array("content-type","bcc:","to:","cc:","href");
        return str_replace($bad,"",$string);
    }  

    $email_message .= "Name: ".clean_string($name)."\n";
    $email_message .= "Email: ".clean_string($email_from)."\n";
    $email_message .= "Topic: ".clean_string($topic)."\n\n";
    if($topic == '[PeachPits Contact Form] event'){
      $email_message .= "Event Name: " .clean_string($event_name). "\n";
      $email_message .= "Event Admin: " .clean_string($event_admin). "\n";
      $email_message .= "Message: " .clean_string($event_message). "\n";
    } else if($topic == '[PeachPits Contact Form] bug'){
      $email_message .= "Where: " .clean_string($bug_topic). "\n";
      $email_message .= "Device: " .clean_string($bug_device). "\n";
      $email_message .= "Description: " .clean_string($bug_desc). "\n";
    } else if($topic == '[PeachPits Contact Form] other'){
      $email_message .= "Message: ".clean_string($other_msg). "\n";
    }
    
    // create email headers
    $headers = 'From: '.$email_from."\r\n".
    'Reply-To: '.$email_from."\r\n" .
    'X-Mailer: PHP/' . phpversion();
    @mail($email_to, $topic, $email_message, $headers);
?>
    <?php
      // echo 'Name: ' .$name. '<br>';
      // echo 'Email: '.$email_from. '<br>';
      // echo 'Topic: ' .$topic. '<br>';
      // echo '--- Event:'  .$event_name. '<br>';
      // echo '--- Event Admin: ' .$event_admin. '<br>';
      // echo '--- Event Message: ' .$event_message. '<br>';
      // echo '-<br>';
      // echo '--- Bug Topic: ' .$bug_topic. '<br>';
      // echo '--- Bug Device: ' .$bug_device. '<br>';
      // echo '--- Bug Desc: ' .$bug_desc. '<br>';
      // echo '-<br>';
      // echo '--- Other Message:' .$other_msg. '<br>';

      //echo $email_message;
    ?>
    <div class="page-header" style="margin-top: 0px">
		  <h1 class="text-center"><small>Thank you for contacting us. We'll get back to you shortly.</small></h1>
      <br>
      <h2 class="text-center"><small>Redirecting you back to the home page...</small></h2>
    </div>
    <script type="text/javascript">
      setTimeout(function () {
          window.location= "";
      }, 2500);
    </script>
<?php
  }
?>
</div>
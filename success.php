<?php
require_once __DIR__ . '/recaptcha/src/autoload.php';
$siteKey = '6LcywBATAAAAAICfBlAuQXBSYIrfReaupseJoD_U';
$secret = '6LcywBATAAAAABp28Xn7IPeCKxURZ6jS9KS6-Wb6';
$lang = 'en';
?>
<!DOCTYPE html>
<html>

    <head>
        <title>NZ Road Info | Success.</title>
        <link rel="shortcut icon" href="traffic-cone.ico">
        <link rel="shortcut icon" href="traffic-cone.png">
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <link rel="stylesheet" href="css/slicknav.css">
        <link rel="stylesheet" href="css/style.css">
        <script src="js/jquery.min.js"></script>
        <script src="js/jquery.slicknav.min.js"></script>
    </head>

    <!-- body of the page with an onload to run the initialize function -->

    <body>


        <div id="menu-wrapper">
            <ul id="menu">
                <li><a class="scroll" href="index.html">About</a></li>
                <li><a class="scroll" href="SHClosures.html">State Highways - closures and warnings</a></li>
                <li><a class="scroll" href="chchRoadClose.html">Christchurch - road closures</a></li>
                <li><a class="scroll" href="chchTraffic.html">Christchurch - road warnings and events</a></li>
                <li><a class="scroll" href="#">Contact Us - Suggestions, Bug Reporting, Happy Thoughts!</a></li>
            </ul>
        </div>
        
        <?php
            if (isset($_POST['g-recaptcha-response'])) {

                $recaptcha = new \ReCaptcha\ReCaptcha($secret);

                $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

                if ($resp->isSuccess()) {

                    if (isset($_POST['email'])) {

                        // EDIT THE 2 LINES BELOW AS REQUIRED

                        $email_to = "hamish.clulee@hotmail.com";

                        $email_subject = "Email submission from Nz Road Info";

                        function died($error) {

                            echo "We are very sorry, but there were error(s) found with the form you submitted. ";

                            echo "These errors appear below.<br /><br />";

                            echo $error . "<br /><br />";

                            echo "Please go back and fix these errors.<br /><br />";

                            die();
                        }

                        // validation expected data exists

                        if (!isset($_POST['name']) ||
                                !isset($_POST['email']) ||
                                !isset($_POST['message'])) {

                            died('Opps! Looks like theres a problem with the form you submitted.');
                        }

                        $name = $_POST['name']; // required

                        $email = $_POST['email']; // required

                        $message = $_POST['message']; // required

                        $error_message = "";

                        $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

                        if (!preg_match($email_exp, $email)) {

                            $error_message .= 'The email address you entered does not appear to be valid.<br />';
                        }

                        $string_exp = "/^[A-Za-z .'-]+$/";

                        if (!preg_match($string_exp, $name)) {

                            $error_message .= 'The name you entered does not appear to be valid.<br />';
                        }

                        if (strlen($message) < 2) {

                            $error_message .= 'The message you entered does not appear to be valid.<br />';
                        }

                        if (strlen($error_message) > 0) {

                            died($error_message);
                        }

                        function clean_string($string) {

                            $bad = array("content-type", "bcc:", "to:", "cc:", "href");

                            return str_replace($bad, "", $string);
                        }

                        $email_message .= "First Name: " . clean_string($name) . "\n";

                        $email_message .= "Email: " . clean_string($email) . "\n";

                        $email_message .= "Message: " . clean_string($message) . "\n";


                        // create email headers

                        $headers = 'From: contact@nzroadinfo.xyz \r\n' .
                                'Reply-To: ' . $email . "\r\n" .
                                'X-Mailer: PHP/' . phpversion();

                        @mail($email_to, $email_subject, $email_message, $headers);
                        ?>



                        <p>Thanks for your interest! We will get back to you asap.</p>

                        <?php
                    }
                }
            }
            ?>



        <script>
            $('#menu').slicknav({
                prependTo: "#menu-wrapper",
                label: ""
            });
        </script>
    </body>

</html>

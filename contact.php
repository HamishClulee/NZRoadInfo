<?php
require_once __DIR__ . '/recaptcha/src/autoload.php';
$siteKey = '6LcywBATAAAAAICfBlAuQXBSYIrfReaupseJoD_U';
$secret = '6LcywBATAAAAABp28Xn7IPeCKxURZ6jS9KS6-Wb6';
$lang = 'en';
?>
<!DOCTYPE html>
<html>

    <head>
        <title>NZ Road Info | Contact Us</title>
        <link rel="shortcut icon" href="traffic-cone.ico">
        <link rel="shortcut icon" href="traffic-cone.png">
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <link rel="stylesheet" href="css/slicknav.css">
        <link rel="stylesheet" href="css/style.css">
        <script src="js/jquery.min.js"></script>
        <script src="js/jquery.slicknav.min.js"></script>
        <script src="js/modernizr.js"></script>
        <script src='https://www.google.com/recaptcha/api.js'></script>
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

        <h1>Contact Us - Suggestions, Bug reporting, happy thoughts!</h1>

        <form class="cd-form floating-labels" action="success.php" method="POST">
                <div class="icon">
                    <label class="cd-label" for="cd-name">Name</label>
                    <input class="user" type="text" name="name" id="cd-name" required>
                </div> 
                <div class="icon">
                    <label class="cd-label" for="cd-email">Email</label>
                    <input class="email" type="email" name="email" id="cd-email" required>
                </div>
                <div class="icon">
                    <label class="cd-label" for="cd-textarea">Message</label>
                    <textarea class="message" name="message" id="cd-textarea" required></textarea>
                </div>

                <div>
                    <div class="g-recaptcha" data-sitekey="6LcywBATAAAAAICfBlAuQXBSYIrfReaupseJoD_U"></div>
                    <input type="submit" value="Send Message">
                </div>
        </form>
        
        <script>
            $('#menu').slicknav({
                prependTo: "#menu-wrapper",
                label: ""
            });
        </script>
        <script src="js/jquery-2.1.1.js"></script>
        <script src="js/main.js"></script>
    </body>

</html>
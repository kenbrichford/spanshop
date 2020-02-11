<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link type="text/css" rel="stylesheet" href="/css/style.css">
    <link rel="shortcut icon" type="image/png" href="/favicon-96x96.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.12.0/lodash.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="/js/jquery.js"></script>
    <title>Contact | SpanShop</title>
    <meta name="description" content="Shop all the top stores for your favorite best-selling products, electronics, books, clothing, video games, toys, jewelry, and more, all in one convenient place.">
    <meta name="keywords" content="price, check, comparison, online, shopping, best, products, electronics, books, clothing, video, games, toys, jewelry, sporting, goods, home, garden, tools, pet, supplies">
  </head>

  <body>
    <div id="wrapper">
      <div id="header" class="headertwo">
        <div id="headwrap">
          <a href="/"><img id="headimgtwo" class="prescroll" src="/media/spanshopnamesmall.png"></a>
          <div id="menubutton"></div>
          <form method="get" action="/" id="searchform" class='searchtwo'>
            <input id="searchbar" type="search" name="q"
            <?php if(isset($_GET["q"])) {
                echo 'value="'.htmlentities($_GET["q"]).'" placeholder="Your Wares. Your Way."';
              } else { echo 'placeholder="Your Wares. Your Way."';
            } ?> autocomplete="off"><input type="submit" value="">
  					<input type='hidden' name='c' value="All">
  					<input type='hidden' name='s' value="Relevance">
            <input type='hidden' name='p' value="1">
          </form>
        </div>
      </div>

      <?php
        $postData = $statusMsg = '';
        $status = 'error';

        // If the form is submitted
        if(isset($_POST['submit'])){
            $postData = $_POST;

            // Validate form fields
            if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['message'])){
                // Validate reCAPTCHA box
                if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
                    // Google reCAPTCHA API secret key
                    $secretKey = '6LeZyr0UAAAAAC5hWxFt0G7EWDELCJ0MP9xzMNxy';

                    // Verify the reCAPTCHA response
                    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']);

                    // Decode json data
                    $responseData = json_decode($verifyResponse);

                    // If reCAPTCHA response is valid
                    if($responseData->success){
                        // Posted form data
                        $name = !empty($_POST['name'])?$_POST['name']:'';
                        $email = !empty($_POST['email'])?$_POST['email']:'';
                        $message = !empty($_POST['message'])?$_POST['message']:'';

                        // Send email notification to the site admin
                        $to = 'admin@span-shop.com';
                        $subject = 'New contact form have been submitted';
                        $htmlContent = "
                            <h1>Contact request details</h1>
                            <p><b>Name: </b>".$name."</p>
                            <p><b>Email: </b>".$email."</p>
                            <p><b>Message: </b>".$message."</p>
                        ";

                        // Always set content-type when sending HTML email
                        $headers = "MIME-Version: 1.0" . "\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                        // More headers
                        $headers .= 'From:'.$name.' <'.$email.'>' . "\r\n";

                        // Send email
                        @mail($to,$subject,$htmlContent,$headers);

                        $status = 'success';
                        $statusMsg = 'Your form has been submitted successfully. Thank you for your feedback.';
                        $postData = '';
                    }else{
                        $statusMsg = 'Robot verification failed, please try again.';
                    }
                }else{
                    $statusMsg = 'Please check on the reCAPTCHA box.';
                }
            }else{
                $statusMsg = 'Please fill all the mandatory fields.';
            }
        }
      ?>

      <div id="content">
        <div id="results">
          <?php if(!empty($statusMsg)) {?>
            <p class='status-msg <?php echo $status; ?>'><?php echo $statusMsg; ?></p>
          <?php } ?>

          <form class="homeCon" id="contact" name="contact" action="" method="post">
            <h3>Have a Suggestion?</h3><hr>
            <input id="conName" name="name" type="text" placeholder="Name (required)" required>
            <input id="conEmail" name="email" type="email" placeholder="Email (required)" required>
            <textarea id="conMess" name="message" rows="5" placeholder="Message (required)" required></textarea>
            <div class='g-recaptcha' data-sitekey='6LeZyr0UAAAAAOYQxxshqFSHV6j2Eo1wvPI9NP3n'></div>
            <input id="conSubm" name="submit" type="submit" value="Send">
          </form>
        </div>
      </div>

      <div id="footer">
        <div class="disclaimer">SpanShop is part of a product affiliate program which means that we might make money from purchases
          made through links found on this site.</div>
        <div class="disclaimer">All product and company names are trademarks &trade; or registered &reg; trademarks of their respective holders.
          Use of them does not imply any affiliation with or endorsement by them.</div>
        <div id="copyright">Copyright &copy; <?php
        $year = new DateTime(null, new DateTimeZone('America/New_York'));
        echo $year->format('Y');
        ?> SpanShop.com. All rights reserved.</div>
      </div>
    </div>
  </body>
</html>

<?php
require_once "php/configuration.php";
require 'vendor/autoload.php';

\Stripe\Stripe::setApiKey($stripeSK);

$session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'name' => 'T-shirt',
                    'description' => 'Comfortable cotton t-shirt',
                    'images' => ['https://example.com/t-shirt.png'],
                    'amount' => 500,
                    'currency' => 'eur',
                    'quantity' => 1,
                ], [
                    'name' => 'T-shirt',
                    'description' => 'Comfortable cotton t-shirt',
                    'images' => ['https://example.com/t-shirt.png'],
                    'amount' => 500,
                    'currency' => 'eur',
                    'quantity' => 1,
                ]],
            'success_url' => 'https://example.com/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'https://example.com/cancel',
        ]);
echo $session;
?>

<!DOCTYPE html>
<html lang="zxx" class="no-js">

    <head>
        <!-- Mobile Specific Meta -->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Favicon-->
        <link rel="shortcut icon" href="img/fav.png">
        <!-- Author Meta -->
        <meta name="author" content="CodePixar">
        <!-- Meta Description -->
        <meta name="description" content="">
        <!-- Meta Keyword -->
        <meta name="keywords" content="">
        <!-- meta character set -->
        <meta charset="UTF-8">
        <!-- Site Title -->
        <title>Just Another Minecraft Store Payment</title>

        <!--
            CSS
            ============================================= -->
        <link rel="stylesheet" href="css/linearicons.css">
        <link rel="stylesheet" href="css/owl.carousel.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/themify-icons.css">
        <link rel="stylesheet" href="css/nice-select.css">
        <link rel="stylesheet" href="css/nouislider.min.css">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="https://js.stripe.com/v3/"></script>
        <script>
            var stripe = Stripe('<?php echo $stripePK; ?>');
            stripe.redirectToCheckout({
                // Make the id field from the Checkout Session creation API response
                // available to this file, so you can provide it as parameter here
                // instead of the {{CHECKOUT_SESSION_ID}} placeholder.
                sessionId: '<?php echo $session->id; ?>'
            }).then(function (result) {
                // If `redirectToCheckout` fails due to a browser or network
                // error, display the localized error message to your customer
                // using `result.error.message`.
            });
        </script>

    </head>
    <body>
        
    </body>
</html>


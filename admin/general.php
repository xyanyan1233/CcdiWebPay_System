<?php
// Include the required configuration and setup files
require_once '../system/config.php';  // Adjust path to your config file
require_once '../vendor/autoload.php';  // Make sure the autoload file is correct

// Set up Twig
$loader = new \Twig\Loader\FilesystemLoader('../theme/admin');  // Adjust path to your template folder
$twig = new \Twig\Environment($loader);

session_start();

// Check if the admin is logged in, if not, redirect to login page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Define the settings (these can be fetched from a database or a configuration file)
$settings = [
    'panelName' => 'CCDI',
    'currency' => 'PHP',
    'timePeriod' => '(UTC -5:00) Eastern Standard Time, Western Caribbean Standard Time',
    'maintenanceMode' => 'Off',
    'transferFunds' => 'Disabled',
    'coupons' => 'Disabled',
    'supportSystem' => 'On',
    'maxPendingTickets' => 1,
    'newUser' => 'On',
    'numberField' => 'Enable',
    'namespace' => 'Enable',
    'contractAtRegistration' => 'Enable',
    'confirmationAtOrder' => 'Enable',
    'forgotPassword' => 'Enable',
    'transferFundsPercentage' => 1,
    'serviceList' => 'Open to everyone',
    'autoRefill' => 'Enable',
    'avgCompletionTimes' => 'Enable',
    'ifServiceDown' => 'Warn & Disable Service',
    'smsVerification' => 'Disable',
    'emailVerification' => 'Disable',
    'admin2fa' => 'Disable',
    'googleLogin' => 'Enable',
    'redirectUri' => 'https://aidriane.com/google',
    'clientId' => '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',
    'clientSecret' => '1022188795615-n2kig0fjoqkt4gfaq8k6kam00pevbiaj.apps.googleusercontent.com',
    'musicUrl' => '',
    'headerCode' => '<style type="text/css">/* Custom CSS */</style>',
    'footerCode' => '<script>/* Custom JS */</script>',
    'bannerConfirmation' => 'Disabled',
    'bannerUrl' => ''
];

echo $twig->render('general.twig', $settings);
?>

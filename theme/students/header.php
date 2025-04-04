<?php
// Get the current file name to set as the title
$pageTitle = basename($_SERVER['PHP_SELF'], ".php");
$pageTitle = ucfirst(str_replace("_", " ", $pageTitle));

// Define navigation links
$navLinks = [
    "new_payment.php" => "New Payments",
    "payments.php" => "Payments",
    "inbox.php" => "Inbox",
    "transactions.php" => "Transactions",
    "cards.php" => "Cards",
    "earn_money.php" => "Earn Money",
    "cash_in.php" => "Cash In",
    "cash_out.php" => "Cash Out",
    "request.php" => "Request",
    "transfer_money.php" => "Transfer Money",
    "profile.php" => "Profile"
];

// Determine the active page
$activePage = basename($_SERVER['PHP_SELF']);

?>

<?php
// Set the HTTP response status to 404
http_response_code(404);

//include 'theme/admin/header.twig';

// Display a user-friendly message
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <link rel="stylesheet" href="path/to/your/css/style.css"> <!-- Adjust the path as necessary -->
    <style>
        body {
            text-align: center;
            padding: 150px;
        }
        h1 {
            font-size: 50px;
        }
        body {
            font: 20px Helvetica, sans-serif;
            color: #333;
        }
        a {
            color: #333;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h1>404 Not Found</h1>
<p>Sorry, the page you are looking for does not exist.</p>
<p><a href="index.php">Go back to the homepage</a></p>

</body>
</html>

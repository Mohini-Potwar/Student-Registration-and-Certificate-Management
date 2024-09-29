<!-- Retrieve user information from the database -->
<?php include "db.php"; ?>

<?php
session_start();

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if the form was submitted
if (isset($_POST["submit"])) {
    // Get the username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Query the database for the user with the matching username and password
    $stmt = $db->prepare(
        "SELECT * FROM admins WHERE username = :username AND password = :password"
    );
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":password", $password);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if a user was found
    if ($user) {
        // Store the user's ID in a session variable
        $_SESSION["user_id"] = $user["id"];

        // Redirect the user to the dashboard page
        header("Location: dashboard.php");
        exit();
    } else {
        // Display an error message
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS for Aesthetic Theme -->
    <style>
        body {
            background-color: #f0f0f0; /* Light gray background */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            margin-top: 70px;
        }

        .card {
            background-color: #ffffff; /* White background for card */
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1); /* Subtle shadow for 3D effect */
        }

        .card-header {
            background-color: #ffffff; /* Navy blue header */
            color: black;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .card-body {
            padding: 30px;
        }

        .form-group label {
            font-weight: bold;
            color: #333;
        }

        .form-control {
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Soft shadow for inputs */
        }

        .btn-primary {
            background-color: #001f3f; /* Navy blue button */
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            border-radius: 6px;
        }

        .btn-primary:hover {
            background-color: #001a35; /* Darker navy blue on hover */
        }

        .alert {
            margin-top: 20px;
            border-radius: 6px;
        }

        .alert-danger {
            background-color: #f8d7da; /* Light red for error messages */
            color: #721c24;
        }

        /* Mobile responsiveness */
        @media (max-width: 576px) {
            .card {
                margin-top: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Login
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" name="username" id="username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

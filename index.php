<?php 
include "admin/db.php"; 

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Verify Certificate
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $certificate_id = $_POST['certificate_id'];

    // Check if certificate exists
    $query = "SELECT * FROM certificates WHERE certificate_id = :certificate_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':certificate_id', $certificate_id);
    $stmt->execute();
    $certificate = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($certificate) {
        $student_id = $certificate['student_id'];

        // Retrieve student information
        $query = "SELECT * FROM students WHERE student_id = :student_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student) {
            // Display student information
        } else {
            // No student found
        }
    } else {
        // Invalid certificate
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify Certificate</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }

        .header {
            padding: 10px 0;
            background-color: #fff;
            border-bottom: 1px solid #ddd;
        }

        .header .logo {
            font-size: 28px;
            font-weight: bold;
            color: #e74c3c;
            display: inline-block;
            padding: 0 20px;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            text-align: center;
        }

        h2 {
            font-size: 24px;
            color: #333;
        }

        .form-group {
            margin: 30px auto;
        }

        #certificate_id {
            width: 350px;
            padding: 12px 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .btn {
            background-color: #ff5722;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #e64a19;
        }

        .alert {
            margin-top: 20px;
            text-align: center;
        }

        .alert i {
            font-size: 36px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="header">
        <span class="logo">ZYLKER</span>
    </div>

    <div class="container">
        <h2>Verify Learner Certificate</h2>
        <p>Enter the learner's credential ID to verify the certificate</p>

        <form method="POST">
            <div class="form-group">
                <input type="text" id="certificate_id" name="certificate_id" placeholder="Certificate ID" required>
            </div>
            <button type="submit" class="btn">Verify</button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <?php if ($certificate): ?>
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle"></i>
                    <h3>Congratulations! Certificate is valid.</h3>
                    <div class="mt-3">
                        <table class="table">
                            <tr>
                                <th>Student ID</th>
                                <td><?php echo $student['student_id']; ?></td>
                            </tr>
                            <tr>
                                <th>Student Name</th>
                                <td><?php echo $student['student_name']; ?></td>
                            </tr>
                            <tr>
                                <th>Course Name</th>
                                <td><?php echo $student['course_name']; ?></td>
                            </tr>
                            <tr>
                                <th>Date of Joining</th>
                                <td><?php echo $student['date_of_joining']; ?></td>
                            </tr>
                        </table>
                        <form action="generate-certificate.php" method="POST" target="_blank">
                            <input type="hidden" name="student_id" value="<?php echo $student['student_id']; ?>">
                            <input type="hidden" name="certificate_id" value="<?php echo $certificate['certificate_id']; ?>">
                            <button type="submit" class="btn btn-success"><i class="fas fa-certificate"></i> Generate Certificate</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-times-circle"></i>
                    <h3>Invalid Certificate</h3>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="footer">
        Powered by Zylker Academy of Training
    </div>

    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>
</html>

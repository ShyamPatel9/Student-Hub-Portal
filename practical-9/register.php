
<?php
require 'db.php';

$message = "";
$messageType = "";

$name = "";
$email = "";
$mobile = "";
$course = "";
$year = "";
$gender = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $year = trim($_POST["year"] ?? "");
    $gender = $_POST["gender"] ?? "";
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";

    if (
        $name === "" || $email === "" || $mobile === "" ||
        $course === "" || $year === "" || $gender === "" ||
        $password === "" || $confirmPassword === ""
    ) {
        $message = "Please fill in all fields.";

    } elseif (!preg_match("/^[a-zA-Z ]{2,100}$/", $name)) {
        $message = "Enter a valid name.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Enter a valid email address.";

    } elseif (strlen($email) > 100) {
        $message = "Email must not exceed 100 characters.";

    } elseif (!preg_match("/^[0-9]{10,15}$/", $mobile)) {
        $message = "Mobile must contain 10-15 digits.";

    } elseif (strlen($course) > 100) {
        $message = "Course name is too long.";

    } elseif (
        filter_var($year, FILTER_VALIDATE_INT) === false ||
        (int)$year < 1 || (int)$year > 6
    ) {
        $message = "Enter a valid year between 1 and 6.";

    } elseif (!in_array($gender, ["Male", "Female", "Other"], true)) {
        $message = "Select a valid gender.";

    } elseif (
        strlen($password) < 8 ||
        strlen($password) > 72
    ) {
        $message = "Password must be 8-72 characters.";

    } elseif (
        !preg_match("/[0-9]/", $password) ||
        !preg_match("/[^a-zA-Z0-9]/", $password)
    ) {
        $message = "Password must contain a number and a special character.";

    } elseif ($password !== $confirmPassword) {
        $message = "Passwords do not match.";

    } else {

        $check = mysqli_prepare(
            $conn,
            "SELECT student_id FROM students WHERE email = ?"
        );

        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $message = "Email already registered.";
        } else {
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO students
                (name, email, mobile, course, year, gender, password)
                VALUES (?, ?, ?, ?, ?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "ssssiss",
                $name,
                $email,
                $mobile,
                $course,
                $year,
                $gender,
                $hashedPassword
            );

            if (mysqli_stmt_execute($stmt)) {
                $message = "Registration successful!";
                $messageType = "success";

                $name = "";
                $email = "";
                $mobile = "";
                $course = "";
                $year = "";
                $gender = "";
            } elseif (mysqli_stmt_errno($stmt) === 1062) {
                $message = "Email already registered.";
            } else {
                $message = "Registration failed. Please check your database.";
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_stmt_close($check);
    }

    if ($messageType !== "success") {
        $messageType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentHub Registration</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f4f8;
            padding: 25px;
        }

        form {
            max-width: 450px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        input:not([type="radio"]) {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            box-sizing: border-box;
        }

        input[type="radio"] {
            margin: 12px 5px 12px 0;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #1e82c8;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .success {
            color: green;
            text-align: center;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>

<body>
    <form method="POST" id="registrationForm">
        <h2>StudentHub Registration</h2>

        <?php if ($message !== "") { ?>
            <p class="<?php echo htmlspecialchars($messageType); ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php } ?>

        <label>Full Name</label>
        <input type="text" name="name" required minlength="2"
               maxlength="100" pattern="[A-Za-z ]{2,100}"
               value="<?php echo htmlspecialchars($name); ?>">

        <label>Email</label>
        <input type="email" name="email" required maxlength="100"
               value="<?php echo htmlspecialchars($email); ?>">

        <label>Mobile</label>
        <input type="tel" name="mobile" required
               pattern="[0-9]{10,15}" maxlength="15"
               value="<?php echo htmlspecialchars($mobile); ?>">

        <label>Course</label>
        <input type="text" name="course" required maxlength="100"
               value="<?php echo htmlspecialchars($course); ?>">

        <label>Year</label>
        <input type="number" name="year" required min="1" max="6"
               value="<?php echo htmlspecialchars($year); ?>">

        <label>Gender</label><br>

        <input type="radio" name="gender" value="Male" required
            <?php echo $gender === "Male" ? "checked" : ""; ?>> Male

        <input type="radio" name="gender" value="Female"
            <?php echo $gender === "Female" ? "checked" : ""; ?>> Female

        <input type="radio" name="gender" value="Other"
            <?php echo $gender === "Other" ? "checked" : ""; ?>> Other

        <br><br>

        <label>Password</label>
        <input type="password" name="password" required
               minlength="8" maxlength="72"
               pattern="(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,72}"
               title="Use at least 8 characters, including a number and special character.">

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required
               minlength="8" maxlength="72">

        <button type="submit">Register</button>

        <p><a href="students.php">View Student Details</a></p>
    </form>

    <script>
        document.getElementById("registrationForm").addEventListener("submit", function (e) {
            const password = this.elements["password"].value;
            const confirmPassword = this.elements["confirm_password"].value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert("Passwords do not match!");
            }
        });
    </script>
</body>
</html>

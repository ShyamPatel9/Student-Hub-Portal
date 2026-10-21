<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $username = $_POST["username"] ?? "";
    $gender   = $_POST["gender"] ?? "";
    $dob      = $_POST["dob"] ?? "";
    $email    = $_POST["email"] ?? "";
    $mobile   = $_POST["mobile"] ?? "";
    $course   = $_POST["course"] ?? "";
    $year     = $_POST["year"] ?? "";

    // CSV file location
    $file = "register.csv";

    // Check if file exists and is empty
    $isNewFile = !file_exists($file) || filesize($file) == 0;

    // Open CSV file
    $handle = fopen($file, "a");

    if ($handle === false) {
        die("Unable to open CSV file.");
    }

    // Add column headings for first record
    if ($isNewFile) {
        fputcsv($handle, [
            "Full Name",
            "Gender",
            "Date of Birth",
            "Email",
            "Mobile Number",
            "Course",
            "Year"
        ]);
    }

    // Add student data
    fputcsv($handle, [
        $username,
        $gender,
        $dob,
        $email,
        $mobile,
        $course,
        $year
    ]);

    // Close file
    fclose($handle);

    echo "<h2>Student data saved successfully!</h2>";
    echo "<p>Data has been added to students.csv</p>";
    echo '<a href="../pgs/register.html">Go Back</a>';

} else {
    echo "Invalid request.";
}

?>

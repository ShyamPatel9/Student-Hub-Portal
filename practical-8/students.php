
<?php
include 'db.php';

$sql = "SELECT student_id, name, email, mobile, course, year, gender
        FROM students";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Details</title>
    <style>
        body {
            font-family: Arial;
            margin: 30px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: lightblue;
        }
    </style>
</head>
<body>
    <h2>StudentHub - Student Details</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Course</th>
            <th>Year</th>
            <th>Gender</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['student_id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['mobile'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($row['course'] ?? ''); ?></td>
            <td><?php echo $row['year']; ?></td>
            <td><?php echo htmlspecialchars($row['gender'] ?? ''); ?></td>
        </tr>
        <?php } ?>
    </table>

    <br>
    <a href="register.php">Add Another Student</a>
</body>
</html>

<?php
mysqli_close($conn);
?>

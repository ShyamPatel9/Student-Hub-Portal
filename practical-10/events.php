```php
<?php
require_once __DIR__ . '/../php/auth.php';
require_once __DIR__ . '/../StudentHub/db.php';

$sql = "SELECT event_id, title, description, event_date, location
        FROM events
        ORDER BY event_date ASC";

$result = mysqli_query($conn, $sql);

if ($result === false) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/style.css">
    <title>StudentHub Events</title>
</head>
<body>

<header>
    <h1>Student Hub - Events</h1>
    <hr>
</header>

<?php if (mysqli_num_rows($result) === 0): ?>
    <p class="label">No events have been added yet.</p>
<?php endif; ?>

<?php while ($event = mysqli_fetch_assoc($result)): ?>
    <section class="label">
        <h2><?= htmlspecialchars($event['title']) ?></h2>

        <p>
            <?= nl2br(htmlspecialchars($event['description'] ?? '')) ?>
        </p>

        <p>
            <b>Date:</b>
            <?= htmlspecialchars($event['event_date']) ?>
        </p>

        <p>
            <b>Location:</b>
            <?= htmlspecialchars($event['location'] ?? 'To be announced') ?>
        </p>
        <hr>
    </section>
<?php endwhile; ?>

<p>
    <a class="button" href="dashboard.php">Back to Dashboard</a>
</p>

<footer>
    <h2>@2026 Student portal</h2>
</footer>

</body>
</html>
```
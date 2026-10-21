<?php
require_once __DIR__ . '/../php/auth.php';
requireRole('admin');
require_once __DIR__ . '/../StudentHub/db.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $eventDate = $_POST['event_date'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $validDate = DateTime::createFromFormat('Y-m-d', $eventDate);
    if ($title === '' || strlen($title) > 150 || $eventDate === '' || !$validDate || $validDate->format('Y-m-d') !== $eventDate) {
        $message = 'Enter a title (up to 150 characters) and a valid event date.';
    } else {
        $stmt = mysqli_prepare($conn, 'INSERT INTO events (title, description, event_date, location, created_by) VALUES (?, ?, ?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'ssssi', $title, $description, $eventDate, $location, $_SESSION['user_id']);
        if (mysqli_stmt_execute($stmt)) {
            $message = 'Event added successfully.';
        } else {
            $message = 'Could not save the event. Check that the database setup SQL has been imported.';
        }
    }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../css/style.css"><title>Admin - Add Event</title></head><body>
<header><h1>Admin: Add Event</h1><hr></header>
<?php if ($message): ?><p class="label" role="status"><?= htmlspecialchars($message) ?></p><?php endif; ?>
<form method="post" action="admin_events.php">
<label class="label" for="title">Event title</label><br><input class="button" id="title" name="title" maxlength="150" required><br><br>
<label class="label" for="description">Description</label><br><textarea class="button" id="description" name="description" rows="5"></textarea><br><br>
<label class="label" for="event_date">Event date</label><br><input class="button" type="date" id="event_date" name="event_date" required><br><br>
<label class="label" for="location">Location</label><br><input class="button" id="location" name="location" maxlength="200"><br><br>
<button class="button" type="submit">Add Event</button>
</form>
<p><a class="button" href="events.php">View Events</a> <a class="button" href="dashboard.php">Dashboard</a> <a class="button" href="logout.php">Logout</a></p>
<footer><h2>@2026 Student portal</h2></footer></body></html>

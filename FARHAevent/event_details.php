<?php
try {
    $connect = new PDO("mysql:host=localhost;dbname=farhaevents", "root", "");
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$eventId = isset($_GET['eventId']) ? $_GET['eventId'] : '';  // استلام eventId من الرابط

if (empty($eventId)) {
    echo "Aucun événement sélectionné.";
    exit;
}

$query = "SELECT evenement.eventTitle, evenement.eventDescription, evenement.TariffNormal,
          evenement.TariffReduit, edition.image, edition.dateEvent, edition.timeEvent
          FROM evenement
          JOIN edition ON evenement.eventId = edition.eventId
          WHERE evenement.eventId = :eventId";

$stmt = $connect->prepare($query);
$stmt->bindValue(':eventId', $eventId);
$stmt->execute();
$event = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$event) {
    echo "Event not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Details</title>
    <link rel="stylesheet" href="details.css">
</head>
<style>
body {
    font-family: Arial, sans-serif;
    background-image: url(RED.jpg) !important;
    margin: 0;
    padding: 0;
}

.details {
    width: 90%;
    max-width: 600px;  /* Reduced max-width of the card */
    height: auto;  /* Let the card grow in height */
    margin: 50px auto;
    background-color: rgba(0, 0, 0, 0.5); 
    padding: 30px;  /* Increased padding for more space */
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    color: white;
}

.details img {
    width: 60%;  /* Further reduced image size */
    max-width: 400px;  /* Maximum width for the image */
    height: auto;
    border-radius: 10px;
    margin-bottom: 20px;
    object-fit: cover;
    color:white;
}

.details h1 {
    color: white;
    font-size: 28px;
    margin-bottom: 10px;
    font-weight: 700;
    text-align: center;
}

.details p {
    font-size: 16px;
    color: white;
    line-height: 1.6;
    margin-bottom: 10px;
    text-align: center;
}

.details label {
    font-size: 16px;
    color: white;
}

.details input[type="number"] {
    padding: 10px;
    width: 180px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    background-color: #fafafa;
}

.details button {
    background-color:rgb(84, 14, 14);
    color: white;
    border: none;
    padding: 10px 25px;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.details button:hover {
    background-color:rgb(35, 6, 6);
}

.details form {
    margin-top: 20px;
}

.details .error {
    color: #e74c3c;
    font-size: 16px;
    margin-top: 20px;
}

</style>
<body>
    <div class="details">
        <img src="<?= htmlspecialchars($event['image']) ?>" alt="Event Image">
        <h1><?= htmlspecialchars($event['eventTitle']) ?></h1>
        <p><?= htmlspecialchars($event['eventDescription']) ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($event['dateEvent']) ?></p>
        <p><strong>Time:</strong> <?= htmlspecialchars($event['timeEvent']) ?></p>
        <p><strong>Standard Ticket:</strong> <?= htmlspecialchars($event['TariffNormal']) ?> DH</p>
        <p><strong>Discounted Ticket:</strong> <?= htmlspecialchars($event['TariffReduit']) ?> DH</p>

        <form method="POST" action="purchase.php">
            <input type="hidden" name="eventId" value="<?= $eventId ?>">
            <label>Number of Standard Tickets:</label>
            <input type="number" name="qteNormal" min="0" required><br><br>
            <label>Number of Discounted Tickets:</label>
            <input type="number" name="qteReduit" min="0" required><br><br>
            <button type="submit">Purchase</button>
        </form>
    </div>
</body>
</html>

<?php
$host = "localhost";
$dbname = "farhaevents";
$username = "root";
$password = "";

try {
    $connect = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if search query is set
$searchTerm = isset($_POST['search']) ? trim($_POST['search']) : '';

// Modify query dynamically
$query = "SELECT edition.image, edition.timeEvent, edition.NumSalle, edition.dateEvent,
          evenement.eventTitle, evenement.eventId
          FROM edition
          JOIN evenement ON edition.eventId = evenement.eventId";

if (!empty($searchTerm)) {
    $query .= " WHERE evenement.eventTitle LIKE :searchTerm";
}


$stmt = $connect->prepare($query);

if (!empty($searchTerm)) {
    $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
}

$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Search</title>
    <style>
       *{
    margin: 0;
    padding: 0;
    font-family: Arial, Helvetica, sans-serif;
  }
  .wrapper {
    border: 1px solid black;
    background-color: #000;
    padding: 1%;
    width: 1100px;
    margin: 0 auto;
  }
  .menu ul {
    list-style-type: none;
  }
  .menu ul li {
    display: inline;
    padding: 2%;
  }
  .menu ul li a {
    text-decoration: none;
    font-weight: bold;
    color: rgba(144, 16, 16, 0.886);
  }
  .menu ul li a {
    color: rgba(144, 16, 16, 0.365);
  }
  .texte-center {
    text-align: center;
  }
  .main-content {
    background-color: black;
    padding: 1%;
  }
  body {
    FOnt-family: Arial, sans-serif;
    background-image: url(RED.jpg) !important;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
  }
  
  .container {
    
    width: 100%;
    max-width: 900px;
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  .card {
    display: flex;
    flex-direction: row;
    background: white;
    width: 100%;
    height: 300px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    transition: transform 0.3s ease-in-out;
  }
  .card:hover {
    transform: scale(1.03);
  }
  .card img {
    width: 40%;
    height: 100%;
    object-fit: cover;
  }
  .card-content {
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    width: 60%;
    height: 100%;
  }
  .card-content p {
    margin: 5px 0;
    font-size: 16px;
    color: #444;
  }
  .card-content form {
    margin-top: 10px;
  }
  .card-content input[type="submit"] {
    background : #421412 !important;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.3s ease;
  }
  .card-content input[type="submit"]:hover {
    background: #210809 !important;
  }
  /* Style for the search bar */
  .search-bar {
    margin: 20px;
    display: flex;
    justify-content: center;
  }
  .search-bar input[type="text"] {
    padding: 10px;
    font-size: 16px;
    width: 300px;
    margin-right: 10px;
  }
  .search-bar input[type="submit"] {
    background: #421412;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  .search-bar input[type="submit"]:hover {
    background:#210809;
  }
  .video-container {
width: 400px; /* حجم الحاوية */
margin: 20px auto; /* توسيط الفيديو */
background-color: #000; /* لون الخلفية */
border-radius: 10px; /* زوايا مستديرة */
box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* ظل خفيف */
}

video {
width: 100%; /* الفيديو يأخذ عرض الحاوية فقط */
height: 300px; /* يحافظ على الأبعاد */
border-radius: 8px; /* زوايا مستديرة */
background-color: #000; /* لون الخلفية */
}

    </style>
</head>
<body>
    <div class="search-bar">
        <form method="post">
            <input type="text" name="search" value="<?= htmlspecialchars($searchTerm) ?>" placeholder="Search for an event">
            <input type="submit" value="Search">
        </form>
    </div>

    <div class="container">
        <?php if (empty($rows)): ?>
            <p style="text-align:center;">No events found.</p>
        <?php else: ?>
            <?php foreach ($rows as $row): ?>
                <div class="card">
                    <img src="images/<?= htmlspecialchars($row['image']) ?>" alt="Event Image" width="100">
                    <p><strong>Date:</strong> <?= htmlspecialchars($row['dateEvent']) ?></p>
                    <p><strong>Time:</strong> <?= htmlspecialchars($row['timeEvent']) ?></p>
                    <p><strong>Room:</strong> <?= htmlspecialchars($row['NumSalle']) ?></p>
                    <form method="post">
                        <input type="hidden" name="eventId" value="<?= htmlspecialchars($row['eventId']) ?>">
                        <input type="submit" name="buy" value="Buy Ticket">
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>

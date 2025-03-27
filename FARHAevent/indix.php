<?php
$host = "localhost";
$dbname = "FarhaEvents";
$username = "root";
$password = "";

try {
    $connect = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$searchTerm = '';
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
}

$query = "SELECT edition.image, edition.timeEvent, edition.NumSalle, edition.dateEvent,
evenement.eventTitle, evenement.eventId
FROM edition
JOIN evenement ON edition.eventId = evenement.eventId
WHERE evenement.eventTitle LIKE :searchTerm";

$stmt = $connect->prepare($query);
$stmt->bindValue(':searchTerm', '%' . $searchTerm . '%');
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
        * {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }
        .wrapper {
            border: 1px solid rgb(173, 36, 36);
            padding: 1%;
            width: 1100px;
            margin: 0 auto;
            background-color: white;
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
            color: rgb(84, 14, 14);
        }
        .texte-center {
            text-align: center;
        }
        .main-content {
            background-color: black;
            padding: 1%;
        }
        body {
            background-image: url(RED.jpg);
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
        background-color:black;
        width: 100%;
        height: 300px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        transition: transform 0.3s ease-in-out;
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
        color: white;
      }
      .card-content form {
        margin-top: 10px;
      }
      .card-content input[type="submit"] {
        background-color:rgb(84, 14, 14);
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background 0.3s ease;
      }
      .card-content input[type="submit"]:hover {
        background-color:rgb(35, 6, 6);
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
    background-color: rgb(84, 14, 14);
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
    margin-top:-70px;
  }

  .search-bar input[type="submit"]:hover {
    background-color: rgb(35, 6, 6);
  }
    </style>
</head>
<body>
    <div class="menu texte-center">
        <div class="wrapper">
            <ul>
    <li><a>Home</a></li>
    <li><a href="login.php">Login</a></li>
    <li><a href="register.html">Register</a></li>
</ul>
        </div>
    </div>

    <div class="search-bar">
        <form method="post">
            <input type="text" name="search" value="<?= htmlspecialchars($searchTerm) ?>" placeholder="Search for an event">
            <input type="submit" value="Search">
        </form>
    </div>

    <div class="container">
        <?php foreach ($rows as $row): ?>
            <div class="card">
                <img src="<?= $row['image'] ?>" alt="Event Image">
                <div class="card-content">
                    <p><strong>Title:</strong> <?= $row['eventTitle'] ?></p>
                    <p><strong>Date:</strong> <?= $row['dateEvent'] ?></p>
                    <p><strong>Time:</strong> <?= $row['timeEvent'] ?></p>
                    <p><strong>Room Number:</strong> <?= $row['NumSalle'] ?></p>
                    <form method="post">
                        <input type="hidden" name="dateEvent" value="<?= $row['dateEvent'] ?>">
                        <input type="hidden" name="timeEvent" value="<?= $row['timeEvent'] ?>">
                        <input type="hidden" name="eventId" value="<?= $row['eventId'] ?>">
                        <input type="hidden" name="NumSalle" value="<?= $row['NumSalle'] ?>">
                        <input type="hidden" name="image" value="<?= $row['image'] ?>">
                      
                    </form>
                </div>
            </div>
            <form method="GET" action="event_details.php" class="search-bar">
    <input type="hidden" name="eventId" value="<?= $row['eventId'] ?>">
    <input type="submit" value="J'achète">
</form>
        <?php endforeach; ?>
    </div>
</body>
</html>

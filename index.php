<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Calendar</title>
    <link rel="stylesheet" href="./css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div>
        <form method="post">
            <input type="submit" name="prev" value="Previous">
            <input type="submit" name="curr" value="Current">
            <input type="submit" name="next" value="Next">
        </form>
    </div>
    <table>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['prev'])) {
            $dateParam = htmlspecialchars($_GET["date"]);
            $dateArray = explode("-", $dateParam);
            $newMonth = $dateArray[1] - 1;
            if ($newMonth <= 0) {
                $newMonth = 12;
                $dateArray[0]--;
            }
            $newDate = $dateArray[0] . "-" . $newMonth . "-01";
            header("Location: http://localhost/TE4/kalenderbutmore?date=" . $newDate);
        } else if(isset($_POST['curr'])){
            $currentDate = date("Y-m-d");
            header("Location: http://localhost/TE4/kalenderbutmore?date=" . $currentDate);
        } else if (isset($_POST['next'])) {
            $dateParam = htmlspecialchars($_GET["date"]);
            $dateArray = explode("-", $dateParam);
            $newMonth = $dateArray[1] + 1;
            if ($newMonth >= 13) {
                $newMonth = 1;
                $dateArray[0]++;
            }
            $newDate = $dateArray[0] . "-" . $newMonth . "-01";
            header("Location: http://localhost/TE4/kalenderbutmore?date=" . $newDate);
        }
    }

    // Hämta namnsdagarna från den uppdaterade JSON-filen
    $namnsdagarJSON = file_get_contents('namnsdagar.json');
    $namnsdagarArray = json_decode($namnsdagarJSON);

    // Hämta månad och dag från URL-parametern
    $dateParam = htmlspecialchars($_GET["date"]);
    $dateArray = explode("-", $dateParam);
    $year = $dateArray[0];
    $month = $dateArray[1];
    $day = 1;
    $currentDate = date("Y-$month-$day");

    $dayCounter = 1;

    while ((strtotime($currentDate)) <= strtotime(date("Y-$month") . '-' . date('t', strtotime($currentDate)))) {
        $dayNum = date('j', strtotime($currentDate));
        $dayName = date('l', strtotime($currentDate));
        $weekNumber = date('W', strtotime($currentDate));
        $dayString = "$dayName $dayNum";
        $dayOfYear = date('z', strtotime($currentDate));
        $namnsdagar = isset($namnsdagarArray[$dayOfYear]) ? implode(', ', $namnsdagarArray[$dayOfYear]) : 'Ingen namnsdag';



        if ($dayName == "Sunday") {
            echo "<tr><td style='color:red;'>$dayString</td><td> $namnsdagar</td></tr>";
        } else if ($dayName == "Monday") {
            echo "<tr><td>$dayString Week $weekNumber</td><td> $namnsdagar</td></tr>";
        } else {
            echo "<tr><td>$dayString</td><td> $namnsdagar</td></tr>";
        }

        $currentDate = date("Y-m-d", strtotime("+1 day", strtotime($currentDate)));
        $dayCounter++;
       

    }
    $monthName = date("F", strtotime($dateArray[0] . "-" . $dateArray[1] . "-01"));

    $imagePath = "./img/" . strtolower($monthName) . ".jpg";

    echo "<img src='$imagePath' alt='$monthName'>";
    ?>
    </table>
</body>
</html>

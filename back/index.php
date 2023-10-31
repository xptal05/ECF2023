<?php
if (session_status() == PHP_SESSION_NONE) {
    include 'session.php';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

</head>

<body class="application-window">
    <?php

require_once "./config/db.php";
$pdo = connectToDatabase($dbConfig);
    function fetchDataDashbord()
    {

        try {
global $pdo;

            // Initialize an empty array to store the results
            $data = array();

            // Define an array of table names you want to fetch data from
            $tableNames = ['vehicles', 'messages', 'feedbacks', 'statuses'];

            foreach ($tableNames as $tableName) {
                // Generate a dynamic SQL query for each table
                $sql = "SELECT * FROM $tableName";
                $statement = $pdo->prepare($sql);

                if ($statement->execute()) {
                    // Store the results in the data array with the table name as the key
                    $data[$tableName] = $statement->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    echo 'Une erreur est survenue';
                }
            }
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
        return $data;
    }

    $data = fetchDataDashbord();
    $statusData = $data['statuses'];
    $feedbacks = $data['feedbacks'];

    // Initialize variables to calculate the sum and count of ratings
    $totalRating = 0;
    $count = 0;

    $statusMapping = [];
    foreach ($statusData as $status) {
        $statusMapping[$status["name"]] = $status["id_status"];
    }

    function countItemsAndRevenueByDateCriteria($items, $status, $dateCriteria, $count = true)
    {
        // Initialize variables for counting and summing revenue.
        $countResult = 0;
        $revenue = 0;

        // Current date for comparison.
        $currentDate = date('Y-m-d');

        foreach ($items as $item) {
            if ($item['status'] == $status) {
                if ($status == 2) {
                    $dateToCheck = date('Y-m-d', strtotime($item['created']));
                } else {
                    $dateToCheck = date('Y-m-d', strtotime($item['modified']));
                }

                // Check the selected date criteria and apply the logic.
                switch ($dateCriteria) {
                    case 'month':
                        if (date('Y-m', strtotime($dateToCheck)) == date('Y-m', strtotime($currentDate))) {
                            if ($count) {
                                $countResult++;
                            } else {
                                $revenue += $item['price'];
                            }
                        }
                        break;

                    case 'week':
                        $startOfWeek = date('Y-m-d', strtotime('last Monday', strtotime($currentDate)));
                        if ($dateToCheck >= $startOfWeek && $dateToCheck <= $currentDate) {
                            if ($count) {
                                $countResult++;
                            } else {
                                $revenue += $item['price'];
                            }
                        }
                        break;

                    case 'year':
                        $startOfYear = date('Y-01-01');
                        if ($dateToCheck >= $startOfYear && $dateToCheck <= $currentDate) {
                            if ($count) {
                                $countResult++;
                            } else {
                                $revenue += $item['price'];
                            }
                        }
                        break;

                    case 'total':
                        if ($count) {
                            $countResult++;
                        } else {
                            $revenue += $item['price'];
                        }
                        break;
                }
            }
        }

        return $count ? $countResult : number_format($revenue, 0, '.', ' ') . ' EUR';
    }

    function itemCountAllStatus($items, $statusMapping, $period)
    {
        $allStatusesCount = 0;
        foreach ($statusMapping as $statusName => $statusId) {
            $count = countItemsAndRevenueByDateCriteria($items, $statusId, $period, true);
            $allStatusesCount += $count;
        }
        return $allStatusesCount;
    }

    // Loop through the feedbacks and calculate the sum and count of ratings
    foreach ($feedbacks as $feedback) {
        // Check if the status is not 7
        if ($feedback['status'] != 7) {
            $totalRating += $feedback['rating'];
            $count++;
        }
    }

    // Calculate the average rating
    $averageRating = ($count > 0) ? round(($totalRating / $count)) : 0;

    function calculateAverageNote($feedbacks, $dateCriteria)
    {
        // Initialize variables to calculate the sum and count of ratings
        $totalRating = 0;
        $count = 0;

        // Current date for comparison.
        $currentDate = date('Y-m-d');

        foreach ($feedbacks as $feedback) {
            // Check if the status is not 7 and apply date criteria.
            if ($feedback['status'] != 7) {
                $feedbackDate = date('Y-m-d', strtotime($feedback['created']));

                if ($dateCriteria === 'total' || (
                    ($dateCriteria === 'year' && date('Y', strtotime($feedbackDate)) == date('Y', strtotime($currentDate))) ||
                    ($dateCriteria === 'month' && date('Y-m', strtotime($feedbackDate)) == date('Y-m', strtotime($currentDate))) ||
                    ($dateCriteria === 'week' && $feedbackDate >= date('Y-m-d', strtotime('last Monday', strtotime($currentDate))) && $feedbackDate <= $currentDate)
                )) {
                    $totalRating += $feedback['rating'];
                    $count++;
                }
            }
        }

        // Calculate the average rating
        $averageRating = ($count > 0) ? round($totalRating / $count, 2) : 0;

        return $averageRating;
    }

    ?>
    <section class="nav">
        <?php include_once "./components/menu.php" ?>
    </section>
    <section class="application">
        <h1>Tableau de bord</h1>
        <div class="application-body">
            <div class="container span-4 dashboard">
                <div class="container-header">
                    <div class="container-header-icon">
                        <img src="./src/cars.svg">
                    </div>
                    <h2>Vehicles</h2>
                </div>
                <div class="container-content">
                    <div class="dashboard-total-container">
                        <div>
                            <h3>Total</h3>
                            <div><?php echo count($data['vehicles']); ?></div>
                        </div>
                        <div>
                            <h3>Total available</h3>
                            <div>
                                <?php
                                echo countItemsAndRevenueByDateCriteria($data['vehicles'], $statusMapping["Active"], 'total') + countItemsAndRevenueByDateCriteria($data['vehicles'], $statusMapping["New"], 'total');;
                                ?>
                            </div>
                        </div>
                    </div>
                    <table>
                        <thead>
                            <th></th>
                            <th>Vendu</th>
                            <th>Revenu</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Total</td>
                                <td><?php echo countItemsAndRevenueByDateCriteria($data['vehicles'], $statusMapping["Sold"], 'total'); ?></td>
                                <td><?php echo countItemsAndRevenueByDateCriteria($data['vehicles'], $statusMapping["Sold"], 'total', false); ?></td>
                            </tr>
                            <tr>
                                <td>Année</td>
                                <td><?php echo countItemsAndRevenueByDateCriteria($data['vehicles'], $statusMapping["Sold"], 'year'); ?></td>
                                <td><?php echo countItemsAndRevenueByDateCriteria($data['vehicles'], $statusMapping["Sold"], 'year', false); ?></td>
                            </tr>
                            <tr>
                                <td>Mois</td>
                                <td><?php echo countItemsAndRevenueByDateCriteria($data['vehicles'], $statusMapping["Sold"], 'month'); ?></td>
                                <td><?php echo countItemsAndRevenueByDateCriteria($data['vehicles'], $statusMapping["Sold"], 'month', false); ?></td>
                            </tr>
                            <tr>
                                <td>Semaine</td>
                                <td><?php echo countItemsAndRevenueByDateCriteria($data['vehicles'], $statusMapping["Sold"], 'week'); ?></td>
                                <td><?php echo countItemsAndRevenueByDateCriteria($data['vehicles'], $statusMapping["Sold"], 'week', false); ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <div>
                        <h3>Vehicles by status</h3>
                        <table>
                            <thead>
                                <th>Status</th>
                                <th>Count</th>
                            </thead>
                            <tbody>
                                <?php
                                // Loop through the status mapping to display each status and its count.
                                foreach ($statusMapping as $statusName => $statusId) {
                                    $count = countItemsAndRevenueByDateCriteria($data['vehicles'], $statusId, 'total');
                                ?>
                                    <tr>
                                        <td><?php echo $statusName; ?></td>
                                        <td><?php echo $count; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="dashboard-btn-container">
                        <a href="./vehicles.php" class="btn">GO TO VEHICLES</a>
                        <a href="./vehicle-form.php" class="btn">ADD NEW VEHICLE</a>
                    </div>
                </div>
            </div>
            <div class="container span-4 dashboard">
                <div class="container-header">
                    <div class="container-header-icon">
                        <img src="./src/messages.svg">
                    </div>
                    <h2>Messages</h2>
                </div>
                <div class="container-content">
                    <div class="dashboard-total-container">
                        <div>
                            <h3>Total</h3>
                            <div><?php echo count($data['messages']); ?></div>
                        </div>
                        <div>
                            <h3>Total nouveaux</h3>
                            <div><?php echo countItemsAndRevenueByDateCriteria($data['messages'], $statusMapping["New"], 'total'); ?></div>
                        </div>
                    </div>
                    <div>
                        <h3>Response time</h3>
                        <div><?php
                                function calculateResponseTime($messages, $statusMapping)
                                {
                                    $totalDifference = 0;

                                    foreach ($messages as $message) {
                                        if ($message['status'] == $statusMapping["Done"]) {
                                            $dateCreated = strtotime($message['created']);
                                            $dateModified = strtotime($message['modified']);

                                            // Calculate the time difference in seconds.
                                            $difference = $dateModified - $dateCreated;

                                            // Add the difference to the total.
                                            $totalDifference += $difference;
                                        }
                                    }

                                    // Convert the total difference to a human-readable format, e.g., hours, minutes, seconds.
                                    $hours = floor($totalDifference / 3600);
                                    $minutes = floor(($totalDifference % 3600) / 60);
                                    $seconds = $totalDifference % 60;

                                    return "$hours hours, $minutes minutes, $seconds seconds";
                                }
                                echo calculateResponseTime($data['messages'], $statusMapping);
                                ?></div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th>Recu</th>
                                <th>Répondu</th>
                                <th>Archivé</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Total</td>
                                <td><?php echo itemCountAllStatus($data['messages'], $statusMapping, 'total') ?></td>
                                <td><?php echo countItemsAndRevenueByDateCriteria($data['messages'], $statusMapping['Done'], 'total', true); ?></td>
                                <td><?php echo countItemsAndRevenueByDateCriteria($data['messages'], $statusMapping['Archived'], 'total', true); ?></td>
                            </tr>
                            <tr>
                                <td>Année</td>
                                <td><?php echo itemCountAllStatus($data['messages'], $statusMapping, 'year') ?></td>
                                <td><?php echo countItemsAndRevenueByDateCriteria($data['messages'], $statusMapping['Done'], 'year', true); ?></td>
                                <td><?php echo countItemsAndRevenueByDateCriteria($data['messages'], $statusMapping['Archived'], 'year', true); ?></td>
                            </tr>
                            <tr>
                                <td>Mois</td>
                                <td><?php echo itemCountAllStatus($data['messages'], $statusMapping, 'month') ?></td>
                                <td><?php echo countItemsAndRevenueByDateCriteria($data['messages'], $statusMapping['Done'], 'month', true); ?></td>
                                <td><?php echo countItemsAndRevenueByDateCriteria($data['messages'], $statusMapping['Archived'], 'month', true); ?></td>
                            </tr>
                            <tr>
                                <td>Semaine</td>
                                <td><?php echo itemCountAllStatus($data['messages'], $statusMapping, 'week') ?></td>
                                <td><?php echo countItemsAndRevenueByDateCriteria($data['messages'], $statusMapping['Done'], 'week', true); ?></td>
                                <td><?php echo countItemsAndRevenueByDateCriteria($data['messages'], $statusMapping['Archived'], 'week', true); ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="./messages.php" class="btn">GO TO MESSAGES</a>
                </div>

            </div>
            <div class="container span-4 dashboard">
                <div class="container-header">
                    <div class="container-header-icon">
                        <img src="./src/feedbacks.svg">
                    </div>
                    <h2>Témoignages</h2>
                </div>
                <div class="container-content">
                    <div class="dashboard-total-container">
                        <div>
                            <h3>Total</h3>
                            <div><?php echo count($data['feedbacks']); ?></div>
                        </div>
                        <div>
                            <h3>Total nouveaux</h3>
                            <div><?php echo countItemsAndRevenueByDateCriteria($data['feedbacks'], $statusMapping["New"], 'total'); ?></div>
                        </div>
                    </div>
                    <div>
                        <h3>Average Note</h3>
                        <div><?php echo $averageRating; ?></div>
                    </div>
                    <table>
                        <thead>
                            <th></th>
                            <th>Recieved</th>
                            <th>Approved</th>
                            <th>Average Note</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Total</td>
                                <td><?php echo itemCountAllStatus($data['feedbacks'], $statusMapping, 'total') ?></td>
                                <td><?php echo countItemsAndRevenueByDateCriteria($data['feedbacks'], $statusMapping['Active'], 'total', true); ?></td>
                                <td><?php echo calculateAverageNote($data['feedbacks'], 'total'); ?></td>
                            </tr>
                            <tr>
                                <td>Année</td>
                                <td><?php echo itemCountAllStatus($data['feedbacks'], $statusMapping, 'year') ?></td>
                                <td><?php echo countItemsAndRevenueByDateCriteria($data['feedbacks'], $statusMapping['Active'], 'year', true); ?></td>
                                <td><?php echo calculateAverageNote($data['feedbacks'], 'year'); ?></td>
                            </tr>
                            <tr>
                                <td>Mois</td>
                                <td><?php echo itemCountAllStatus($data['feedbacks'], $statusMapping, 'month') ?></td>
                                <td><?php echo countItemsAndRevenueByDateCriteria($data['feedbacks'], $statusMapping['Active'], 'month', true); ?></td>
                                <td><?php echo calculateAverageNote($data['feedbacks'], 'month'); ?></td>
                            </tr>
                            <tr>
                                <td>Semaine</td>
                                <td><?php echo itemCountAllStatus($data['feedbacks'], $statusMapping, 'week') ?></td>
                                <td><?php echo countItemsAndRevenueByDateCriteria($data['feedbacks'], $statusMapping['Active'], 'week', true); ?></td>
                                <td><?php echo calculateAverageNote($data['feedbacks'], 'week'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="./feedback.php" class="btn">GO TO TEMOIGNAGES</a>
                </div>
            </div>
        </div>
    </section>
    <script src="script-dashboard.js"></script>
    <script src="./components/menu.js"></script>
</body>

</html>
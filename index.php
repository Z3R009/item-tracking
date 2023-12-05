<?php
include 'DBConnection.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Page</title>
    <style>
        body {
            background-color: #EAD7BB;
            background-image: url('img/bg4.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            overflow-y: none;
            overflow: hidden;
        }

        .header {
            /* background-color: #BCA37F;
            color: #fff;
            padding: 20px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            backdrop-filter: blur(100px); */
            color: #fff;
            padding: 20px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: right;
            margin-bottom: 20px;
            margin-left: none;
        }

        .header img {
            max-width: 50px;
        }

        .header nav button {
            background-color: #fff;
            color: #BCA37F;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
            margin-left: 10px;
        }

        .forms {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 10px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 20px;
            text-align: center;
        }

        button[type="submit"] {
            background-color: #BCA37F;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
            width: 100%;
        }

        .card {
            display: none;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid#31304D;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #31304D;
            color: white;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tbody td {
            word-break: break-word;
        }

        .no-data {
            text-align: center;
            margin-top: 20px;
            color: #BCA37F;
        }

        .search-container {
            position: relative;
            width: 100%;
            margin-bottom: 10px;
        }

        input[type="text"] {
            padding-right: 40px;
            /* Add space on the right for the button */
        }

        button[type="submit"] {
            position: absolute;

            right: 0;
            top: 0;
            bottom: 0;
            width: 40px;
            background-color: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
        }

        /* Styling the arrow shape */
        button[type="submit"]:after {
            content: '';
            position: absolute;
            top: 40%;
            left: 60px;
            right: 0;
            /* Adjust distance from the right */
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 10px 0 10px 15px;
            /* Adjust arrow size */
            border-color: transparent transparent transparent #007BFF;
            /* Adjust arrow color */
        }

        a {
            text-decoration: none;
            color: black;
            font-size: 30px;
            display: flex;
            align-items: center; /* Center content vertically */
            width: 50px; /* Set button width */
            height: 50px; /* Set button height */
            padding-left: 1000px;
        }

        .drawer {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #BCA37F;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .drawer a {
            padding: 10px 20px;
            text-decoration: none;
            font-size: 20px;
            color: #fff;
            display: block;
            transition: 0.3s;
        }

        .drawer a:hover {
            background-color: #BCA37F;
            color: #fff;
        }

        .drawer .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 30px;
            margin-left: 50px;
        }
        .header .open-btn {
        margin-left: 20px;
    }

        /* Responsive code ni sya */

@media only screen and (max-width: 600px) {
    .header {
        flex-direction: column;
        text-align: center;
    }

    .header nav {
        margin-top: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
    }

    .header nav button {
        margin-left: 10px;
        margin-top: 10px;
        margin-right: 10px;
    }

    nav button {
        margin: 10px 0;
        margin-left: 10px;
    }
}
    </style>
</head>

</head>

<body>

<div class="drawer" id="drawer">
        <a href="search.php" class="close-btn">&times;</a>
        <a href="login.php">Login</a>
    </div>
    <div class="header">
        <!-- Button to open the drawer -->
        <button class="open-btn" onclick="openDrawer()">&#9776;</button>
    </div>

    <div class="forms">
        <form method="post">
            <div class="form-group">
                <div class="search-container">
                    <input type="text" name="search" id="search" placeholder="Search Tracking Number">
                    <button type="submit" name="submit"></button>
                </div>
            </div>
        </form>
    </div>
    <div class="card">
        <?php
        if (isset($_POST['submit'])) {
            $search = $_POST['search'];

            $sql = "SELECT * FROM history WHERE tracking_number = '$search' order by date DESC, time DESC";
            $result = mysqli_query($connection, $sql);

            echo '<table border="1" style="border: 1px solid #BCA37F;
        padding: 10px;
        text-align: center;">';
            if ($result && mysqli_num_rows($result) > 0) {
                echo '
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>';
                while ($row = mysqli_fetch_assoc($result)) {
                    // Format the date to display in words
                    $formatted_date = date('F j, Y', strtotime($row['date']));
                    // Format the time to display in 12-hour format
                    $formatted_time = date('h:i A', strtotime($row['time']));

                    echo '
                    <tr>
                        <td class="date-column">' . $formatted_date . '</td>
                        <td class="time-column">' . $formatted_time . '</td>
                        <td>' . $row['current_location'] . '</td>
                    </tr>';
                }
                echo '</tbody>';
            } else {
                echo '<tr><td colspan="3" class="no-data">Data not found</td></tr>';
            }
            echo '</table>';

            echo '<style>.card { display: block; }</style>';
        }
        ?>
    </div>
    <script>
        // JavaScript function to open the drawer
        function openDrawer() {
            document.getElementById("drawer").style.width = "250px";
        }
    </script>
</body>

</html>

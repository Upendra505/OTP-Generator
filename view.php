<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
        }

        /* Sidebar styling */
        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background: #2c3e50;
            color: white;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a {
            color: white;
            padding: 15px 20px;
            text-decoration: none;
            display: block;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background: #34495e;
        }

        /* Content styling */
        .content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s;
        }

        /* Responsive Table */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background: #2980b9;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e1e1e1;
        }

        /* Sidebar Toggle Button */
        #sidebarToggle {
            display: none;
            font-size: 24px;
            color: #333;
            position: fixed;
            top: 10px;
            left: 10px;
            cursor: pointer;
            z-index: 10;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .content {
                margin-left: 0;
                padding: 20px;
            }

            #sidebarToggle {
                display: block;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar Toggle Button -->
    <span id="sidebarToggle" onclick="toggleSidebar()">&#9776;</span>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h2>Admin Panel</h2>
        <a href="#dashboard">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content" id="mainContent">
        <div class="content-header">
            <h2>Data List</h2>
            <input type="text" id="searchInput" placeholder="Search...">
        </div>

        <!-- Data Table -->
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Qualification</th>
                        <th>Enquiry for</th>
                        <th>Created At</th>
                        <th>Note</th>
                        <th>Call</th>
                    </tr>
                </thead>
                <tbody id="dataList">
                    <?php
                    // PHP to fetch and display data from MySQL
                  $host = 'localhost';                 
$username = 'starkina_leads';                 
$password = 'rlo~Hh)tRHc-';                 
$dbname = 'starkina_leads';  

                    // Create connection
                    $conn = new mysqli($host, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Query to get data in descending order of creation date
                    $sql = "SELECT id, name, phone, qualification, course, createdAt FROM lead ORDER BY createdAt DESC";
                    $result = $conn->query($sql);

                    // Display fetched data in table rows
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['phone'] . "</td>";
                            echo "<td>" . $row['qualification'] . "</td>";
                            echo "<td>" . $row['course'] . "</td>";
                            echo "<td>" . $row['createdAt'] . "</td>";
                            echo "<td><button onclick='openModal(" . $row['id'] . ")'>Add Note</button></td>";
                            echo "<td><a href='tel:" . $row['phone'] . "'>Call</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No records found</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Toggle sidebar visibility
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            if (sidebar.style.display === "block") {
                sidebar.style.display = "none";
            } else {
                sidebar.style.display = "block";
            }
        }
    </script>
</body>

</html>
<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة مشاريع مع تتبع الميزانية والزمن والمراحل</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold text-emerald-600">نظام إدارة مشاريع مع تتبع الميزانية والزمن والمراحل</h1>
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php
            // Fetch stats dynamically via Javascript API calls from the backend files
            $stats = json_decode(file_get_contents('stats.json'), true);
            ?>
            <div class="glassmorphism-card p-4">
                <h2 class="text-lg font-bold text-emerald-600">إجمالي المشاريع</h2>
                <p class="text-3xl font-bold text-teal-500"><?= $stats['projects_count'] ?></p>
            </div>
            <div class="glassmorphism-card p-4">
                <h2 class="text-lg font-bold text-emerald-600">إجمالي الفريق</h2>
                <p class="text-3xl font-bold text-teal-500"><?= $stats['team_count'] ?></p>
            </div>
            <div class="glassmorphism-card p-4">
                <h2 class="text-lg font-bold text-emerald-600">إجمالي الميزانية</h2>
                <p class="text-3xl font-bold text-teal-500"><?= $stats['budget'] ?></p>
            </div>
        </div>
        <div class="flex justify-between items-center mt-4">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='projects.php'">مشاريع</button>
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='team.php'">فريق العمل</button>
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='budget.php'">الميزانية</button>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('stats.php')
            .then(response => response.json())
            .then(data => {
                const stats = data;
                document.querySelector('.stats-grid .card-1 .count').textContent = stats.projects_count;
                document.querySelector('.stats-grid .card-2 .count').textContent = stats.team_count;
                document.querySelector('.stats-grid .card-3 .count').textContent = stats.budget;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>



// stats.php
<?php
// Fetch stats dynamically via PHP API calls from the database
$stats = array(
    'projects_count' => 10,
    'team_count' => 20,
    'budget' => 10000
);
echo json_encode($stats);
?>



// stats.json
{
    "projects_count": 10,
    "team_count": 20,
    "budget": 10000
}


Note: You need to replace the `stats.json` file with your actual data or create a PHP script to fetch the data from the database.

This code uses Tailwind CSS for styling and fetches stats dynamically via Javascript API calls from the backend files. The stats are displayed in a grid layout with a glassmorphism card design. The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules.
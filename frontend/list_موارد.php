**list_موارد.php**

<?php
// Session validation
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
    <title>موارد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span class="ml-4">مرحباً, <?= $_SESSION['username'] ?></span>
        <a href="logout.php" class="ml-4">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">موارد</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_موارد.php'">إضافة عنصر جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم العنصر</th>
                    <th>وصف العنصر</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const recordsContainer = document.getElementById('records');

        // Load records on page load
        loadRecords();

        // Search functionality
        function searchRecords() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/موارد.php', {
                    method: 'GET',
                    params: { search: searchQuery }
                })
                .then(response => response.json())
                .then(data => {
                    const records = data.records;
                    const html = records.map(record => `
                        <tr>
                            <td>${record.name}</td>
                            <td>${record.description}</td>
                            <td>
                                <a href="edit_موارد.php?id=${record.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                                <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        </tr>
                    `).join('');
                    recordsContainer.innerHTML = html;
                });
            } else {
                loadRecords();
            }
        }

        // Load records from backend
        function loadRecords() {
            fetch('../backend/موارد.php')
            .then(response => response.json())
            .then(data => {
                const records = data.records;
                const html = records.map(record => `
                    <tr>
                        <td>${record.name}</td>
                        <td>${record.description}</td>
                        <td>
                            <a href="edit_موارد.php?id=${record.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    </tr>
                `).join('');
                recordsContainer.innerHTML = html;
            });
        }

        // Delete record
        function deleteRecord(id) {
            if (confirm('هل تريد حذف العنصر؟')) {
                fetch('../backend/موارد.php', {
                    method: 'DELETE',
                    params: { id }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadRecords();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                });
            }
        }
    </script>
</body>
</html>

**backend/موارد.php**

<?php
// Database connection
$db = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');

// Search functionality
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $stmt = $db->prepare('SELECT * FROM records WHERE name LIKE :search OR description LIKE :search');
    $stmt->bindParam(':search', '%' . $searchQuery . '%');
    $stmt->execute();
    $records = $stmt->fetchAll();
} else {
    $stmt = $db->query('SELECT * FROM records');
    $records = $stmt->fetchAll();
}

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $db->prepare('DELETE FROM records WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

// Output records
echo json_encode(['records' => $records]);
**list_فريق-العمل.php**

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
    <title>فريق العمل</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .emerald-600 {
            color: #008E77;
        }
        .teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4">
        <header class="bg-white shadow-md p-4 mb-4">
            <nav class="flex justify-between">
                <a href="index.php" class="text-lg font-bold">الرئيسية</a>
                <div class="flex items-center">
                    <p class="mr-2">مرحباً, <?= $_SESSION['username'] ?></p>
                    <a href="logout.php" class="text-red-600 hover:text-red-800">تسجيل خروج</a>
                </div>
            </nav>
        </header>
        <div class="bg-white shadow-md p-4 rounded-md">
            <h2 class="text-lg font-bold mb-4">فريق العمل</h2>
            <div class="flex justify-between mb-4">
                <a href="create_فريق-العمل.php" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
                <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600" placeholder="بحث...">
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">اسم</th>
                        <th class="px-4 py-2">وظيفة</th>
                        <th class="px-4 py-2">إجراءات</th>
                    </tr>
                </thead>
                <tbody id="records">
                    <!-- Records will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const recordsTable = document.getElementById('records');

        searchInput.addEventListener('input', async () => {
            const searchQuery = searchInput.value.trim();
            const response = await fetch(`../backend/فريق-العمل.php?search=${searchQuery}`);
            const data = await response.json();
            recordsTable.innerHTML = '';
            data.forEach((record) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-4 py-2">${record.اسم}</td>
                    <td class="px-4 py-2">${record.وظيفة}</td>
                    <td class="px-4 py-2">
                        <a href="edit_فريق-العمل.php?id=${record.id}" class="text-teal-500 hover:text-teal-800">تعديل</a>
                        <button class="text-red-600 hover:text-red-800" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                recordsTable.appendChild(row);
            });
        });

        async function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                const response = await fetch(`../backend/فريق-العمل.php?delete&id=${id}`, { method: 'DELETE' });
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('حدث خطأ أثناء حذف السجل');
                }
            }
        }

        async function loadRecords() {
            const response = await fetch('../backend/فريق-العمل.php');
            const data = await response.json();
            recordsTable.innerHTML = '';
            data.forEach((record) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-4 py-2">${record.اسم}</td>
                    <td class="px-4 py-2">${record.وظيفة}</td>
                    <td class="px-4 py-2">
                        <a href="edit_فريق-العمل.php?id=${record.id}" class="text-teal-500 hover:text-teal-800">تعديل</a>
                        <button class="text-red-600 hover:text-red-800" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                recordsTable.appendChild(row);
            });
        }

        loadRecords();
    </script>
</body>
</html>


**backend/فريق-العمل.php**

<?php
// Database connection
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

if (!$conn) {
    die('Could not connect: ' . mysqli_error($conn));
}

// Search query
$searchQuery = $_GET['search'] ?? '';

// Fetch records
$query = "SELECT * FROM فريق_العمل";
if ($searchQuery) {
    $query .= " WHERE اسم LIKE '%$searchQuery%'";
}
$result = mysqli_query($conn, $query);

// Fetch data
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Output data
header('Content-Type: application/json');
echo json_encode($data);

// Delete record
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM فريق_العمل WHERE id = '$id'";
    mysqli_query($conn, $query);
    echo 'Record deleted successfully';
}

// Close connection
mysqli_close($conn);
?>
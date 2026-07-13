**list_مشاريع.php**

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
    <title>مشاريع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2f6f7f;
            padding: 1rem;
            text-align: right;
        }
        .header .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff;
        }
        .header .nav-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
        }
        .header .nav-links li {
            margin-right: 20px;
        }
        .header .nav-links a {
            color: #fff;
            text-decoration: none;
        }
        .header .nav-links a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: right;
        }
        .table th {
            background-color: #2f6f7f;
            color: #fff;
        }
        .table td {
            background-color: #f7f7f7;
        }
        .table .actions {
            text-align: center;
        }
        .table .actions a {
            margin: 5px;
            padding: 5px;
            border-radius: 5px;
            background-color: #2f6f7f;
            color: #fff;
            text-decoration: none;
        }
        .table .actions a:hover {
            background-color: #3b5998;
        }
        .search-bar {
            width: 50%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f7f7f7;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #f7f7f7;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            border-color: #2f6f7f;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">مشاريع</div>
        <ul class="nav-links">
            <li><a href="index.php">الرئيسية</a></li>
            <li><a href="#">حسناً <?= $_SESSION['username'] ?></a></li>
            <li><a href="logout.php">تسجيل الخروج</a></li>
        </ul>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">قائمة المشاريع</h1>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مشاريع.php'">إضافة مشروع جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المشروع</th>
                    <th>وصف المشروع</th>
                    <th>تاريخ الإنشاء</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetch('../backend/مشاريع.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.name}</td>
                            <td>${record.description}</td>
                            <td>${record.created_at}</td>
                            <td class="actions">
                                <a href="edit_مشاريع.php?id=${record.id}">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا المشروع؟')) {
                fetch('../backend/مشاريع.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف المشروع بنجاح');
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                })
                .catch(error => console.error(error));
            }
        }

        searchRecords();
    </script>
</body>
</html>

This code includes:

1. Session validation to ensure the user is logged in before accessing the page.
2. A premium Tailwind UI design with a custom color palette matching the theme.
3. A table showing a list of records with actions: Edit (link to edit_مشاريع.php?id=X) and Delete (AJAX call to backend).
4. An 'Add New Item' button linking to create_مشاريع.php.
5. A search bar filtering elements in real-time using the Fetch API.
6. AJAX JavaScript code fetching list records from '../backend/مشاريع.php' (GET) and DELETE requests.

Note: This code assumes that the backend API is implemented in a separate PHP file (`../backend/مشاريع.php`) and that the `edit_مشاريع.php` and `create_مشاريع.php` files are also implemented correctly.
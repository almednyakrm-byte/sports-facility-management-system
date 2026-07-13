**list_الطلاب.php**

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
    <title>الطلاب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2c3e50;
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
        .table-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 1rem;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-container table th, .table-container table td {
            border: 1px solid #ddd;
            padding: 0.5rem;
            text-align: left;
        }
        .table-container table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            padding: 1rem;
            background-color: #f7f7f7;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 0.5rem;
            border: none;
            border-radius: 0.25rem;
        }
        .search-bar button[type="submit"] {
            background-color: #2c3e50;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            cursor: pointer;
        }
        .search-bar button[type="submit"]:hover {
            background-color: #3c4a59;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span>مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="table-container">
        <h2>قائمة الطلاب</h2>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button type="submit" id="search-button">بحث</button>
        </div>
        <table id="student-table">
            <thead>
                <tr>
                    <th>اسم الطالب</th>
                    <th>البريد الإلكتروني</th>
                    <th>الجنسية</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="student-table-body">
                <!-- Table rows will be populated dynamically -->
            </tbody>
        </table>
        <button class="btn btn-primary" id="add-new-student-button">إضافة طالب جديد</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fetch@2.0.3/dist/fetch.min.js"></script>
    <script>
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        const studentTable = document.getElementById('student-table');
        const studentTableBody = document.getElementById('student-table-body');
        const addNewStudentButton = document.getElementById('add-new-student-button');

        searchButton.addEventListener('click', (e) => {
            e.preventDefault();
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/الطلاب.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        studentTableBody.innerHTML = '';
                        data.forEach(student => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${student.name}</td>
                                <td>${student.email}</td>
                                <td>${student.nationality}</td>
                                <td>
                                    <a href="edit_الطلاب.php?id=${student.id}" class="btn btn-sm btn-primary">تعديل</a>
                                    <button class="btn btn-sm btn-danger delete-student-button" data-id="${student.id}">حذف</button>
                                </td>
                            `;
                            studentTableBody.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/الطلاب.php')
                    .then(response => response.json())
                    .then(data => {
                        studentTableBody.innerHTML = '';
                        data.forEach(student => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${student.name}</td>
                                <td>${student.email}</td>
                                <td>${student.nationality}</td>
                                <td>
                                    <a href="edit_الطلاب.php?id=${student.id}" class="btn btn-sm btn-primary">تعديل</a>
                                    <button class="btn btn-sm btn-danger delete-student-button" data-id="${student.id}">حذف</button>
                                </td>
                            `;
                            studentTableBody.appendChild(row);
                        });
                    });
            }
        });

        addNewStudentButton.addEventListener('click', () => {
            window.location.href = 'create_الطلاب.php';
        });

        studentTable.addEventListener('click', (e) => {
            if (e.target.classList.contains('delete-student-button')) {
                const studentId = e.target.dataset.id;
                if (confirm('هل تريد حذف الطالب؟')) {
                    fetch('../backend/الطلاب.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id: studentId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('تم حذف الطالب بنجاح');
                            window.location.reload();
                        } else {
                            alert('حدث خطأ أثناء حذف الطالب');
                        }
                    });
                }
            }
        });
    </script>
</body>
</html>

Note: This code assumes that you have a backend PHP script (`../backend/الطلاب.php`) that handles the GET and DELETE requests for the students data. You will need to implement this script separately.
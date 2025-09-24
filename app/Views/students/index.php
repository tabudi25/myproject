<!DOCTYPE html>
<html>
<head>
    <title>Students List</title>
</head>
<body>
    <h1>Students</h1>
    <a href="/student/create">Add Student</a>
    <br><br>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Profile Picture</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php if (!empty($students)): ?>
            <?php foreach ($students as $student): ?>
            <tr>
                <td><?= $student['id'] ?></td>
                <td>
                    <?php if ($student['profile_picture']): ?>
                        <img src="/uploads/<?= $student['profile_picture'] ?>" width="50">
                    <?php else: ?>
                        No Image
                    <?php endif; ?>
                </td>
                <td><?= esc($student['name']) ?></td>
                <td><?= esc($student['email']) ?></td>
                <td>
                    <a href="/student/delete/<?= $student['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">No students found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
</head>
<body>
    <h1>Add Student</h1>
    <form action="/student/store" method="post" enctype="multipart/form-data">
        <p>
            <label>Name:</label>
            <input type="text" name="name" required>
        </p>
        <p>
            <label>Email:</label>
            <input type="email" name="email" required>
        </p>
        <p>
            <label>Profile Picture:</label>
            <input type="file" name="profile_picture">
        </p>
        <button type="submit">Save</button>
    </form>
    <br>
    <a href="/student">Back to List</a>
</body>
</html>

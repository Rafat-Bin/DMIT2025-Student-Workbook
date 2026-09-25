<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (
        isset($_POST["form_id"]) &&
        $_POST["form_id"] == "student"
    ) {

        $student_name = $_POST["student_name"];

        echo "<p>Student submitted: {$student_name}</p>";
    }


    if (
        isset($_POST["form_id"]) &&
        $_POST["form_id"] == "course"
    ) {

        $course_name = $_POST["course_name"];

        echo "<p>Course submitted: {$course_name}</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Exercise 1</title>
</head>

<body>

    <h1>Student and Course</h1>

    <h2>Student</h2>

    <form method="POST">

        <input
            type="hidden"
            name="form_id"
            value="student"
        >

        <label for="student_name">
            Student Name:
        </label>

        <input
            type="text"
            id="student_name"
            name="student_name"
            required
        >

        <button type="submit">
            Submit Student
        </button>

    </form>


    <h2>Course</h2>

    <form method="POST">

        <input
            type="hidden"
            name="form_id"
            value="course"
        >

        <label for="course_name">
            Course Name:
        </label>

        <input
            type="text"
            id="course_name"
            name="course_name"
            required
        >

        <button type="submit">
            Submit Course
        </button>

    </form>

</body>

</html>
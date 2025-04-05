# 📝 Online Examination System

An Online Examination System built with **PHP**, **SQL Server**, **HTML**, and **CSS**, designed for academic use. It supports multiple user roles (Admin, Teacher, Student), enabling role-based functionalities such as exam creation, participation, and evaluation.

## 📌 Features

- 🔐 Secure login system with user role differentiation
- 🧑‍🏫 Teacher dashboard to create and manage exams/questions
- 🧑‍🎓 Student panel to take exams and view results
- 🗂️ Admin panel for user and system management
- 📋 Exam types: Midterm, Final, Quiz
- ❓ Question types: Multiple Choice (MCQ), True/False
- ⏱️ Exam timer and automatic submission
- 📊 Result calculation and display
- 🛠️ Modular and maintainable codebase

---

## 📁 Project Structure

/online-exam-system/ │ ├── /admin/ # Admin dashboard ├── /teacher/ # Teacher dashboard and exam creation ├── /student/ # Student panel and exam interface ├── /includes/ # Reusable PHP components (DB, auth, etc.) ├── /assets/ # CSS, JS, images ├── db/ # Database schema and sample data ├── index.php # Landing/login page └── README.md # This file

yaml
Copy
Edit

---

## ⚙️ Requirements

Before running the project, make sure you have:

- PHP >= 7.4
- SQL Server (or use Azure SQL Database)
- A web server (e.g., XAMPP, WAMP, Laragon with SQLSRV extension)
- SQLSRV PHP extension (for connecting PHP with SQL Server)
- Composer (optional for dependency management)

---

## 🛠️ Setup Instructions

### 1. Clone the Repository

``bash
git clone https://github.com/yourusername/online-exam-system.git
cd online-exam-system

2. Configure the Database
Create a new database in SQL Server (e.g., OnlineExamDB).

Import the SQL schema file from the /db/ directory. This includes tables for:

Users

Exams

Questions

Answers

3. Update Database Connection
Open the database connection file (usually found in /includes/db.php or similar) and configure your SQL Server credentials:

php
Copy
Edit
$serverName = "localhost"; 
$connectionOptions = array(
    "Database" => "OnlineExamDB",
    "Uid" => "your_username",
    "PWD" => "your_password"
);
$conn = sqlsrv_connect($serverName, $connectionOptions);
4. Start the Server
If using XAMPP or WAMP:

Place the project folder inside htdocs (XAMPP) or www (WAMP).

Start Apache and SQL Server services.

Access the system in your browser at:

perl
Copy
Edit
http://localhost/online-exam-system/
👥 Default User Roles
You can use the following default accounts to log in (based on the seeded database):

Role	Email	Password
Admin	admin@example.com	admin123
Teacher	teacher@example.com	teacher123
Student	student@example.com	student123
(Modify these in the DB if needed)

🧠 System Overview
Admin: Manages users, controls overall system.

Teacher: Creates exams, adds questions, views submissions.

Student: Takes exams, views scores, checks results.

Refer to the Documentation PDF for full ER diagrams, class structure, and system flow.

🚧 Future Improvements
Add real-time proctoring (camera/audio monitoring)

Use Laravel or other modern frameworks

Add file upload/image-based questions

Improve UI with a responsive framework like Bootstrap/Tailwind

📝 License
This project is intended for educational purposes. Feel free to fork and modify it for learning or internal use.

🙌 Acknowledgements
Developed as part of an academic project for demonstrating full-stack web development using PHP and SQL Server.









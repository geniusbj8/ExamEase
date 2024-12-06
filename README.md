# Exam Ease – Exam Management System (LMS)

Exam Ease is a comprehensive Learning Management System (LMS) designed to streamline course management, exam administration, and student assessments in an educational environment. The system provides role-based access for administrators, instructors, and students, ensuring efficient workflows and secure interactions.

---

## Features

### **User Roles and Access Control**
- **Admins**: Manage users, courses, exams, and generate performance reports.  
- **Instructors**: Create and manage courses and exams, and track student progress.  
- **Students**: Enroll in courses, take exams, and view detailed performance reports.

### **Core Functionalities**
1. **Course Management**:
   - Create, modify, and manage courses.
   - Assign exams to courses and manage schedules.
2. **Exam Creation and Management**:
   - Create exams with support for multiple question types:
     - Multiple-choice
     - True/False
     - Short-answer
   - Define exam durations and descriptions.
3. **Question Management**:
   - Store questions in a centralized question bank.
   - Flag correct answers for automated grading.
4. **Student Enrollment**:
   - Track student enrollments and link them to relevant courses and exams.
5. **Grading and Reporting**:
   - Automatic grading for objective questions.
   - Generate detailed reports summarizing student performance and progress.
6. **Secure Authentication**:
   - Passwords are hashed using SHA-256 for secure logins.
7. **Role-Based Permissions**:
   - Ensure data and features are accessible based on user roles.

---

## Database Design

### **Schema Overview**
The database schema ensures optimal data relationships and scalability. Key entities include users, roles, courses, exams, questions, answers, and student-related data.

### **Key Tables and Relationships**
- **Roles Table**: Defines user roles (Admin, Instructor, Student).  
- **Users Table**: Stores user details and links them to their roles.  
- **Courses Table**: Stores course information and links it to exams.  
- **Exams Table**: Stores exam details and links to associated courses.  
- **Questions Table**: Tracks exam questions and their types.  
- **Answers Table**: Tracks possible answers and correct ones for grading.  
- **Student Answers Table**: Logs students’ responses.  
- **Reports Table**: Stores performance metrics.  
- **Enrollments Table**: Tracks student enrollments in courses.  
- **Exam Grades Table**: Tracks students’ final scores for exams.

### **Normalization**
- **Third Normal Form (3NF)**: Ensures reduced redundancy and improved data integrity.  
- Strong relationships between entities are enforced via foreign keys.

---

## Technologies and Tools

### **Backend**
- **Language**: PHP  
- **Database**: MySQL / MariaDB (via XAMPP)  
- **Authentication**: SHA-256 hashing for secure login  

### **Frontend**
- **Technologies**: HTML, CSS, JavaScript  
- **Design**: Responsive UI for seamless interaction across devices  

### **Reporting**
- **SQL Queries**: Summarize performance data for students, instructors, and admins.  

---

## Installation Instructions

1. **Prerequisites**:
   - XAMPP (or any similar local server environment)
   - Web browser (e.g., Chrome, Firefox)
   - Text editor/IDE (e.g., VS Code, Sublime Text)

2. **Clone the Repository**:
   ```bash
   git clone https://github.com/geniusbj8/ExamEase.git
   ```

3. **Set Up the Database**:
   - Import the provided SQL file into your MySQL database:
     - Open `phpMyAdmin` in your browser.
     - Create a new database (e.g., `exam_ease`).
     - Import the `exam_ease.sql` file from the project.

4. **Configure the Backend**:
   - Update the `config.php` file with your database credentials:
     ```php
     $db_host = 'localhost';
     $db_user = 'your_username';
     $db_password = 'your_password';
     $db_name = 'exam_ease';
     ```

5. **Start the Server**:
   - Run the XAMPP Apache and MySQL servers.
   - Access the system in your browser at `http://localhost/exam-ease/pages`.


## Usage Guide

### **Admin**
- Manage users, roles, and courses.
- Oversee exams and generate performance reports.

### **Instructor**
- Create and manage exams, courses, and questions.
- Review student responses and grades.

### **Student**
- Enroll in courses.
- Take exams and track performance through reports.

---


## Future Enhancements

- Add support for essay-type questions with AI-assisted grading.
- Integrate advanced analytics for performance tracking.
- Enable multi-language support for a global user base.

---

## Contributing

Contributions are welcome! Follow these steps:
1. Fork the repository.
2. Create a new branch:
   ```bash
   git checkout -b feature-name
   ```
3. Commit your changes:
   ```bash
   git commit -m 'Add feature-name'
   ```
4. Push to the branch:
   ```bash
   git push origin feature-name
   ```
5. Open a pull request.

---

## License

This project is licensed under the [MIT License](LICENSE).

---

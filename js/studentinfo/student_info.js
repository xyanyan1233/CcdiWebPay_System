//user
document.addEventListener("DOMContentLoaded", function () {
    fetch("../../theme/students/functions/check_student_login.php")
        .then(response => response.json())
        .then(data => {
            if (data.first_name) {
                // Display student name in the button
                const userMenuButton = document.querySelector(".user-menu button");
                userMenuButton.innerHTML = `<img src="../../images/students/user-avatar.webp" class="user-avatar" alt="User Avatar"> ${data.first_name}`;
            }
            
            console.log(data); // Debugging: View all student details in console
        })
        .catch(error => console.error("Error fetching student data:", error));
});
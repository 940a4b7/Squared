document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault();
    
    let studentId = document.getElementById("studentId").value.trim();
    let password = document.getElementById("password").value.trim();
    let loginMessage = document.getElementById("loginMessage");

    fetch("php/login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `student_id=${encodeURIComponent(studentId)}&password=${encodeURIComponent(password)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            window.location.href = "home.php"; // Redirect on successful login
        } else {
            loginMessage.textContent = data.message;
        }
    })
    .catch(error => {
        loginMessage.textContent = "An error occurred. Please try again.";
    });
});
$(document).ready(function () {
    $("#loginForm").submit(function (event) {
        event.preventDefault();

        var formData = {
            email: $("#email").val(),
            password: $("#password").val()
        };

        $.ajax({
            url: "/api/auth/login.php",
            type: "POST",
            contentType: "application/json",  // Set content type to JSON
            data: JSON.stringify(formData),   // Convert formData to JSON string
            success: function (response) {
                console.log(response);
                localStorage.setItem("token", response.token);
                window.location.href = "favorites.html";
            },
            error: function (xhr) {
                console.log("Error:", xhr.responseText);
                alert("Invalid credentials!");
            }
        });
    });
});

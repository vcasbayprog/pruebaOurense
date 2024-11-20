$(document).ready(function () {
    // Login
    $("#loginForm").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            url: "auth.php",
            type: "POST",
            data: {
                action: "login",
                email: $("#email").val(),
                password: $("#password").val(),
            },
            success: function (response) {
                console.log(response);
                try {
                    $("#loginMessage").text(response.message);
                    if (response.status === "success") {
                        setTimeout(() => {
                            alert("Bienvenido al sistema.");
                            window.location.href = "dashboard.php";
                        }, 1000);
                    }
                } catch (e) {
                    console.error("Error al manejar la respuesta:", e);
                    $("#loginMessage").text("Error en la respuesta del servidor.");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
                $("#loginMessage").text("Ocurrió un error al procesar la solicitud.");
            }
        });
    });


    //Register
    $("#registerForm").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            url: "auth.php",
            type: "POST",
            data: {
                action: "register",
                name: $("#name").val(),
                email: $("#email").val(),
                password: $("#password").val(),
            },
            success: function (response) {
                console.log(response);

                try {
                    if (response.status === "success") {
                        $("#registerMessage").text(response.message);
                        setTimeout(() => {
                            alert("Registro exitoso. Redirigiendo al login.");
                            window.location.href = "login.html";
                        }, 1000);
                    } else {
                        $("#registerMessage").text(response.message);
                    }
                } catch (e) {
                    console.error("Error al manejar la respuesta:", e);
                    $("#registerMessage").text("Error al procesar la respuesta del servidor.");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
                $("#registerMessage").text("Ocurrió un error al procesar la solicitud.");
            }
        });
    });


});

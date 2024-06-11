let username = document.getElementById("input_username");
let password = document.getElementById("input_password");
let btnLogin = document.getElementById("btn-login");
let errorMessage = document.getElementsByClassName("error-message")[0];

if (btnLogin) {
    btnLogin.addEventListener("click", function () {
        if (!username.value || !password.value) {
            errorMessage.textContent = "Please enter username and password";
            return;
        }

        fetch("http://localhost:8000/api/user/login", {
            method: "POST",
            body: JSON.stringify({
                username: username.value,
                password: password.value,
            }),
            headers: {
                "Content-type": "application/json; charset=UTF-8",
            },
        })
            .then((response) => {
                if (!response.ok) {
                    if (errorMessage) {
                        errorMessage.textContent =
                            "Login failed. Please check your credentials and try again.";
                    }
                }
                return response.json();
            })
            .then((json) => {
                console.log(json);
            })
            .catch((error) => {
                console.error("Error during fetch:", error);
            });
    });
} else {
    console.error("button with id 'btn-login' not found");
}

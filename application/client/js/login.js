
const loginForm = document.getElementById("loginForm");


const loginMessage = document.getElementById("loginMessage");


async function loginUser(event) {

    event.preventDefault();


    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value;


    if (username === "" || password === "") {
        loginMessage.textContent = "Please enter username and password.";
        return;
    }


    const formData = new FormData();
    formData.append("username", username);
    formData.append("password", password);

    try {

  
        const response = await fetch("../server/auth.php", {
            method: "POST",
            body: formData
        });

        const result = await response.json();


        if (response.ok && result.success) {

            loginMessage.style.color = "green";
            loginMessage.textContent = result.message;

  
            window.location.href = "operations.html";

        } else {

            loginMessage.style.color = "#a33";
            loginMessage.textContent = result.message;

        }

    } catch (error) {

        loginMessage.style.color = "#a33";
        loginMessage.textContent = "Unable to connect to the server.";

    }
}


loginForm.addEventListener("submit", loginUser);

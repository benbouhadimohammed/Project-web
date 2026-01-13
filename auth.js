document.getElementById("login-form").addEventListener("submit", async function (e) {
    e.preventDefault();

    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    try {
        const res = await fetch("api/login.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            credentials: "include", 
            body: JSON.stringify({ username, password })
        });

        const json = await res.json();

        if (json.success) {
           
            window.location.href = "catalog.html";
        } else {
           
            const errorMsg = document.getElementById("error-message");
            errorMsg.style.display = "block";
            errorMsg.innerText = json.message;
        }

    } catch (err) {
        console.error(err);
        const errorMsg = document.getElementById("error-message");
        errorMsg.style.display = "block";
        errorMsg.innerText = "Login failed. Try again later.";
    }
});

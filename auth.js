
document.getElementById("login-form").addEventListener("submit", function (e) {
  e.preventDefault(); 

  const u = document.getElementById("username").value;
  const p = document.getElementById("password").value;

  if (u === "admin" && p === "admin123") {
    localStorage.setItem("logged", "true");
    window.location.href = "catalog.html";
  } else {
    document.getElementById("error-message").style.display = "block";
    document.getElementById("error-message").innerText =
      "Invalid username or password";
  }
});



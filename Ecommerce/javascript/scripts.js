document.querySelector("form").addEventListener("submit", function(e) {
    const password = document.querySelector("[name='password']").value;
    if (password.length < 6) {
        alert("Le mot de passe doit comporter au moins 6 caractères.");
        e.preventDefault();
    }
});

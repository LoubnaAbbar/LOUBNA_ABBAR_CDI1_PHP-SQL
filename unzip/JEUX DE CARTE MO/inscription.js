document.addEventListener("DOMContentLoaded", () => {
    const tabs = document.querySelectorAll("#menu .tab");
    const contents = document.querySelectorAll("#content .content");

   
    tabs.forEach((tab) => {
        tab.addEventListener("click", () => {
            if (tab.classList.contains("active")) {
                tab.classList.remove("active");
                contents.forEach((content) => content.classList.remove("visible"));
            } else {
                tabs.forEach((t) => t.classList.remove("active"));
                tab.classList.add("active");

                const targetClass = tab.classList[1];
                contents.forEach((content) => {
                    content.classList.remove("visible");
                    if (content.classList.contains(targetClass)) {
                        content.classList.add("visible");
                    }
                });
            }
        });
    });

const form = document.getElementById("myForm");

form.addEventListener("submit", (event) => {
    event.preventDefault(); 

    const errorList = document.getElementById("errorList");
    errorList.innerHTML = ""; 

    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirmPassword");
    const flowerOptions = document.querySelectorAll("input[name='flower']");

    let hasError = false;

 

  
    if (email.value.trim() === "") {
        showError(email, "L'email est requis.");
        hasError = true;
    } else {
        showSuccess(email);
    }

    if (password.value.trim().length < 8) {
        showError(password, "Le mot de passe doit comporter au moins 8 caractères.");
        hasError = true;
    } else {
        showSuccess(password);
    }


    if (password.value !== confirmPassword.value) {
        showError(confirmPassword, "Les mots de passe ne correspondent pas.");
        hasError = true;
    } else {
        showSuccess(confirmPassword);
    }

    let isFlowerSelected = false;
    flowerOptions.forEach((option) => {
        if (option.checked) {
            isFlowerSelected = true;
        }
    });

    if (!isFlowerSelected) {
        const errorItem = document.createElement("li");
        errorItem.innerText = "Vous devez sélectionner une option pour la question QCM.";
        errorList.appendChild(errorItem);
        hasError = true;
    }


    if (!hasError) {
        alert("Formulaire soumis avec succès !");
    }
});

function showError(field, message) {
    const errorItem = document.createElement("li");
    errorItem.innerText = message;
    document.getElementById("errorList").appendChild(errorItem);
    field.classList.add("error"); 
    field.classList.remove("success"); 
}

function showSuccess(field) {
    field.classList.add("success"); 
    field.classList.remove("error"); 
}


});

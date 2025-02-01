document.addEventListener("DOMContentLoaded", function () {
    const navbar = document.querySelector("nav");

    window.addEventListener("scroll", function () {
        if (window.scrollY > 50) {
            navbar.classList.add("scrolled");
        } else {
            navbar.classList.remove("scrolled");
        }
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const navbar = document.querySelector("nav");
    const logo = document.querySelector(".logo img");

    let isScrolled = false;

    window.addEventListener("scroll", function () {
        if (window.scrollY > 50 && !isScrolled) {
            isScrolled = true;
            navbar.classList.add("scrolled");
            setTimeout(() => {
                logo.src = "/TrekSmart/assets/logo/logoblack.png"; // Delayed logo transition
            }, 100); // Adjust timing as needed
        } else if (window.scrollY <= 50 && isScrolled) {
            isScrolled = false;
            navbar.classList.remove("scrolled");
            setTimeout(() => {
                logo.src = "/TrekSmart/assets/logo/logowhite.png"; // Delayed logo revert
            }, 100);
        }
    });
});

// Password Validation
document.getElementById('password').addEventListener('input', function () {
    const password = this.value;
    const passwordHelp = document.getElementById('passwordHelp');
    const pattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    if (password.length < 8) {
        passwordHelp.textContent = 'Password must be at least 8 characters long.';
        passwordHelp.style.color = 'red';
    } else if (!/[A-Z]/.test(password)) {
        passwordHelp.textContent = 'Password must contain at least one uppercase letter.';
        passwordHelp.style.color = 'red';
    } else if (!/\d/.test(password)) {
        passwordHelp.textContent = 'Password must contain at least one number.';
        passwordHelp.style.color = 'red';
    } else if (!/[@$!%*?&]/.test(password)) {
        passwordHelp.textContent = 'Password must contain at least one special character.';
        passwordHelp.style.color = 'red';
    } else {
        passwordHelp.textContent = 'Password is strong!';
        passwordHelp.style.color = 'green';
    }
});

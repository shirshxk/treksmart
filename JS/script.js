document.addEventListener("DOMContentLoaded", () => {
    // Navbar Scrolling Logic
    const navbar = document.querySelector("nav");
    const logo = document.querySelector(".logo img");
    let isScrolled = false;

    window.addEventListener("scroll", () => {
        if (window.scrollY > 50 && !isScrolled) {
            isScrolled = true;
            navbar.classList.add("scrolled");
            setTimeout(() => {
                logo.src = "/TrekSmart/assets/logo/logoblack.png";
            }, 100);
        } else if (window.scrollY <= 50 && isScrolled) {
            isScrolled = false;
            navbar.classList.remove("scrolled");
            setTimeout(() => {
                logo.src = "/TrekSmart/assets/logo/logowhite.png";
            }, 100);
        }
    });

    // Dropdown Menu Logic
    const dropdown = document.querySelector(".dropdown");
    const toggle = dropdown.querySelector(".dropdown-toggle");

    toggle.addEventListener("click", (e) => {
        e.preventDefault();
        dropdown.classList.toggle("active");
    });

    document.addEventListener("click", (event) => {
        if (!dropdown.contains(event.target)) {
            dropdown.classList.remove("active");
        }
    });

    // Modal Logic
    document.addEventListener("click", (event) => {
        if (event.target.classList.contains("btn-secondary")) {
            const packageId = event.target.getAttribute("data-id");
            viewDetails(packageId);
        } else if (event.target.classList.contains("close")) {
            closeModal();
        }
    });

    // Password Validation
    const passwordInput = document.getElementById("password");
    if (passwordInput) {
        passwordInput.addEventListener("input", function () {
            const password = this.value;
            const passwordHelp = document.getElementById("passwordHelp");
            const pattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (password.length < 8) {
                passwordHelp.textContent = "Password must be at least 8 characters long.";
                passwordHelp.style.color = "red";
            } else if (!/[A-Z]/.test(password)) {
                passwordHelp.textContent = "Password must contain at least one uppercase letter.";
                passwordHelp.style.color = "red";
            } else if (!/\d/.test(password)) {
                passwordHelp.textContent = "Password must contain at least one number.";
                passwordHelp.style.color = "red";
            } else if (!/[@$!%*?&]/.test(password)) {
                passwordHelp.textContent = "Password must contain at least one special character.";
                passwordHelp.style.color = "red";
            } else {
                passwordHelp.textContent = "Password is strong!";
                passwordHelp.style.color = "green";
            }
        });
    }
});

// View Details Function
document.addEventListener("click", (event) => {
    if (event.target.classList.contains("btn-secondary")) {
        const packageId = event.target.getAttribute("data-id");
        viewDetails(packageId);
    }
});

function viewDetails(packageId) {
    fetch(`trekking.php?id=${packageId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Failed to fetch package details");
            }
            return response.text();
        })
        .then(data => {
            const modalBody = document.getElementById("modalBody");
            modalBody.innerHTML = data; // Inject fetched data into modal
            document.getElementById("packageModal").style.display = "flex"; // Display modal
        })
        .catch(error => {
            console.error("Error fetching package details:", error);
            alert("Could not load package details. Please try again.");
        });
}



function closeModal() {
    document.getElementById("packageModal").style.display = "none";
}

// Open Booking Modal
function openBookingModal(packageId) {
    document.getElementById("bookingPackageId").value = packageId; // Set package ID
    document.getElementById("bookingModal").style.display = "flex"; // Show modal
}

// Close Booking Modal
function closeBookingModal() {
    document.getElementById("bookingModal").style.display = "none";
    document.getElementById("bookingForm").reset(); // Reset form
}


document.getElementById('bookingForm').addEventListener('submit', function(event) {
    const email = document.getElementById('email').value;
    const trekStartDate = document.getElementById('trekStartDate').value;

    if (!email || !trekStartDate) {
        event.preventDefault(); // Prevent form submission
        alert("Please fill in all required fields!");
    }
});


// Wait for the DOM to be fully loaded before executing any code
document.addEventListener("DOMContentLoaded", () => {
    // ---------------------------
    // Navbar Scrolling Behavior
    // ---------------------------
    const navbar = document.querySelector("nav");
    const logo = document.querySelector(".logo img");
    let isScrolled = false;

    // When the user scrolls, change the navbar and logo based on scroll position
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

    // ---------------------------
    // Dropdown Menu Behavior
    // ---------------------------
    const dropdown = document.querySelector(".dropdown");
    const toggle = dropdown.querySelector(".dropdown-toggle");

    // Toggle the dropdown when its button is clicked
    toggle.addEventListener("click", (e) => {
        e.preventDefault();
        dropdown.classList.toggle("active");
    });

    // Close the dropdown when clicking outside of it
    document.addEventListener("click", (event) => {
        if (!dropdown.contains(event.target)) {
            dropdown.classList.remove("active");
        }
    });

    // ---------------------------
    // Modal Trigger for Details
    // ---------------------------
    document.addEventListener("click", (event) => {
        if (event.target.classList.contains("btn-secondary")) {
            const packageId = event.target.getAttribute("data-id");
            viewDetails(packageId);
        } else if (event.target.classList.contains("close")) {
            closeModal();
        }
    });

    // ---------------------------
    // Password Strength Validation
    // ---------------------------
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

// ---------------------------
// View Package Details
// ---------------------------
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
            modalBody.innerHTML = data; // Inject package details into the modal
            document.getElementById("packageModal").style.display = "flex"; // Show the modal
        })
        .catch(error => {
            console.error("Error fetching package details:", error);
            alert("Could not load package details. Please try again.");
        });
}

// ---------------------------
// Close Package Details Modal
// ---------------------------
function closeModal() {
    document.getElementById("packageModal").style.display = "none";
}

// ---------------------------
// Open Booking Modal
// ---------------------------
function openBookingModal(packageId) {
    document.getElementById("bookingPackageId").value = packageId; // Set the package ID in the form
    document.getElementById("bookingModal").style.display = "flex"; // Show the booking modal
}

// ---------------------------
// Close Booking Modal and Reset Form
// ---------------------------
function closeBookingModal() {
    document.getElementById("bookingModal").style.display = "none";
    document.getElementById("bookingForm").reset();
}

// ---------------------------
// Booking Form Submission Validation
// ---------------------------
document.getElementById('bookingForm').addEventListener('submit', function(event) {
    const email = document.getElementById('email').value;
    const trekStartDate = document.getElementById('trekStartDate').value;

    if (!email || !trekStartDate) {
        event.preventDefault();
        alert("Please fill in all required fields!");
    }
});

// ---------------------------
// General Modal Open/Close Functions
// ---------------------------
function openModal(modalId) {
    document.getElementById(modalId).style.display = "flex";
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

// ---------------------------
// Section Switching (Dashboard)
// ---------------------------
function showSection(sectionId) {
    document.querySelectorAll('.dashboard-section').forEach(section => {
        section.style.display = "none";
    });
    document.getElementById(sectionId).style.display = "block";
}

// ---------------------------
// Delete User Confirmation
// ---------------------------
function deleteUser(id) {
    if (confirm("Are you sure you want to delete this user?")) {
        window.location.href = `admin_actions.php?action=deleteUser&id=${id}`;
    }
}

// ---------------------------
// Delete Package Confirmation
// ---------------------------
function deletePackage(id) {
    if (confirm("Are you sure you want to delete this package?")) {
        window.location.href = `admin_actions.php?action=deletePackage&id=${id}`;
    }
}

// ---------------------------
// Delete Blog Confirmation
// ---------------------------
function deleteBlog(id) {
    if (confirm("Are you sure you want to delete this blog?")) {
        window.location.href = `admin_actions.php?action=deleteBlog&id=${id}`;
    }
}

// ---------------------------
// Delete Booking Confirmation
// ---------------------------
function deleteBooking(id) {
    if (confirm("Are you sure you want to delete this booking?")) {
        window.location.href = `admin_actions.php?action=deleteBooking&id=${id}`;
    }
}

// ---------------------------
// Approve Booking Confirmation
// ---------------------------
function approveBooking(id) {
    if (confirm("Are you sure you want to approve this booking?")) {
        window.location.href = `admin_actions.php?action=approveBooking&id=${id}`;
    }
}

// ---------------------------
// Cancel Booking Confirmation
// ---------------------------
function cancelBooking(id) {
    if (confirm("Are you sure you want to cancel this booking?")) {
        window.location.href = `admin_actions.php?action=cancelBooking&id=${id}`;
    }
}

// ---------------------------
// Open Edit Package Modal and Populate Fields
// ---------------------------
function openEditPackageModal(id, title, description, price, duration, difficulty) {
    document.getElementById('edit_package_id').value = id;
    document.getElementById('edit_package_title').value = title;
    document.getElementById('edit_package_description').value = description;
    document.getElementById('edit_package_price').value = price;
    document.getElementById('edit_package_duration').value = duration;
    document.getElementById('edit_package_difficulty').value = difficulty;
    openModal('editPackageModal');
}

// ---------------------------
// Open Edit Blog Modal and Populate Fields
// ---------------------------
function openEditBlogModal(id, title, excerpt, content, readTime) {
    document.getElementById('edit_blog_id').value = id;
    document.getElementById('edit_blog_title').value = title;
    document.getElementById('edit_blog_excerpt').value = excerpt;
    document.getElementById('edit_blog_content').value = content;
    document.getElementById('edit_blog_read_time').value = readTime;
    openModal('editBlogModal');
}

// ---------------------------
// Open Edit User Modal and Populate Fields
// ---------------------------
function openEditUserModal(id, firstName, lastName, username, email, adminCheck) {
    document.getElementById('edit_user_id').value = id;
    document.getElementById('edit_firstname').value = firstName;
    document.getElementById('edit_lastname').value = lastName;
    document.getElementById('edit_username').value = username;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_admin_check').value = adminCheck;
    openModal('editUserModal');
}

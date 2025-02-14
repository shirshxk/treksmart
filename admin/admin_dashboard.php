<?php
<<<<<<< HEAD
=======
session_start();
>>>>>>> ea3dcc8 (update)
// Start of admin dashboard page: Fetch data and set active section.
require '../includes/db.php';
$activeSection = $_GET['section'] ?? 'manageUsers';

// Check if an admin is logged in; if not, redirect to login.
if (!isset($_SESSION['user_id']) || $_SESSION['admin_check'] != 1) {
<<<<<<< HEAD
    header("Location: admin_login.php");
=======
    header("Location: ../auth/login.php");
>>>>>>> ea3dcc8 (update)
    exit;
}

// Fetch data for dashboard sections.
$packages = $conn->query("SELECT * FROM trekking_packages")->fetch_all(MYSQLI_ASSOC);
$blogs = $conn->query("SELECT * FROM blogs")->fetch_all(MYSQLI_ASSOC);
$users = $conn->query("SELECT * FROM users")->fetch_all(MYSQLI_ASSOC);
$bookings = $conn->query("SELECT b.id, b.trek_start_date, b.special_requests, b.status, u.firstname, u.lastname, p.title AS package_title
                          FROM bookings b
                          JOIN users u ON b.user_id = u.id
                          JOIN trekking_packages p ON b.package_id = p.id")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/png" href="/TrekSmart/assets/logo/favicon.png">
    <!-- Link to the CSS file -->
    <link rel="stylesheet" href="/TrekSmart/assets/CSS/style.css">
</head>
<body>
    <main class="admin-dashboard">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <h2 class="sidebar-title">Admin Dashboard</h2>
            <ul class="sidebar-menu">
                <li>
                    <a href="admin_dashboard.php?section=manageUsers" 
                       class="sidebar-link <?= $activeSection === 'manageUsers' ? 'active' : '' ?>">
                        Manage Users
                    </a>
                </li>
                <li>
                    <a href="admin_dashboard.php?section=managePackages" 
                       class="sidebar-link <?= $activeSection === 'managePackages' ? 'active' : '' ?>">
                        Manage Packages
                    </a>
                </li>
                <li>
                    <a href="admin_dashboard.php?section=manageBlogs" 
                       class="sidebar-link <?= $activeSection === 'manageBlogs' ? 'active' : '' ?>">
                        Manage Blogs
                    </a>
                </li>
                <li>
                    <a href="admin_dashboard.php?section=manageBookings" 
                       class="sidebar-link <?= $activeSection === 'manageBookings' ? 'active' : '' ?>">
                        Manage Bookings
                    </a>
                </li>
            </ul>
            <img src="/TrekSmart/assets/logo/logowhite.png" alt="Admin Dashboard" class="sidebar-image">
        </div>

        <!-- Main Content Area -->
        <div class="content">
            <!-- Manage Users Section -->
            <section id="manageUsers" class="dashboard-section" style="<?= $activeSection === 'manageUsers' ? 'display: block;' : 'display: none;' ?>">
                <h2>Manage Users</h2>
                <button class="btn-primary" onclick="openModal('addUserModal')">Add User</button>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $index => $user): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= $user['admin_check'] == 1 ? 'Admin' : 'User'; ?></td>
                                <td>
                                    <button class="btn-edit"
                                        onclick="openEditUserModal(
                                            <?= $user['id'] ?>, 
                                            '<?= htmlspecialchars($user['firstname'], ENT_QUOTES) ?>',
                                            '<?= htmlspecialchars($user['lastname'], ENT_QUOTES) ?>',
                                            '<?= htmlspecialchars($user['username'], ENT_QUOTES) ?>', 
                                            '<?= htmlspecialchars($user['email'], ENT_QUOTES) ?>',
                                            <?= $user['admin_check'] ?>
                                        )">Edit
                                    </button>
                                    <button class="btn-danger" onclick="deleteUser(<?= $user['id'] ?>)">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <!-- Manage Blogs Section -->
            <section id="manageBlogs" class="dashboard-section" style="<?= $activeSection === 'manageBlogs' ? 'display: block;' : 'display: none;' ?>">
                <h2>Manage Blogs</h2>
                <button class="btn-primary" onclick="openModal('addBlogModal')">Add Blog</button>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 25%;">Title</th>
                            <th style="width: 40%;">Excerpt</th>
                            <th style="width: 20%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($blogs as $index => $blog): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($blog['title']) ?></td>
                                <td><?= htmlspecialchars($blog['excerpt']) ?></td>
                                <td>
                                    <button class="btn-edit"
                                        onclick='openEditBlogModal(
                                            <?= json_encode($blog['id']) ?>, 
                                            <?= json_encode($blog['title']) ?>, 
                                            <?= json_encode($blog['excerpt']) ?>, 
                                            <?= json_encode($blog['content']) ?>, 
                                            <?= json_encode($blog['read_time']) ?>
                                        )'>
                                        Edit
                                    </button>
                                    <button class="btn-danger" onclick="deleteBlog(<?= $blog['id'] ?>)">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <!-- Manage Bookings Section -->
            <section id="manageBookings" class="dashboard-section" style="<?= $activeSection === 'manageBookings' ? 'display: block;' : 'display: none;' ?>">
                <h2>Manage Bookings</h2>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Package</th>
                            <th>User</th>
                            <th>Trek Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $index => $booking): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($booking['package_title']) ?></td>
                                <td><?= htmlspecialchars($booking['firstname'] . " " . $booking['lastname']) ?></td>
                                <td><?= htmlspecialchars($booking['trek_start_date']) ?></td>
                                <td><?= htmlspecialchars($booking['status']) ?></td>
                                <td>
                                    <?php if ($booking['status'] === 'Pending'): ?>
                                        <button class="btn-approve" onclick="approveBooking(<?= $booking['id'] ?>)">Approve</button>
                                    <?php endif; ?>
                                    <button class="btn-cancel" onclick="cancelBooking(<?= $booking['id'] ?>)">Cancel</button>
                                    <button class="btn-danger" onclick="deleteBooking(<?= $booking['id'] ?>)">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
            
            <!-- Manage Packages Section -->
            <section id="managePackages" class="dashboard-section" style="<?= $activeSection === 'managePackages' ? 'display: block;' : 'display: none;' ?>">
                <h2>Manage Packages</h2>
                <button class="btn-primary" onclick="openModal('addPackageModal')">Add Package</button>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($packages as $index => $package): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($package['title']) ?></td>
                                <td><?= htmlspecialchars($package['description']) ?></td>
                                <td>$<?= htmlspecialchars($package['price']) ?></td>
                                <td>
                                    <button class="btn-edit"
                                        onclick="openEditPackageModal(
                                            <?= $package['id'] ?>, 
                                            '<?= htmlspecialchars($package['title']) ?>', 
                                            '<?= htmlspecialchars($package['description']) ?>', 
                                            '<?= htmlspecialchars($package['price']) ?>', 
                                            '<?= htmlspecialchars($package['duration']) ?>', 
                                            '<?= htmlspecialchars($package['difficulty']) ?>'
                                        )">
                                        Edit
                                    </button>
                                    <button class="btn-danger" onclick="deletePackage(<?= $package['id'] ?>)">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </main>

    <!-- Add/Edit User Modal -->
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addUserModal')">&times;</span>
            <h2>Add User</h2>
            <form method="POST" action="admin_actions.php?action=addUser" enctype="multipart/form-data">
                <div class="booking-form-group">
                    <label for="add_firstname">First Name</label>
                    <input type="text" id="add_firstname" name="firstname" required>
                </div>
                <div class="booking-form-group">
                    <label for="add_lastname">Last Name</label>
                    <input type="text" id="add_lastname" name="lastname" required>
                </div>
                <div class="booking-form-group">
                    <label for="add_username">Username</label>
                    <input type="text" id="add_username" name="username" required>
                </div>
                <div class="booking-form-group">
                    <label for="add_email">Email</label>
                    <input type="email" id="add_email" name="email" required>
                </div>
                <div class="booking-form-group">
                    <label for="add_password">Password</label>
                    <input type="password" id="add_password" name="password" required>
                </div>
                <div class="booking-form-group">
                    <label for="add_admin_check">Role</label>
                    <select id="add_admin_check" name="admin_check" required>
                        <option value="0">User</option>
                        <option value="1">Admin</option>
                    </select>
                </div>
                <div class="booking-form-group">
                    <label for="add_profile_picture">Profile Picture</label>
                    <input type="file" id="add_profile_picture" name="profile_picture" accept="image/*">
                </div>
                <button type="submit" class="btn-primary">Add User</button>
            </form>
        </div>
    </div>

    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editUserModal')">&times;</span>
            <h2>Edit User</h2>
            <form method="POST" action="admin_actions.php?action=editUser" enctype="multipart/form-data">
                <input type="hidden" id="edit_user_id" name="id">
                <div class="booking-form-group">
                    <label for="edit_firstname">First Name</label>
                    <input type="text" id="edit_firstname" name="firstname" required>
                </div>
                <div class="booking-form-group">
                    <label for="edit_lastname">Last Name</label>
                    <input type="text" id="edit_lastname" name="lastname" required>
                </div>
                <div class="booking-form-group">
                    <label for="edit_username">Username</label>
                    <input type="text" id="edit_username" name="username" required>
                </div>
                <div class="booking-form-group">
                    <label for="edit_email">Email</label>
                    <input type="email" id="edit_email" name="email" required>
                </div>
                <div class="booking-form-group">
                    <label for="edit_admin_check">Role</label>
                    <select id="edit_admin_check" name="admin_check" required>
                        <option value="0">User</option>
                        <option value="1">Admin</option>
                    </select>
                </div>
                <div class="booking-form-group">
                    <label for="edit_profile_picture">Profile Picture</label>
                    <input type="file" id="edit_profile_picture" name="profile_picture" accept="image/*">
                </div>
                <button type="submit" class="btn-primary">Update User</button>
            </form>
        </div>
    </div>

    <!-- Edit Package Modal -->
    <div id="editPackageModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editPackageModal')">&times;</span>
            <h2>Edit Package</h2>
            <form method="POST" action="admin_actions.php?action=editPackage" enctype="multipart/form-data">
                <input type="hidden" id="edit_package_id" name="id">
                <div class="booking-form-group">
                    <label for="edit_package_title">Title</label>
                    <input type="text" id="edit_package_title" name="title" required>
                </div>
                <div class="booking-form-group">
                    <label for="edit_package_description">Description</label>
                    <textarea id="edit_package_description" name="description" required></textarea>
                </div>
                <div class="booking-form-group">
                    <label for="edit_package_duration">Duration (Days)</label>
                    <input type="number" id="edit_package_duration" name="duration" required>
                </div>
                <div class="booking-form-group">
                    <label for="edit_package_difficulty">Difficulty</label>
                    <select id="edit_package_difficulty" name="difficulty" required>
                        <option value="Easy" selected>Easy</option>
                        <option value="Moderate">Moderate</option>
                        <option value="Difficult">Difficult</option>
                    </select>
                </div>
                <div class="booking-form-group">
                    <label for="edit_package_price">Price</label>
                    <input type="number" id="edit_package_price" name="price" required>
                </div>
                <div class="booking-form-group">
                    <label for="edit_package_image">Upload New Image</label>
                    <input type="file" id="edit_package_image" name="image" accept="image/*">
                </div>
                <button type="submit" class="btn-primary">Update Package</button>
            </form>
        </div>
    </div>

    <!-- Edit Blog Modal -->
    <div id="editBlogModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editBlogModal')">&times;</span>
            <h2>Edit Blog</h2>
            <form method="POST" action="admin_actions.php?action=editBlog" enctype="multipart/form-data">
                <input type="hidden" id="edit_blog_id" name="id">
                <div class="booking-form-group">
                    <label for="edit_blog_title">Title</label>
                    <input type="text" id="edit_blog_title" name="title" required>
                </div>
                <div class="booking-form-group">
                    <label for="edit_blog_excerpt">Excerpt</label>
                    <textarea id="edit_blog_excerpt" name="excerpt" required></textarea>
                </div>
                <div class="booking-form-group">
                    <label for="edit_blog_content">Content</label>
                    <textarea id="edit_blog_content" name="content" required></textarea>
                </div>
                <div class="booking-form-group">
                    <label for="edit_blog_read_time">Read Time (Minutes)</label>
                    <input type="number" id="edit_blog_read_time" name="read_time" required>
                </div>
                <div class="booking-form-group">
                    <label for="edit_blog_image">Upload New Image</label>
                    <input type="file" id="edit_blog_image" name="image" accept="image/*">
                </div>
                <button type="submit" class="btn-primary">Update Blog</button>
            </form>
        </div>
    </div>

    <!-- Add Package Modal -->
    <div id="addPackageModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addPackageModal')">&times;</span>
            <h2>Add Package</h2>
            <form method="POST" action="admin_actions.php?action=addPackage" enctype="multipart/form-data">
                <div class="booking-form-group">
                    <label for="packageTitle">Title</label>
                    <input type="text" id="packageTitle" name="title" required>
                </div>
                <div class="booking-form-group">
                    <label for="packageDescription">Description</label>
                    <textarea id="packageDescription" name="description" required></textarea>
                </div>
                <div class="booking-form-group">
                    <label for="packageDuration">Duration (Days)</label>
                    <input type="number" id="packageDuration" name="duration" required>
                </div>
                <div class="booking-form-group">
                    <label for="packageDifficulty">Difficulty</label>
                    <select id="packageDifficulty" name="difficulty" required>
                        <option value="Easy" selected>Easy</option>
                        <option value="Moderate">Moderate</option>
                        <option value="Difficult">Difficult</option>
                    </select>
                </div>
                <div class="booking-form-group">
                    <label for="packagePrice">Price</label>
                    <input type="number" id="packagePrice" name="price" required>
                </div>
                <div class="booking-form-group">
                    <label for="packageImage">Upload Image</label>
                    <input type="file" id="packageImage" name="image" accept="image/*">
                </div>
                <button type="submit" class="btn-primary">Add Package</button>
            </form>
        </div>
    </div>

    <!-- Add Blog Modal -->
    <div id="addBlogModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addBlogModal')">&times;</span>
            <h2>Add Blog</h2>
            <form method="POST" action="admin_actions.php?action=addBlog" enctype="multipart/form-data">
                <div class="booking-form-group">
                    <label for="blogTitle">Title</label>
                    <input type="text" id="blogTitle" name="title" required>
                </div>
                <div class="booking-form-group">
                    <label for="blogExcerpt">Excerpt</label>
                    <textarea id="blogExcerpt" name="excerpt" required></textarea>
                </div>
                <div class="booking-form-group">
                    <label for="blogContent">Content</label>
                    <textarea id="blogContent" name="content" required></textarea>
                </div>
                <div class="booking-form-group">
                    <label for="blogReadTime">Read Time (minutes)</label>
                    <input type="number" id="blogReadTime" name="read_time" required>
                </div>
                <div class="booking-form-group">
                    <label for="blogImage">Upload Image</label>
                    <input type="file" id="blogImage" name="image" accept="image/*">
                </div>
                <button type="submit" class="btn-primary">Add Blog</button>
            </form>
        </div>
    </div>

    <!-- Load JavaScript -->
    <script src="/TrekSmart/assets/JS/script.js"></script>
</body>
</html>

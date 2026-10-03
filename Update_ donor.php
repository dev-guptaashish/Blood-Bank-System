<?php
require_once 'config.php';
requireLogin();

$id = '';
$donor = null;
$message = '';
$success_msg = '';

// Search/Load donor
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM donors WHERE id = ?");
    $stmt->execute([$id]);
    $donor = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$donor) {
        $message = "Donor not found!";
    }
}

// Update processing
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    $name = trim($_POST['name']);
    $age = (int)$_POST['age'];
    $dob = $_POST['dob'];
    $blood_group = $_POST['blood_group'];
    $gender = $_POST['gender'];
    $address = trim($_POST['address']);
    $mobile = trim($_POST['mobile']);
    $email = trim($_POST['email']);
    $city = trim($_POST['city']);
    
    try {
        $stmt = $pdo->prepare("UPDATE donors SET name=?, age=?, dob=?, blood_group=?, gender=?, address=?, mobile=?, email=?, city=?, updated_at=NOW() WHERE id=?");
        $stmt->execute([$name, $age, $dob, $blood_group, $gender, $address, $mobile, $email, $city, $id]);
        $success_msg = "Donor profile '$name' (ID: $id) updated successfully!";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Donor - Blood Bank System</title>
  <link rel="stylesheet" href="home.css">
  <style>
    .form-row { display: flex; gap: 10px; align-items: end; }
    .form-row .form-group { flex: 1; margin-bottom: 0; }
    .btn-search { background-color: #007bff; color: white; border: none; padding: 12px 20px; border-radius: 5px; cursor: pointer; font-size: 16px; height: fit-content; white-space: nowrap; }
    .btn-search:hover { background-color: #0056b3; }
    .btn-clear { background-color: #dc3545; color: white; border: none; padding: 12px 20px; border-radius: 5px; cursor: pointer; font-size: 16px; height: fit-content; white-space: nowrap; margin-left: 10px; }
    .btn-clear:hover { background-color: #c82333; }
    .success-message, .error-message { margin-top: 1.5rem; padding: 1rem 1.5rem; border-radius: 10px; font-weight: bold; text-align: center; <?php if ($success_msg): ?>background: rgba(76, 175, 80, 0.2); border: 2px solid #4caf50; color: #2e7d32; display: block;<?php elseif ($message): ?>background: rgba(244, 67, 54, 0.2); border: 2px solid #f44336; color: #d32f2f; display: block;<?php else: ?>display: none;<?php endif; ?> animation: fadeIn 0.5s; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    @media (max-width: 768px) { .form-row { flex-direction: column; } .btn-search, .btn-clear { align-self: stretch; margin-left: 0; } }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="container">
      <h1>Blood Bank System</h1>
      <ul id="nav-menu">
        <li><a href="home_full.php">Home</a></li>
        <li class="dropdown"><a>Donors</a><div class="dropdown-content"><a href="Donor.php">Add Donors</a><a href="Update_ donor.php">Update Donor</a><a href="all_donor_details.php">All Donor Details</a></div></li>
        <li class="dropdown"><a>Search blood donor</a><div class="dropdown-content"><a href="search_donor_location.php">Location</a><a href="search_donor_bloodgroup.php">Blood group</a></div></li>
        <li class="dropdown"><a>Stock</a><div class="dropdown-content"><a href="stock_increase.php">Stock (Increase)</a><a href="stock_decrease.php">Stock (Decrease)</a><a href="stock_detail.php">Detail</a></div></li>
        <li><a href="delete_donor.php">Delete donor</a></li>
        <li><a href="login.php?logout=1">Log out</a></li>
      </ul>
      <div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>
    </div>
  </nav>

  <section class="hero">
    <div class="hero-content">
      <h1>Update Donor Information</h1>
      <p>Enter Donor ID and click Search, then update details</p>
    </div>
  </section>

  <main>
    <div class="form-container">
      <div class="form-card">
        <h2>Update Donor Form</h2>
        <?php if ($message): ?><div class="error-message"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
        <?php if ($success_msg): ?><div class="success-message"><?php echo $success_msg; ?></div><?php endif; ?>
        <form method="POST" id="updateForm">
          <input type="hidden" name="id" value="<?php echo $donor ? $donor['id'] : (isset($_POST['id']) ? $_POST['id'] : $id); ?>">
          <div class="form-row">
            <div class="form-group">
              <label for="donor_id">Donor ID *</label>
              <input type="number" id="donor_id" name="search_id" class="form-control" value="<?php echo $donor ? $donor['id'] : $id; ?>" placeholder="Enter Donor ID to search" min="1">
            </div>
            <button type="button" class="btn btn-search" id="searchBtn">Search</button>
          </div>

          <div class="form-group">
            <label for="name">Full Name *</label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo $donor ? htmlspecialchars($donor['name']) : (isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''); ?>" required>
          </div>
          <div class="form-group">
            <label for="age">Age *</label>
            <input type="number" id="age" name="age" class="form-control" value="<?php echo $donor ? $donor['age'] : (isset($_POST['age']) ? $_POST['age'] : ''); ?>" min="18" max="70" required>
          </div>
          <div class="form-group">
            <label for="dob">Date of Birth *</label>
            <input type="date" id="dob" name="dob" class="form-control" value="<?php echo $donor ? $donor['dob'] : (isset($_POST['dob']) ? $_POST['dob'] : ''); ?>" required>
          </div>
          <div class="form-group">
            <label for="blood_group">Blood Group *</label>
            <select id="blood_group" name="blood_group" class="form-control" required>
              <option value="" disabled hidden>Select Blood Group</option>
              <option <?php echo ($donor && $donor['blood_group']=='O+' || (isset($_POST['blood_group']) && $_POST['blood_group']=='O+')) ? 'selected' : ''; ?>>O+</option>
              <option <?php echo ($donor && $donor['blood_group']=='O-' || (isset($_POST['blood_group']) && $_POST['blood_group']=='O-')) ? 'selected' : ''; ?>>O-</option>
              <option <?php echo ($donor && $donor['blood_group']=='A+' || (isset($_POST['blood_group']) && $_POST['blood_group']=='A+')) ? 'selected' : ''; ?>>A+</option>
              <option <?php echo ($donor && $donor['blood_group']=='A-' || (isset($_POST['blood_group']) && $_POST['blood_group']=='A-')) ? 'selected' : ''; ?>>A-</option>
              <option <?php echo ($donor && $donor['blood_group']=='B+' || (isset($_POST['blood_group']) && $_POST['blood_group']=='B+')) ? 'selected' : ''; ?>>B+</option>
              <option <?php echo ($donor && $donor['blood_group']=='B-' || (isset($_POST['blood_group']) && $_POST['blood_group']=='B-')) ? 'selected' : ''; ?>>B-</option>
              <option <?php echo ($donor && $donor['blood_group']=='AB+' || (isset($_POST['blood_group']) && $_POST['blood_group']=='AB+')) ? 'selected' : ''; ?>>AB+</option>
              <option <?php echo ($donor && $donor['blood_group']=='AB-' || (isset($_POST['blood_group']) && $_POST['blood_group']=='AB-')) ? 'selected' : ''; ?>>AB-</option>
            </select>
          </div>
          <div class="form-group">
            <label>Gender *</label>
            <div class="radio-group">
              <label><input type="radio" name="gender" value="Male" <?php echo ($donor && $donor['gender']=='Male' || (isset($_POST['gender']) && $_POST['gender']=='Male')) ? 'checked' : ''; ?> required> Male</label>
              <label><input type="radio" name="gender" value="Female" <?php echo ($donor && $donor['gender']=='Female' || (isset($_POST['gender']) && $_POST['gender']=='Female')) ? 'checked' : ''; ?>> Female</label>
              <label><input type="radio" name="gender" value="Other" <?php echo ($donor && $donor['gender']=='Other' || (isset($_POST['gender']) && $_POST['gender']=='Other')) ? 'checked' : ''; ?> > Other</label>
            </div>
          </div>
          <div class="form-group">
            <label for="address">Address *</label>
            <textarea id="address" name="address" class="form-control" rows="3" required><?php echo $donor ? htmlspecialchars($donor['address']) : (isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''); ?></textarea>
          </div>
          <div class="form-group">
            <label for="mobile">Mobile Number *</label>
            <input type="tel" id="mobile" name="mobile" class="form-control" value="<?php echo $donor ? htmlspecialchars($donor['mobile']) : (isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : ''); ?>" required>
          </div>
          <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" class="form-control" value="<?php echo $donor ? htmlspecialchars($donor['email']) : (isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''); ?>" required>
          </div>
          <div class="form-group">
            <label for="city">City *</label>
            <input type="text" id="city" name="city" class="form-control" value="<?php echo $donor ? htmlspecialchars($donor['city']) : (isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''); ?>" required>
          </div>
          <div class="form-row">
            <button type="submit" class="btn">Update Donor</button>
            <button type="reset" class="btn btn-clear" id="clearBtn">Clear All</button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <footer>
    <div class="container">
      <p>&copy; 2024 Blood Bank System | Logged as <?php echo htmlspecialchars($_SESSION['username']); ?></p>
    </div>
  </footer>

  <script>
    // Search functionality
    document.getElementById('searchBtn').onclick = function() {
      const donorIdInput = document.getElementById('donor_id');
      const id = donorIdInput.value.trim();
      if (id) {
        window.location.href = 'Update_ donor.php?id=' + id;
      } else {
        alert('Please enter Donor ID first');
      }
    };

    // Clear functionality
    document.getElementById('clearBtn').onclick = function() {
      document.getElementById('updateForm').reset();
      document.getElementById('donor_id').value = '';
      // Uncheck radios
      document.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);
    };

    // Mobile nav
    const hamburger = document.getElementById('hamburger'), navMenu = document.getElementById('nav-menu');
    function toggleMenu() { navMenu.style.display = navMenu.style.display === 'flex' ? 'none' : 'flex'; }
    hamburger.onclick = toggleMenu;
    window.onresize = () => { if (window.innerWidth > 768) navMenu.style.display = 'flex'; };
  </script>
</body>
</html>

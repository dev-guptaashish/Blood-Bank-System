<?php
require_once 'config.php';
requireLogin();

$message = '';
$success_msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
        $stmt = $pdo->prepare("INSERT INTO donors (name, age, dob, blood_group, gender, address, mobile, email, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $age, $dob, $blood_group, $gender, $address, $mobile, $email, $city]);
        $success_msg = "Donor '$name' ($blood_group) added successfully! ID: " . $pdo->lastInsertId();
        $_POST = []; // Reset form
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
  <title>Donor Registration - Blood Bank System</title>
  <link rel="stylesheet" href="home.css">
  <style>
    .success-message {
      margin-top: 1.5rem;
      padding: 1rem 1.5rem;
      background: rgba(76, 175, 80, 0.2);
      border: 2px solid #4caf50;
      border-radius: 10px;
      color: #2e7d32;
      font-weight: bold;
      text-align: center;
      <?php if ($success_msg): ?>display: block;<?php endif; ?>
      animation: fadeIn 0.5s ease-in;
    }
    .error-message {
      margin-top: 1.5rem;
      padding: 1rem 1.5rem;
      background: rgba(244, 67, 54, 0.2);
      border: 2px solid #f44336;
      border-radius: 10px;
      color: #d32f2f;
      font-weight: bold;
      text-align: center;
      <?php if ($message): ?>display: block;<?php endif; ?>
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>

<body>
  <!-- Navigation Bar -->
  <nav class="navbar" role="navigation" aria-label="Main navigation">
    <div class="container">
      <h1>Blood Bank System</h1>
      <ul id="nav-menu" role="menubar">
        <li><a href="home_full.php">Home</a></li>
        <li class="dropdown">
          <a>Donors</a>
          <div class="dropdown-content">
            <a href="Donor.php">Add Donors</a>
            <a href="Update_ donor.php">Update Donor</a>
            <a href="all_donor_details.php">All Donor Details</a>
          </div>
        </li>
        <li class="dropdown">
          <a>Search blood donor</a>
          <div class="dropdown-content">
            <a href="search_donor_location.php">Location</a>
            <a href="search_donor_bloodgroup.php">Blood group</a>
          </div>
        </li>
        <li class="dropdown">
          <a>Stock</a>
          <div class="dropdown-content">
            <a href="stock_increase.php">Stock (Increase)</a>
            <a href="stock_decrease.php">Stock (Decrease)</a>
            <a href="stock_detail.php">Detail</a>
          </div>
        </li>
        <li><a href="delete_donor.php">Delete donor</a></li>
        <li><a href="login.php?logout=1">Log out</a></li>
      </ul>
      <div class="hamburger" id="hamburger">
        <span></span><span></span><span></span>
      </div>
    </div>
  </nav>

  <section class="hero">
    <div class="hero-content">
      <h1>Become a Donor</h1>
      <p>Save lives by registering as a blood donor today</p>
    </div>
  </section>

  <main>
    <div class="form-container">
      <div class="form-card">
        <h2>Donor Registration Form</h2>
        <?php if ($message): ?>
          <div class="error-message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if ($success_msg): ?>
          <div class="success-message"><?php echo $success_msg; ?></div>
        <?php endif; ?>
        <form method="POST" id="donorForm">
          <div class="form-group">
            <label for="name">Full Name *</label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" placeholder="Enter your full name" required>
          </div>
          <div class="form-group">
            <label for="age">Age *</label>
            <input type="number" id="age" name="age" class="form-control" value="<?php echo isset($_POST['age']) ? $_POST['age'] : ''; ?>" min="18" max="70" required>
          </div>
          <div class="form-group">
            <label for="dob">Date of Birth *</label>
            <input type="date" id="dob" name="dob" class="form-control" value="<?php echo isset($_POST['dob']) ? $_POST['dob'] : ''; ?>" required>
          </div>
          <div class="form-group">
            <label for="blood_group">Blood Group *</label>
            <select id="blood_group" name="blood_group" class="form-control" required>
              <option value="" disabled selected>Select Blood Group</option>
              <option <?php echo (isset($_POST['blood_group']) && $_POST['blood_group']=='O+') ? 'selected' : ''; ?>>O+</option>
              <option <?php echo (isset($_POST['blood_group']) && $_POST['blood_group']=='O-') ? 'selected' : ''; ?>>O-</option>
              <option <?php echo (isset($_POST['blood_group']) && $_POST['blood_group']=='A+') ? 'selected' : ''; ?>>A+</option>
              <option <?php echo (isset($_POST['blood_group']) && $_POST['blood_group']=='A-') ? 'selected' : ''; ?>>A-</option>
              <option <?php echo (isset($_POST['blood_group']) && $_POST['blood_group']=='B+') ? 'selected' : ''; ?>>B+</option>
              <option <?php echo (isset($_POST['blood_group']) && $_POST['blood_group']=='B-') ? 'selected' : ''; ?>>B-</option>
              <option <?php echo (isset($_POST['blood_group']) && $_POST['blood_group']=='AB+') ? 'selected' : ''; ?>>AB+</option>
              <option <?php echo (isset($_POST['blood_group']) && $_POST['blood_group']=='AB-') ? 'selected' : ''; ?>>AB-</option>
            </select>
          </div>
          <div class="form-group">
            <label>Gender *</label>
            <div class="radio-group">
              <label><input type="radio" name="gender" value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender']=='Male') ? 'checked' : ''; ?> required> Male</label>
              <label><input type="radio" name="gender" value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender']=='Female') ? 'checked' : ''; ?>> Female</label>
              <label><input type="radio" name="gender" value="Other" <?php echo (isset($_POST['gender']) && $_POST['gender']=='Other') ? 'checked' : ''; ?>> Other</label>
            </div>
          </div>
          <div class="form-group">
            <label for="address">Address *</label>
            <textarea id="address" name="address" class="form-control" rows="3" placeholder="Enter your complete address" required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
          </div>
          <div class="form-group">
            <label for="mobile">Mobile Number *</label>
            <input type="tel" id="mobile" name="mobile" class="form-control" value="<?php echo isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : ''; ?>" placeholder="10-digit mobile number" required>
          </div>
          <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" class="form-control" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" placeholder="your.email@example.com" required>
          </div>
          <div class="form-group">
            <label for="city">City *</label>
            <input type="text" id="city" name="city" class="form-control" value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>" placeholder="Enter your city" required>
          </div>
          <button type="submit" class="btn">Register Now</button>
        </form>
      </div>
    </div>
  </main>

  <footer>
    <div class="container">
      <p>&copy; 2024 Blood Bank System. All rights reserved. | Be the hero, donate blood!</p>
    </div>
  </footer>

  <script>
    const hamburger = document.getElementById('hamburger'), navMenu = document.getElementById('nav-menu');
    function toggleMenu() { navMenu.style.display = navMenu.style.display === 'flex' ? 'none' : 'flex'; }
    hamburger.onclick = toggleMenu; hamburger.onkeydown = e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggleMenu(); } };
    window.onresize = () => { if (window.innerWidth > 768) navMenu.style.display = 'flex'; };
  </script>
</body>
</html>


<?php
require_once 'config.php';
requireLogin();

$location = '';
$donors = [];


if (isset($_GET['location'])) {
    $location = trim($_GET['location']);
    $stmt = $pdo->prepare("SELECT * FROM donors WHERE city LIKE ? OR address LIKE ? ORDER BY name");
    $stmt->execute(["%$location%", "%$location%"]);
    $donors = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Donors by Location - Blood Bank System</title>
  <link rel="stylesheet" href="home.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
<style>
    .search-container { max-width: 1400px; margin: 2rem auto; }
    .search-form-card { width: 700px; height: 300px; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border-radius: 15px; padding: 3rem; box-shadow: 0 15px 35px rgba(0,0,0,0.2); text-align: center; margin: 0 auto 2rem; display: flex; flex-direction: column; justify-content: center; }
    .search-row { display: flex; gap: 15px; align-items: end; margin-bottom: 1rem; }
    .search-row .form-group { flex: 1; margin-bottom: 0; }
    .search-btn { background-color: #d32f2f; color: white; border: none; padding: 15px 30px; border-radius: 10px; cursor: pointer; font-size: 1.1rem; font-weight: bold; height: fit-content; white-space: nowrap; transition: all 0.3s ease; }
    .search-btn:hover { background-color: #b71c1c; transform: translateY(-2px); }
    .results-container { margin-top: 2rem; }
    .results-header { text-align: center; margin-bottom: 1.5rem; color: #333; }
    .results-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border-radius: 15px; padding: 2rem; box-shadow: 0 15px 35px rgba(0,0,0,0.2); overflow-x: auto; }
    .donors-table { width: 100%; min-width: 1200px; border-collapse: collapse; margin-top: 1rem; }
    .donors-table th, .donors-table td { padding: 10px 8px; border: 1px solid #ddd; text-align: left; font-size: 0.9rem; }
    .donors-table th { background-color: #d32f2f; color: white; font-weight: bold; position: sticky; top: 0; }
    .donors-table tr:nth-child(even) { background-color: #f9f9f9; }
    .no-results { padding: 2rem; text-align: center; color: #666; }
    @media (max-width: 768px) { 
      .search-row { flex-direction: column; } 
      .search-btn { align-self: stretch; } 
      .search-form-card, .results-card { padding: 2rem 1.5rem; } 
      .donors-table th, .donors-table td { padding: 8px 4px; font-size: 0.8rem; }
    }
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
      <h1>Search Donors by Location</h1>
      <p>Find available blood donors in your area</p>
    </div>
  </section>

  <main>
    <div class="search-container">
      <div class="search-form-card">
        <h2>Search Donors by City/Area</h2>
        <form method="GET">
          <div class="search-row">
            <div class="form-group">
              <label for="location">Location (City/Area)</label>
              <input type="text" id="location" name="location" class="form-control" value="<?php echo htmlspecialchars($location); ?>" placeholder="Enter city or area name">
            </div>
            <button type="submit" class="search-btn">Search</button>
          </div>
        </form>
      </div>

      <?php if ($donors): ?>
        <div class="results-container">
          <h3 style="color: #333; margin-bottom: 10px; text-align: center;"><?php echo count($donors); ?> donor<?php echo count($donors) !== 1 ? 's' : ''; ?> found for "<?php echo htmlspecialchars($location); ?>"</h3>
          <div class="results-card">
            <button class="search-btn" style="float: right; margin-bottom: 1rem; padding: 12px 24px; font-size: 1rem;" onclick="CreatePDF()">📄 Download PDF</button>
            <table class="donors-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Age</th>
                  <th>Gender</th>
                  <th>Blood Group</th>
                  <th>City</th>
                  <th>Mobile</th>
                  <th>Email</th>
                  <th>Address</th>
                  <th>Added</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($donors as $donor): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($donor['id']); ?></td>
                    <td><?php echo htmlspecialchars($donor['name']); ?></td>
                    <td><?php echo htmlspecialchars($donor['age']); ?></td>
                    <td><?php echo htmlspecialchars($donor['gender']); ?></td>
                    <td><strong><?php echo htmlspecialchars($donor['blood_group']); ?></strong></td>
                    <td><?php echo htmlspecialchars($donor['city']); ?></td>
                    <td><?php echo htmlspecialchars($donor['mobile']); ?></td>
                    <td><?php echo htmlspecialchars($donor['email']); ?></td>
                    <td><?php echo htmlspecialchars(substr($donor['address'], 0, 50)) . (strlen($donor['address']) > 50 ? '...' : ''); ?></td>
                    <td><?php echo date('M j, Y', strtotime($donor['created_at'])); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php elseif ($location): ?>
        <div class="results-container">
          <div class="results-card">
            <div class="no-results">
              <h3>No donors found</h3>
              <p>No donors match "<strong><?php echo htmlspecialchars($location); ?></strong>". Try a different location or add new donors.</p>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <footer>
    <div class="container">
      <p>&copy; 2024 Blood Bank System | Logged as <?php echo htmlspecialchars($_SESSION['username']); ?></p>
    </div>
  </footer>

  <script>
    const hamburger = document.getElementById('hamburger'), navMenu = document.getElementById('nav-menu');
    function toggleMenu() { navMenu.style.display = navMenu.style.display === 'flex' ? 'none' : 'flex'; }
    hamburger.onclick = toggleMenu;
    window.onresize = () => { if (window.innerWidth > 768) navMenu.style.display = 'flex'; };
    
   function CreatePDF() {
    if (!window.jspdf || !window.jspdf.jsPDF) {
        alert('PDF library not loaded. Refresh page and try again.');
        return;
    }

    const jsPDF = window.jspdf.jsPDF;
    const doc = new jsPDF();

    const location = '<?php echo addslashes($location); ?>';
    const count = <?php echo isset($donors) ? count($donors) : 0; ?>;

    doc.setFontSize(18);
    doc.text(`Donors Found in "${location}" (${count})`, 14, 20);

    const table = document.querySelector('.donors-table tbody');

    if (!table || table.rows.length === 0) {
        doc.text('No donors found.', 14, 30);
    } else {
        const rows = Array.from(table.rows).map(row =>
            Array.from(row.cells).map(cell => cell.textContent.trim())
        );

        doc.autoTable({
            head: [['ID','Name','Age','Gender','Blood Group','City','Mobile','Email','Address','Date']],
            body: rows,
            startY: 30,
            styles: { fontSize: 7 },
            headStyles: { fillColor: [211, 47, 47] }
        });
    }

    doc.save(`donors_${location}.pdf`);
}
  </script>
</body>
</html>


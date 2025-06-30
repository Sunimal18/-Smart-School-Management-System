<?php
// index.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Smart School System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>

  <!-- Hero Section -->
  <header class="hero-section text-white d-flex align-items-center justify-content-center text-center">
    <div class="overlay"></div>
    <div class="content">
      <img src="library/logo.webp" alt="School Logo" class="school-logo mb-3">
      <h1 class="display-4 font-weight-bold">Smart School System</h1>
      <p class="lead">Pu/Bandaranayakapura Maha Vidyalaya</p>
      <a href="login.php" class="btn btn-outline-light btn-lg mt-3">Teacher / Principal Login</a>
    </div>
  </header>

  <!-- Main Content -->
  <main class="container py-5">
    <!-- School Info -->
    <div class="text-center mb-5">
      <h2 class="mb-2">Welcome to Our School</h2>
      <p class="text-muted mb-1">Pu/Bandaranayakapura M.V. <br> Wanathawilluwa</p>
      <p class="text-muted">📞 +94 714443274 | 📧 bandaranayakapuram@gmail.com</p>
    </div>

    <!-- Parent Check Form -->
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-lg">
          <div class="card-header bg-primary text-white text-center">
            <h5>Check Student Marks</h5>
          </div>
          <div class="card-body">
            <form action="check_marks.php" method="GET">
              <div class="form-group">
                <label for="index_no">Student Index Number</label>
                <input type="text" name="index_no" id="index_no" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="Grade">Grade</label>
                <select name="grade" id="term" class="form-control" required>
                  <option value="">-- Select Grade --</option>
                  <option value="6">Grade 6</option>
                  <option value="7">Grade 7</option>
                  <option value="8">Grade 8</option>
                  <option value="9">Grade 9</option>
                  <option value="10">Grade 10</option>
                  <option value="11">Grade 11</option>
                </select>
              </div>
              <div class="form-group">
                <label for="term">Term</label>
                <select name="term" id="term" class="form-control" required>
                  <option value="">-- Select term --</option>
                  <option value="1">1st Term Test</option>
                  <option value="2">2nd Term Test</option>
                  <option value="3">3rd Term Test</option>
                </select>
              </div>
              <button type="submit" class="btn btn-success btn-block">Check Marks</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="text-center text-muted py-4">
    &copy; <?php echo date('Y'); ?> Smart School System. All rights reserved.
  </footer>

</body>
</html>

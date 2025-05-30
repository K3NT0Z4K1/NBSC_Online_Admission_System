<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Collapsible Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  

<link href= "../components/css/style.css" rel="stylesheet">
  


</head>
<body>

    

  <!-- Sidebar -->
  <div id="sidebar" class="sidebar position-fixed">
    <h4 class="text-center py-3">Dashboard</h4>
    <a href="#">Home</a>
    <a href="#">Profile</a>
    <a href="#">Settings</a>
    <a href="#">Logout</a>
  </div>

  <!-- Main Content -->
  <div id="mainContent" class="content">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom mb-4">
      <div class="container-fluid">
        <button class="btn btn-outline-light me-3" id="toggleSidebar">☰</button>
        <span class="navbar-text">Welcome to the NBSC Online Admission System</span>
      </div>
    </nav>

    <!-- Form -->
    <div class="container">
      <form class="row g-3">
        <div class="col-md-4">
          <label for="validationServer01" class="form-label">First name</label>
          <input type="text" class="form-control" id="" required>
        </div>
        <div class="col-md-4">
          <label for="validationServer02" class="form-label">Last name</label>
          <input type="text" class="form-control" id="validationServer02" required>
        </div>
        <div class="col-md-4">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" required>
        </div>
        <div class="col-md-6">
          <label for="city" class="form-label">City</label>
          <input type="text" class="form-control" id="city" required>
        </div>
        <div class="col-md-3">
          <label for="institute" class="form-label">Institute</label>
          <select class="form-select" id="institute" required>
            <option selected disabled value="">Choose...</option>
            <option>IBM</option>
            <option>ICS</option>
            <option>ITE</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="phone" class="form-label">Phone Number</label>
          <input type="text" class="form-control" id="phone" required>
        </div>
        <div class="col-12">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="termsCheck" required>
            <label class="form-check-label" for="termsCheck">
              Agree to terms and conditions
            </label>
          </div>
        </div>
        <div class="col-12">
          <button class="btn btn-primary" type="submit">Submit form</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('mainContent');
    const toggleBtn = document.getElementById('toggleSidebar');

    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
      content.classList.toggle('collapsed');
    });
  </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>NBSC Online Admission - Dashboard</title>
  <style>
   * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
    }

    .sidebar {
      width: 250px;
      background-color: #0d1b4c;
      color: white;
      min-height: 100vh;
      padding: 20px;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 40px;
    }

    .logo-img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
      background-color: white;
      padding: 5px;
    }

    .nav {
      list-style: none;
    }

    .nav-item {
      padding: 12px;
      margin-bottom: 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    .nav-item.active,
    .nav-item:hover {
      background-color: #3053a5;
    }

    .main {
      flex: 1;
      background-color: #f5f5f5;
      padding: 30px;
    }

    .top-bar {
      display: flex;
      justify-content: flex-end;
      margin-bottom: 20px;
    }

    .logout-btn {
      background-color: #0d1b4c;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .tabs {
      margin-bottom: 20px;
    }

    .tab-button {
      padding: 10px 20px;
      border: none;
      background-color: #ddd;
      margin-right: 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    .tab-button.active {
      background-color: #0d1b4c;
      color: white;
    }

    h2 {
      margin-bottom: 15px;
      margin-top: 15px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    th,
    td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    th {
      background-color: #0d1b4c;
      color: white;
    }

    .approved {
      background-color: #7dff97;
      padding: 5px 10px;
      border-radius: 5px;
    }

    .failed {
      background-color: #f5a623;
      padding: 5px 10px;
      border-radius: 5px;
    }

    .sending {
      background-color: #b0a8f5;
      padding: 5px 10px;
      border-radius: 5px;
    }
  </style>
</head>

<body>
  <div class="sidebar">
    <div class="logo">
      <img src="../../components/img/nbsclogo.png" alt="Logo" class="logo-img" />
      <h3>NBSC Online Admission</h3>
    </div>
    <ul class="nav">
      <li class="nav-item active">Dashboard</li>
    </ul>
  </div>

  <div class="main">
    <div class="top-bar">
      <button class="logout-btn" onclick="window.location.href='../../index.php'">Log out</button>
    </div>

    <div class="tabs">
      <button onclick="window.location.href='dashboard.php'" class="tab-button">Approved Applications</button>
      <button onclick="window.location.href='exam-scheduling.php'" class="tab-button">Exam Scheduling</button>
      <button class="tab-button active">Result Management</button>

    </div>

    <div id="result" class="tab-content active">
      <h2>Result Management</h2>
      <table>
        <tr>
          <th>Applicant</th>
          <th>Course</th>
          <th>Result</th>
          <th>Status</th>
        </tr>
        <tr>
          <td>Maria Santos</td>
          <td>BSIT</td>
          <td>85%</td>
          <td><span class="approved">Passed</span></td>
        </tr>
        <tr>
          <td>Juan Dela Cruz</td>
          <td>BSED</td>
          <td>60%</td>
          <td><span class="failed">Failed</span></td>
        </tr>
        <tr>
          <td>Anna Rivera</td>
          <td>BSIT</td>
          <td>75%</td>
          <td><span class="sending">Pending</span></td>
        </tr>
      </table>
    </div>
  </div>
</body>

</html>
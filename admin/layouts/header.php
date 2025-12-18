<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Absensi QR Code</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f6fa;
      font-family: 'Poppins', sans-serif;
    }
    .sidebar {
      height: 100vh;
      background: linear-gradient(180deg, #0d6efd, #6610f2);
      color: #fff;
      padding-top: 1.5rem;
      position: fixed;
      width: 240px;
    }
    .sidebar a {
      color: #fff;
      text-decoration: none;
      display: block;
      padding: 12px 20px;
      border-radius: 10px;
      margin: 4px 10px;
    }
    .sidebar a:hover, .sidebar a.active {
      background-color: rgba(255,255,255,0.2);
    }
    .main-content {
      margin-left: 240px;
      padding: 30px;
    }
    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .btn-custom {
      background: #0d6efd;
      color: white;
      border-radius: 8px;
    }
    .btn-custom:hover {
      background: #0b5ed7;
    }
  </style>
</head>

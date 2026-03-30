<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'SIMORGAN')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#4361ee',
            'primary-dark': '#3a56d4',
            secondary: '#3fbc93',
            danger: '#ef476f',
            success: '#06d6a0',
            warning: '#ffd166',
            info: '#3a86ff',
            light: '#f8f9fa',
            dark: '#212529',
            gray: '#6c757d',
            border: '#e9ecef',
            'primary-light': '#eef2ff',
          },
          fontFamily: {
            'poppins': ['Poppins', 'sans-serif'],
          }
        }
      }
    }
  </script>
  
  <style>
    :root {
      --primary: #4361ee;
      --primary-dark: #3a56d4;
      --secondary: #3fbc93;
      --sidebar-gradient: linear-gradient(135deg, #4361ee 0%, #3fbc93 100%);
      --sidebar-text: #ffffff;
      --sidebar-hover: rgba(255, 255, 255, 0.15);
      --sidebar-active: rgba(255, 255, 255, 0.25);
      --sidebar-icon: rgba(255, 255, 255, 0.8);
      --sidebar-border: rgba(255, 255, 255, 0.1);
      --sidebar-submenu-bg: rgba(0, 0, 0, 0.1);
      --danger: #ef476f;
      --success: #06d6a0;
      --warning: #ffd166;
      --info: #3a86ff;
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .material-icons {
      font-family: 'Material Icons';
      font-weight: normal;
      font-style: normal;
      font-size: 24px;
      display: inline-block;
      line-height: 1;
      text-transform: none;
      letter-spacing: normal;
      word-wrap: normal;
      white-space: nowrap;
      direction: ltr;
      -webkit-font-smoothing: antialiased;
      text-rendering: optimizeLegibility;
      -moz-osx-font-smoothing: grayscale;
      font-feature-settings: 'liga';
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      overflow-x: hidden;
    }
    
    /* SIDEBAR */
    .sidebar {
      width: 280px;
      background: var(--sidebar-gradient);
      display: flex;
      flex-direction: column;
      position: fixed;
      height: 100vh;
      transition: var(--transition);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      z-index: 1000;
      left: 0;
      top: 0;
    }

    .sidebar.collapsed {
      width: 80px;
    }

    /* SIDEBAR HEADER */
    .sidebar-header {
      padding: 24px 20px;
      text-align: center;
      border-bottom: 1px solid var(--sidebar-border);
      font-weight: 600;
      color: var(--sidebar-text);
      position: relative;
      flex-shrink: 0;
    }

    .sidebar-header span {
      font-size: 1.5rem;
      font-weight: 700;
      letter-spacing: 0.5px;
      display: inline-block;
      margin-top: 8px;
      transition: var(--transition);
    }

    .sidebar.collapsed .sidebar-header span {
      opacity: 0;
      width: 0;
      height: 0;
      overflow: hidden;
    }

    /* TOGGLE BUTTON */
    .sidebar-toggle {
      position: absolute;
      top: 50%;
      right: -15px;
      transform: translateY(-50%);
      background: white;
      border: none;
      border-radius: 50%;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
      font-size: 16px;
      transition: var(--transition);
      z-index: 10;
      color: var(--primary);
    }

    .sidebar-toggle:hover {
      background: var(--primary);
      color: white;
      transform: translateY(-50%) scale(1.1);
    }

    .sidebar-toggle .material-icons {
      font-size: 18px;
    }

    /* NAVIGASI */
    .sidebar-nav {
      flex: 1;
      padding: 16px 0;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    .sidebar-nav ul {
      list-style: none;
      padding: 0;
      margin: 0;
      height: 100%;
      display: flex;
      flex-direction: column;
    }

    .nav-item {
      position: relative;
    }

    .nav-item > a {
      display: flex;
      align-items: center;
      padding: 14px 20px;
      color: var(--sidebar-text);
      text-decoration: none;
      font-size: 16px;
      font-weight: 500;
      transition: var(--transition);
      white-space: nowrap;
      margin: 0 8px;
      border-radius: 6px;
    }

    .sidebar.collapsed .nav-item > a span {
      opacity: 0;
      width: 0;
      height: 0;
      display: inline-block;
    }

    .nav-item > a i {
      margin-right: 12px;
      color: var(--sidebar-icon);
      min-width: 24px;
      text-align: center;
      font-size: 24px;
      transition: var(--transition);
    }

    .sidebar.collapsed .nav-item > a i {
      margin-right: 0;
      font-size: 26px;
    }

    .nav-item > a:hover {
      background: var(--sidebar-hover);
    }

    .nav-item.active > a {
      background: var(--sidebar-active);
      font-weight: 600;
    }

    .nav-item.active > a i {
      color: white;
    }

    /* SUBMENU */
    .has-submenu > a::after {
      content: 'expand_more';
      font-family: 'Material Icons';
      position: absolute;
      right: 20px;
      transition: var(--transition);
      font-size: 20px;
      color: rgba(255, 255, 255, 0.6);
    }

    .has-submenu.active > a::after {
      transform: rotate(180deg);
      color: white;
    }

    .sidebar.collapsed .has-submenu > a::after {
      display: none;
    }

    .submenu {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease-out;
      background: var(--sidebar-submenu-bg);
      border-radius: 0 0 6px 6px;
      margin: 0 8px;
    }

    .has-submenu.active > .submenu {
      max-height: 1000px;
    }

    .submenu-item > a {
      display: flex;
      align-items: center;
      padding: 12px 20px 12px 56px;
      font-size: 15px;
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      transition: var(--transition);
      position: relative;
      white-space: nowrap;
      border-left: 3px solid transparent;
    }

    .submenu-item.active > a {
      color: white;
      font-weight: 500;
      border-left-color: white;
    }

    .sidebar.collapsed .submenu-item > a {
      padding: 12px 20px;
      justify-content: center;
    }

    .submenu-item > a:hover {
      color: white;
      background: rgba(255, 255, 255, 0.1);
      border-left-color: rgba(255, 255, 255, 0.3);
    }

    /* LOGOUT BUTTON */
    .sidebar-footer {
      padding: 16px;
      border-top: 1px solid var(--sidebar-border);
      flex-shrink: 0;
    }

    .logout-btn {
      display: flex;
      align-items: center;
      padding: 12px 16px;
      color: var(--sidebar-text);
      text-decoration: none;
      font-weight: 500;
      transition: var(--transition);
      white-space: nowrap;
      border-radius: 6px;
      background: rgba(255, 255, 255, 0.1);
      font-size: 16px;
      border: none;
      width: 100%;
      cursor: pointer;
    }

    .logout-btn i {
      margin-right: 12px;
      color: var(--sidebar-icon);
      min-width: 24px;
      text-align: center;
      font-size: 24px;
      transition: var(--transition);
    }

    .sidebar.collapsed .logout-btn span {
      opacity: 0;
      width: 0;
      height: 0;
      display: inline-block;
    }

    .sidebar.collapsed .logout-btn i {
      margin-right: 0;
      font-size: 26px;
    }

    .logout-btn:hover {
      background: rgba(239, 71, 111, 0.2);
      color: white;
    }

    .logout-btn:hover i {
      color: var(--danger);
    }

    /* MAIN CONTENT - INI YANG DIPERBAIKI */
    .main-content {
      margin-left: 280px;
      width: calc(100% - 280px);
      transition: var(--transition);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .sidebar.collapsed ~ .main-content {
      margin-left: 80px;
      width: calc(100% - 80px);
    }

    /* Content Header */
    .content-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 30px;
      background: white;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      position: sticky;
      top: 0;
      z-index: 90;
    }

    .content-header h1 {
      font-size: 24px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .header-actions {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    /* Profile Dropdown Styles */
    .profile-dropdown {
      position: relative;
      display: inline-block;
    }

    .profile-btn {
      display: flex;
      align-items: center;
      gap: 8px;
      background: none;
      border: none;
      cursor: pointer;
      color: #1e293b;
      font-weight: 500;
      padding: 5px 10px;
      border-radius: 20px;
      transition: all 0.3s;
    }

    .profile-btn:hover {
      background-color: rgba(67, 97, 238, 0.1);
    }

    .profile-dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      background-color: white;
      min-width: 200px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      z-index: 1;
      padding: 10px 0;
    }

    .profile-dropdown:hover .profile-dropdown-content {
      display: block;
    }

    .profile-dropdown-item {
      padding: 10px 15px;
      display: flex;
      align-items: center;
      gap: 10px;
      color: #1e293b;
      text-decoration: none;
      font-size: 14px;
    }

    .profile-dropdown-item:hover {
      background-color: rgba(67, 97, 238, 0.1);
      color: var(--primary);
    }

    .profile-dropdown-item i {
      color: #6c757d;
      width: 20px;
      text-align: center;
    }

    /* CONTENT BODY */
    .content-body {
      padding: 30px;
      flex: 1;
      background: #f8fafc;
    }

    /* STATS CARDS - DI TENGAH */
    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
      width: 100%;
      max-width: 900px;
      margin-left: auto;
      margin-right: auto;
    }

    .stat-card {
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      border-left: 4px solid var(--primary);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      text-align: center;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    .stat-card.green {
      border-left-color: var(--secondary);
    }

    .stat-card.orange {
      border-left-color: var(--warning);
    }

    .stat-card.blue {
      border-left-color: var(--info);
    }

    .stat-value {
      font-size: 28px;
      font-weight: 700;
      color: #1e293b;
      margin: 8px 0 4px;
    }

    .stat-label {
      font-size: 14px;
      color: #64748b;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .stat-label i {
      font-size: 20px;
    }

    /* WELCOME SECTION - TETAP DI KIRI */
    .welcome-container {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 30px;
    }

    .welcome-text {
      flex: 1;
    }

    .welcome-text h2 {
      font-size: 24px;
      margin-bottom: 8px;
      color: #1e293b;
    }

    .welcome-text p {
      color: #64748b;
      margin: 0;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }
      
      .sidebar.active {
        transform: translateX(0);
      }
      
      .main-content {
        margin-left: 0 !important;
        width: 100% !important;
      }
      
      .sidebar.collapsed {
        transform: translateX(-100%);
      }
      
      .sidebar-toggle {
        display: none;
      }

      .stats-container {
        grid-template-columns: 1fr;
      }

      .welcome-container {
        flex-direction: column;
        text-align: center;
      }
    }
  </style>
</head>
<body class="bg-gray-50 font-poppins text-gray-800">
  <!-- Overlay for mobile -->
  <div class="overlay" id="overlay"></div>

  <div class="flex min-h-screen">
    @include('components.sidebar-adminpelayananpublik')

    <main class="main-content transition-all duration-300 flex-1 min-h-screen flex flex-col" id="mainContent">
      @yield('content')
    </main>
  </div>

  @yield('scripts')
  @stack('scripts')
</body>
</html>
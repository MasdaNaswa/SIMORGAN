<aside class="sidebar" id="sidebar">
  <!-- Header -->
  <div class="sidebar-header">
    <span>Admin Pelayanan Publik</span>
    <button class="sidebar-toggle" id="sidebarToggle">
      <i class="material-icons">menu</i>
    </button>
  </div>

  <!-- Navigation -->
  <nav class="sidebar-nav">
    <ul>
      <!-- Dashboard -->
      <li class="nav-item">
        <a href="{{ route('adminpelayananpublik.dashboard') }}">
          <i class="material-icons">dashboard</i> <span>Dashboard</span>
        </a>
      </li>

      <!-- Kategori Laporan -->
      <li class="nav-item">
        <a href="{{ route('adminpelayananpublik.kategori.index') }}">
          <i class="material-icons">library_books</i> <span>Kategori Laporan</span>
        </a>
      </li>

      <!-- Kelola Akun OPD -->
      <li class="nav-item">
        <a href="{{ route('adminpelayananpublik.kelola-akun.index') }}">
          <i class="material-icons">account_circle</i> <span>Kelola Akun</span>
        </a>
      </li>

      <!-- Manajemen Laporan -->
      <li class="nav-item">
        <a href="{{ route('adminpelayananpublik.laporan.index') }}">
          <i class="material-icons">assignment</i> <span>Manajemen Laporan</span>
        </a>
      </li>

      <!-- Template Laporan -->
      <li class="nav-item">
        <a href="{{ route('adminpelayananpublik.template.index') }}">
          <i class="material-icons">description</i> <span>Template Laporan</span>
        </a>
      </li>

    </ul>
  </nav>

  <!-- Footer Logout -->
  <div class="sidebar-footer">
    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit" class="logout-btn">
        <i class="material-icons">logout</i> <span>Keluar</span>
      </button>
    </form>
  </div>
</aside>

<script>
  // Sort menu utama sidebar berdasarkan abjad
  document.addEventListener('DOMContentLoaded', () => {
    const navList = document.querySelector('.sidebar-nav > ul');
    const items = Array.from(navList.children);

    items.sort((a, b) => {
      const textA = a.querySelector('span')?.textContent.trim().toLowerCase() || '';
      const textB = b.querySelector('span')?.textContent.trim().toLowerCase() || '';
      return textA.localeCompare(textB);
    });

    items.forEach(item => navList.appendChild(item));
  });

  // Toggle sidebar collapse
  const sidebar = document.getElementById('sidebar');
  const toggleBtn = document.getElementById('sidebarToggle');
  const mainContent = document.querySelector('.main-content');

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));

    const icon = toggleBtn.querySelector('i');
    icon.textContent = sidebar.classList.contains('collapsed') ? 'chevron_right' : 'menu';
  });

  // Periksa status sidebar dari localStorage
  document.addEventListener('DOMContentLoaded', () => {
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (isCollapsed) {
      sidebar.classList.add('collapsed');
      const icon = toggleBtn.querySelector('i');
      icon.textContent = 'chevron_right';
    }
  });

  // Responsive handler
  function handleResize() {
    if (window.innerWidth <= 768) {
      sidebar.classList.remove('collapsed');
      const icon = toggleBtn.querySelector('i');
      icon.textContent = 'menu';
    }
  }

  window.addEventListener('resize', handleResize);
  handleResize();
</script>
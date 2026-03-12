<aside class="sidebar" id="sidebar">
  <!-- Header -->
  <div class="sidebar-header">
    <span>Admin RB</span>
    <button class="sidebar-toggle" id="sidebarToggle">
      <i class="material-icons">menu</i>
    </button>
  </div>

  <!-- Navigation -->
  <nav class="sidebar-nav">
    <ul>
      <!-- Dashboard -->
      <li class="nav-item">
        <a href="{{ route('adminrb.dashboard') }}">
          <i class="material-icons">dashboard</i> <span>Dashboard</span>
        </a>
      </li>

      <!-- RB -->
      <li class="nav-item has-submenu">
        <a href="#">
          <i class="material-icons">assessment</i> <span>RB</span>
        </a>
        <ul class="submenu">
          <!-- RB Berdampak -->
          <li class="submenu-item has-submenu">
            <a href="#">
              <span>RB Berdampak</span>
            </a>
            <ul class="submenu">
              <li class="submenu-item"><a href="{{ route('adminrb.rb-general.index') }}">RB General</a></li>
              <li class="submenu-item"><a href="{{ route('adminrb.rb-tematik.index') }}">RB Tematik</a></li>
            </ul>
          </li>
          <!-- SAKIP -->
          <li class="submenu-item has-submenu">
            <a href="#">
              <span>SAKIP</span>
            </a>
            <ul class="submenu">
              <li class="submenu-item"><a href="{{ route('adminrb.pk-bupati.index') }}">PK Bupati</a></li>
            </ul>
          </li>
        </ul>
      </li>

      <!-- Kelola Akun -->
      <li class="nav-item">
        <a href="{{ route('adminrb.kelola-akun.index') }}">
          <i class="material-icons">manage_accounts</i> <span>Kelola Akun</span>
        </a>
      </li>

      <!-- Kontrol Akses RB -->
      <li class="nav-item">
        <a href="{{ route('adminrb.aksesrb.index') }}">
          <i class="fas fa-lock text-blue-600 text-[20px]"></i> <span>Kontrol Akses RB</span>
        </a>
      </li>

      <ul class="submenu">
        <!-- RB Berdampak -->
        <li class="submenu-item has-submenu">
          <a href="#">
            <span>RB Berdampak</span>
          </a>
          <ul class="submenu">
            <li class="submenu-item"><a href="{{ route('adminrb.rb-general.index') }}">RB General</a></li>
            <li class="submenu-item"><a href="{{ route('adminrb.rb-tematik.index') }}">RB Tematik</a></li>
          </ul>
        </li>
      </ul>
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

    // Sort berdasarkan text di <span> atau teks dalam <a>
    items.sort((a, b) => {
      const textA = a.querySelector('span')?.textContent.trim().toLowerCase() || '';
      const textB = b.querySelector('span')?.textContent.trim().toLowerCase() || '';
      return textA.localeCompare(textB);
    });

    // Hapus semua, lalu append lagi sesuai urutan
    items.forEach(item => navList.appendChild(item));
  });

  // Toggle sidebar collapse
  const sidebar = document.getElementById('sidebar');
  const toggleBtn = document.getElementById('sidebarToggle');
  const mainContent = document.querySelector('.main-content');

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));

    // Update toggle icon
    const icon = toggleBtn.querySelector('i');
    if (sidebar.classList.contains('collapsed')) {
      icon.textContent = 'chevron_right';
    } else {
      icon.textContent = 'menu';
    }
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

  // Toggle submenu dengan auto-close lainnya
  document.querySelectorAll('.has-submenu > a').forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      const parent = this.parentElement;

      // Tutup submenu terbuka lainnya di level yang sama
      if (parent.parentElement) {
        const siblings = parent.parentElement.querySelectorAll('.has-submenu');
        siblings.forEach(sib => {
          if (sib !== parent) {
            sib.classList.remove('active');
          }
        });
      }

      parent.classList.toggle('active');
    });
  });

  // Responsive handler
  function handleResize() {
    if (window.innerWidth <= 768) {
      // Reset state di mobile
      sidebar.classList.remove('collapsed');
      const icon = toggleBtn.querySelector('i');
      icon.textContent = 'menu';
    }
  }

  window.addEventListener('resize', handleResize);
  handleResize();
</script>
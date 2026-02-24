<aside class="sidebar" id="sidebar">
  <!-- Header -->
  <div class="sidebar-header">
    <span>OPD</span>
    <button class="sidebar-toggle" id="sidebarToggle">
      <i class="material-icons">menu</i>
    </button>
  </div>

  <!-- Navigation -->
  <nav class="sidebar-nav">
    <ul>
      <li class="nav-item">
        <a href="{{ route('dashboard') }}">
          <i class="material-icons">dashboard</i> <span>Dashboard</span>
        </a>
      </li>


      <!-- Kelembagaan -->
      <li class="nav-item has-submenu">
        <a href="#">
          <i class="material-icons">account_balance</i> <span>Kelembagaan</span>
        </a>
        <ul class="submenu">
          <li class="submenu-item"><a href="{{ route('anjab-abk.index') }}"> Anjab & ABK</a></li>
          <li class="submenu-item"><a href="{{ route('petajab.index') }}">Peta Jabatan</a></li>
          <li class="submenu-item"><a href="{{ route('evajab.index') }}">Evaluasi Jabatan</a></li>
          <li class="submenu-item"><a href="{{ route('kematangan.index') }}">Kematangan Kelembagaan</a></li>
        </ul>
      </li>

      <!-- Pelayanan Publik -->
      <li class="nav-item has-submenu">
        <a href="#">
          <i class="material-icons">description</i> <span>Pelayanan Publik</span>
        </a>
        <ul class="submenu">
          <li class="submenu-item"><a href="{{ route('pelayanan-publik.index') }}">Dokumen</a></li>
        </ul>
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
              <li class="submenu-item"><a href="{{ route('rb-general.index') }}">RB General</a></li>
              <li class="submenu-item"><a href="{{ route('rb-tematik.index') }}">RB Tematik</a></li>
            </ul>
          </li>

          <!-- SAKIP -->
          <li class="submenu-item has-submenu">
            <a href="#">
              <span>SAKIP</span>
            </a>
            <ul class="submenu">
              <li class="submenu-item"><a href="{{ route('pk-bupati.index') }}">PK Bupati</a></li>
            </ul>
          </li>
        </ul>
      </li>
    </ul>
  </nav>

  <!-- Footer Logout -->
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

    // Auto open sidebar saat klik icon menu ketika collapsed
  document.querySelectorAll('.sidebar-nav a').forEach(link => {
    link.addEventListener('click', () => {
      // Jika sidebar sedang collapse
      if (sidebar.classList.contains('collapsed')) {
        sidebar.classList.remove('collapsed');
        localStorage.setItem('sidebarCollapsed', false);

        // Update icon toggle
        const icon = toggleBtn.querySelector('i');
        icon.textContent = 'menu';
      }
    });
  });

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

  const icon = toggleBtn.querySelector('i');

  if (sidebar.classList.contains('collapsed')) {
    icon.textContent = 'chevron_right';

    // 🔥 TUTUP SEMUA SUBMENU SAAT SIDEBAR DITUTUP
    document.querySelectorAll('.has-submenu.active').forEach(item => {
      item.classList.remove('active');
    });

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
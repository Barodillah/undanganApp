<footer class="footer">
  <div class="d-sm-flex justify-content-center justify-content-sm-between">
    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
      © <span id="currentYear"></span>
      <a href="/" target="_blank"><strong>JB Events</strong></a>.
      All rights reserved.
    </span>

    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">
      <span id="jb-rotating-text">Creating unforgettable events</span>
    </span>
  </div>
</footer>
<script>
  // Tahun otomatis
  document.getElementById('currentYear').textContent = new Date().getFullYear();

  // Rotating text
  const texts = [
    "Creating unforgettable events",
    "Smart event & ticketing platform",
    "Powered by passion & technology",
    "Your trusted event partner",
    "Built for memorable moments",
    "Made with ❤️ for your events",
    "Manage events faster & smarter",
    "From planning to execution",
    "Designed for event organizers",
    "One platform, endless events"
  ];

  let index = 0;
  const textElement = document.getElementById('jb-rotating-text');

  setInterval(() => {
    index = (index + 1) % texts.length;
    textElement.textContent = texts[index];
  }, 3000);
</script>

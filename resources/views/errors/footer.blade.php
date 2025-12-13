    <div class="col-12 text-center mt-xl-2">
        <a href="{{ url()->previous() }}"
          onclick="event.preventDefault(); history.back();"
          class="text-white font-weight-medium">
          <i class="fas fa-hand-point-left mr-2"></i>Back to previous page
        </a>

    </div>  
</div>
<div class="row mt-5">
    <div class="col-12 mt-xl-2">
        <p class="text-white font-weight-medium text-center">
            © <span id="jbYear"></span>
            <a href="/" class="text-white" target="_blank">
            <strong>JB Events</strong>
            </a>.
            <span id="jbFooterText">Creating unforgettable events</span>
        </p>
    </div>
<script>
  // Tahun otomatis
  document.getElementById('jbYear').textContent = new Date().getFullYear();

  // Rotating text
  const footerTexts = [
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

  let footerIndex = 0;
  const footerTextEl = document.getElementById('jbFooterText');

  setInterval(() => {
    footerIndex = (footerIndex + 1) % footerTexts.length;
    footerTextEl.textContent = footerTexts[footerIndex];
  }, 3000);
</script>

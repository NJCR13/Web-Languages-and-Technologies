document.addEventListener("DOMContentLoaded", () => {
  const buttons = document.querySelectorAll(".collapsible");
  buttons.forEach(btn => {
    btn.addEventListener("click", () => {
      buttons.forEach(other => {
        if (other !== btn && other.classList.contains("active")) {
          other.classList.remove("active");
          const content = other.nextElementSibling;
          content.style.height = `${content.scrollHeight}px`;
          setTimeout(() => {
            content.style.height = 0;
          }, 10);
        }
      });

      btn.classList.toggle("active");
      const panel = btn.nextElementSibling;
      if (btn.classList.contains("active")) {
        panel.style.height = `${panel.scrollHeight}px`;
      } else {
        panel.style.height = 0;
      }
    });

    const panel = btn.nextElementSibling;
    panel.style.height = 0;
  });
});
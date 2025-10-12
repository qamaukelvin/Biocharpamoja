document.addEventListener("DOMContentLoaded", () => {
    // Site content starts invisible
  });
  
  window.addEventListener("load", () => {
    const loader = document.getElementById("loader-wrapper");
  
    setTimeout(() => {
      loader.classList.add("hidden");
      document.body.classList.add("loaded");
  
      setTimeout(() => {
        loader.style.display = "none";
      }, 500);
    }, 4000);
  });
  
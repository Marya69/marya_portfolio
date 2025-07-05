
document.addEventListener('DOMContentLoaded', () => {
  const sections = [
    { id: "about-me",   file: "about-me.php"   },
    { id: "skills",     file: "skills.php"     },
    { id: "education",  file: "education.php"  },
    { id: "projects",   file: "projects.php"   },
    { id: "experience", file: "experience.php" },
    { id: "contact",    file: "contact.php"    }
  ];
  const totalSections = sections.length;
  let currentIdx = 0;

  const navLinks = document.querySelectorAll('[data-target]');
  const contentDiv = document.getElementById('content');

  // ease-in-out quad
  function easeInOutQuad(t) {
    return t < 0.5 ? 2*t*t : -1 + (4 - 2*t)*t;
  }

  // smoothly scroll contentDiv so that element's top aligns with container top
  function smoothScrollTo(element, duration = 2000) {
    const startY   = contentDiv.scrollTop;
    const targetY  = element.offsetTop;
    const distance = targetY - startY;
    let startTime  = null;

    function step(timestamp) {
      if (!startTime) startTime = timestamp;
      const time = timestamp - startTime;
      const progress = Math.min(time / duration, 1);
      contentDiv.scrollTop = startY + distance * easeInOutQuad(progress);
      if (time < duration) {
        requestAnimationFrame(step);
      }
    }
    requestAnimationFrame(step);
  }

  const updateActiveLink = (targetId) => {
    navLinks.forEach(link => {
      link.classList.toggle('active', link.dataset.target === targetId);
    });
  };

  const loadContent = (targetId) => {
    const idx = sections.findIndex(s => s.id === targetId);
    if (idx === -1) return;
    currentIdx = idx;
    updateActiveLink(targetId);

    fetch(sections[idx].file)
      .then(r => r.text())
      .then(html => {
        contentDiv.innerHTML = html;
        setTimeout(() => {
          contentDiv.classList.add('active');

          const el = document.getElementById(targetId);
          if (el) smoothScrollTo(el, 2000); // 2 seconds!

          if (targetId === "projects") initializeProjectTabs();
        }, 10);
      })
      .catch(err => console.error(err));
  };

  navLinks.forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      loadContent(link.dataset.target);
    });
  });
  document.body.addEventListener('click', e => {
    const target = e.target.closest('[data-target]');
    if (!target) return;
    e.preventDefault();
    loadContent(target.dataset.target);
  });

  // contentDiv.addEventListener('scroll', () => {
  //   const atBottom = contentDiv.scrollTop + contentDiv.clientHeight >= contentDiv.scrollHeight - 5;
  //   const atTop    = contentDiv.scrollTop <= 5;

  //   if (atBottom && currentIdx < totalSections - 1) {
  //     loadContent(sections[currentIdx + 1].id);
  //   } else if (atTop && currentIdx > 0) {
  //     loadContent(sections[currentIdx - 1].id);
  //   }
  // });

  // initial load
  loadContent(sections[0].id);
});


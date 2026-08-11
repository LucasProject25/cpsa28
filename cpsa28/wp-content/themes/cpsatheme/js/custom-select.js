// Minimal accessible custom select
(function () {
  const customSelects = document.querySelectorAll('.js-custom-select');
  if (!customSelects.length) return;

  customSelects.forEach(cs => {
    const btn = cs.querySelector('.cs-toggle');
    const list = cs.querySelector('.cs-list');
    const options = Array.from(list.querySelectorAll('[role="option"]'));
    const hidden = cs.querySelector('input[type="hidden"]');

    function open() {
      btn.setAttribute('aria-expanded', 'true');
      list.setAttribute('aria-hidden', 'false');
      list.classList.add('open');
      // focus first option
      setTimeout(() => options[0]?.focus?.(), 0);
      document.addEventListener('click', onDocClick);
    }
    function close() {
      btn.setAttribute('aria-expanded', 'false');
      list.setAttribute('aria-hidden', 'true');
      list.classList.remove('open');
      document.removeEventListener('click', onDocClick);
    }
    function onDocClick(e) {
      if (!cs.contains(e.target)) close();
    }

    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      if (list.classList.contains('open')) close(); else open();
    });

    // keyboard: open with ArrowDown or Enter
    btn.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowDown' || e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        open();
      }
    });

    options.forEach((opt, idx) => {
      opt.tabIndex = 0; // make focusable
      opt.addEventListener('click', () => selectOption(opt));
      opt.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          selectOption(opt);
        }
        if (e.key === 'ArrowDown') {
          e.preventDefault();
          const next = options[idx+1]; if (next) next.focus();
        }
        if (e.key === 'ArrowUp') {
          e.preventDefault();
          const prev = options[idx-1]; if (prev) prev.focus(); else btn.focus();
        }
        if (e.key === 'Escape') {
          close(); btn.focus();
        }
      });

      opt.addEventListener('mouseenter', () => {
        options.forEach(o => o.classList.remove('focused'));
        opt.classList.add('focused');
      });
    });

    function selectOption(opt) {
      const value = opt.dataset.value ?? '';
      const label = opt.textContent.trim();
      hidden.value = value;
      cs.dataset.value = value;
      btn.textContent = label;
      // keep the chevron visual by re-adding after text
      const chevron = document.createElement('span'); chevron.style.display='none';
      // close and focus back
      close();
      btn.focus();
    }

  });
})();

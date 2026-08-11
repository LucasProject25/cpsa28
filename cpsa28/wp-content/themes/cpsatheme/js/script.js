////////////////////////////////////////////////// CAROUSEL ////////////////////////////////////////////////////////////////////////////////////

jQuery(document).ready(function(){
    jQuery('.diaporama').slick({
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1
    });
});

jQuery(document).ready(function(){
    jQuery('.slider_nav').slick({
                infinite: true,
                slidesToShow: 5,
                slidesToScroll: 1
    });
});

// Sélectionne les éléments du slider
const items = document.querySelectorAll('.carousel__item--detail__vehicule');
const navItems = document.querySelectorAll('.carousel__nav-item');

// Si les éléments n'existent pas, on évite tout accès à classList
if (items.length > 0 && navItems.length > 0) {
    // Fonction pour activer une image
    function setActiveSlide(index) {
        // Reset : enlève les classes actives
        items.forEach(item => item.classList.remove('active'));
        navItems.forEach(nav => nav.classList.remove('active'));

        // S'assure que l'index est dans les bornes valides
        const i = Math.max(0, Math.min(index, items.length - 1, navItems.length - 1));

        // Ajoute la classe active sur le bon élément
        items[i].classList.add('active');
        navItems[i].classList.add('active');
    }

    // Ajoute les événements sur chaque miniature
    navItems.forEach((nav, index) => {
        nav.addEventListener('click', () => {
            setActiveSlide(index);
        });
    });

    // Active la première image au chargement
    setActiveSlide(0);

    // ---------- Nav thumbnails scroller (prev/next) ----------
    const navPrev = document.querySelector('.carousel__nav-prev');
    const navNext = document.querySelector('.carousel__nav-next');
    const navViewport = document.querySelector('.carousel__nav-viewport');
    const navTrack = document.querySelector('.carousel__nav-track');
    const navSlides = document.querySelectorAll('.carousel__nav-slide');

    const VISIBLE_COUNT = 5; // nombre de miniatures visibles
    let navIndex = 0; // index du premier élément visible

    function updateNavButtons() {
        if (!navPrev || !navNext) return;
        if (navSlides.length <= VISIBLE_COUNT) {
            navPrev.style.display = 'none';
            navNext.style.display = 'none';
            if (navTrack) navTrack.style.transform = 'translateX(0)';
            return;
        }
        navPrev.disabled = navIndex <= 0;
        navNext.disabled = navIndex + VISIBLE_COUNT >= navSlides.length;
        navPrev.classList.toggle('disabled', navPrev.disabled);
        navNext.classList.toggle('disabled', navNext.disabled);
    }

    function updateNavLayout() {
        if (!navViewport || !navTrack) return;
        const viewportWidth = navViewport.clientWidth;
        const itemWidth = Math.floor(viewportWidth / VISIBLE_COUNT);
        navSlides.forEach(slide => {
            slide.style.flex = `0 0 ${itemWidth}px`;
        });
        navTrack.style.transform = `translateX(${-navIndex * itemWidth}px)`;
        updateNavButtons();
    }

    if (navPrev && navNext && navViewport && navTrack) {
        navPrev.addEventListener('click', () => {
            if (navIndex > 0) {
                navIndex--;
                updateNavLayout();
            }
        });
        navNext.addEventListener('click', () => {
            if (navIndex + VISIBLE_COUNT < navSlides.length) {
                navIndex++;
                updateNavLayout();
            }
        });
        window.addEventListener('resize', updateNavLayout);
        // Initial layout
        updateNavLayout();
    }
}

////////////////////////////////////////////////// MODAL ////////////////////////////////////////////////////////////////////////////////////
// Sélectionne tous les boutons
const buttons = document.querySelectorAll(".option-buttons__btn, .myBtn");

// Sélectionne toutes les modals
const modals = document.querySelectorAll(".modal");

// Ouvre la bonne modal
buttons.forEach(btn => {
    btn.addEventListener("click", () => {
        const targetId = btn.dataset.modal;
        const modal = document.getElementById(targetId);
        // On supporte deux modes :
        // - Desktop : ancienne logique (display:block)
        // - Mobile : ajout d'une classe `open` pour jouer l'animation translateY
        const isMobile = window.matchMedia('(max-width: 1179px)').matches;
        if (isMobile) {
            // Ensure modal is present then add open class to trigger animation
            modal.style.display = 'block';
            // Force reflow to ensure transition from translateY(100%) -> 0
            // (helps on some mobiles)
            void modal.offsetWidth;
            modal.classList.add('open');
        } else {
            modal.style.display = "block";
        }
    });
});

// Ferme quand on clique sur la croix
modals.forEach(modal => {
    const closeBtn = modal.querySelector(".close");
    if (!closeBtn) return;
    closeBtn.addEventListener("click", () => {
        const isMobile = window.matchMedia('(max-width: 1179px)').matches;
        if (isMobile && modal.classList.contains('open')) {
            // remove open to trigger slide-down animation, then hide after transition
            modal.classList.remove('open');
            const onTransitionEnd = (e) => {
                if (e.propertyName !== 'transform') return;
                modal.style.display = 'none';
                modal.removeEventListener('transitionend', onTransitionEnd);
            };
            modal.addEventListener('transitionend', onTransitionEnd);
        } else {
            modal.style.display = 'none';
        }
    });
});


////////////////////////////////////////////////// POP-UP inscription ////////////////////////////////////////////////////////////////////////////////////
function confirmSignin() {
    alert("Votre inscription a été confirmée !\n\nVous allez rapidement recevoir un email afin que vous puissiez accéder à votre espace privé.\n\nMerci et à bientôt !")
}
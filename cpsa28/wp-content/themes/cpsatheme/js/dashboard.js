/* JS pour ouvrir une div lorsqu'on appuie sur l'un des boutons */

const btns = document.querySelectorAll(".toggleBtn");
const contents = document.querySelectorAll(".content")


btns.forEach(btn => {
    btn.addEventListener("click", () => {
        const target = btn.dataset.div; // Récupération des datas
        const targetContent = document.getElementById(target); // Cible le contenu selon le bouton sélectionné

        if (!targetContent) return;

        const isOpen = targetContent.classList.contains("open");

        // Fermeture
        contents.forEach(content => {
            content.classList.remove("open", "show-content");
        });

        // Ouverture
        if (!isOpen) {
            targetContent.classList.add("open");
        }
    });
});

// attendre la fin de l'animation du max-height
contents.forEach(content => {
    content.addEventListener("transitionend", (e) => {
        if (e.propertyName === "max-height" && content.classList.contains("open")) {
            content.classList.add("show-content");
        }
    });
});

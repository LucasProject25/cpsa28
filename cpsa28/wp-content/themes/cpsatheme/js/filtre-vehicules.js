document.addEventListener('DOMContentLoaded', () => {
    const filtres   = document.querySelectorAll('.filtre__link');
    const conteneur = document.getElementById('resultats-vehicules');

    function chargerVehicules(brand) {
        conteneur.innerHTML = '<p class="catalogue__title">Chargement...</p>';

        fetch(filtreParams.ajaxurl, {
            method:  'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body:    new URLSearchParams({
                action: 'filtrer_vehicules',
                nonce:  filtreParams.nonce,
                marque: brand,
            }).toString(),
        })
        .then(r => r.json())
        .then(({ success, data }) => {
            conteneur.innerHTML = success && data
                ? data
                : '<p class="catalogue__title">Aucun véhicule trouvé.</p>';
        })
        .catch(() => {
            conteneur.innerHTML = '<p>Erreur lors du chargement.</p>';
        });
    }

    filtres.forEach(lien => {
        lien.addEventListener('click', e => {
            e.preventDefault(); // empêche le href="" de remonter en haut

            filtres.forEach(f => f.classList.remove('filtre__link--actif'));
            lien.classList.add('filtre__link--actif');

            chargerVehicules(lien.dataset.brand);
        });
    });

});
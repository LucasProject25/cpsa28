(function () {
    const login = document.querySelector('#login');
    const signin = document.querySelector('#signin');
    const btns = document.querySelectorAll('.btnSwap');
    const btnLog = document.querySelector('.btnLogin');
    const btnSig = document.querySelector('.btnSignin');

    if (!login || !signin || !btns.length) return;

    // Initialement, afficher le login et cacher l'inscription
    login.style.display = 'block';
    btnLog.classList.add('myBtn--active');
    btnLog.classList.remove('myBtn--connexion');
    signin.style.display = 'none';
    signin.querySelectorAll("input").forEach(input => {
        input.disabled = true;
    });
    

    btns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();

            const target = btn.dataset.form; // récupère "login" ou "signin"

            //Si l'utilisateur clique sur "Inscription", cache le login et affiche l'inscription et inversement sinon
            if (target === 'signin') {
                login.style.display = 'none';
                login.querySelectorAll("input").forEach(input => {
                    input.disabled = true;
                });

                btnLog.classList.add('myBtn--connexion');
                btnLog.classList.remove('myBtn--active');
                
                signin.style.display = 'block';
                signin.querySelectorAll("input").forEach(input => {
                    input.disabled = false;
                });

                btnSig.classList.add('myBtn--active');
                btnSig.classList.remove('myBtn--connexion');
            } else if (target === 'login') {
                login.style.display = 'block';
                login.querySelectorAll("input").forEach(input => {
                    input.disabled = false;
                });

                btnLog.classList.add('myBtn--active');
                btnLog.classList.remove('myBtn--connexion');

                signin.style.display = 'none';
                signin.querySelectorAll("input").forEach(input => {
                    input.disabled = true;
                });

                btnSig.classList.add('myBtn--connexion');
                btnSig.classList.remove('myBtn--active');
            }
        });
    });
})();
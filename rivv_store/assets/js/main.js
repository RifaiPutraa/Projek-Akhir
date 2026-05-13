// RIVV STORE - Main JS

document.addEventListener('DOMContentLoaded', function () {

    // ---- Nominal Card Selection ----
    const nominalCards = document.querySelectorAll('.nominal-card');
    const nominalInput = document.getElementById('id_nominal');

    nominalCards.forEach(card => {
        card.addEventListener('click', function () {
            nominalCards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            if (nominalInput) {
                nominalInput.value = this.dataset.id;
            }
        });
    });

    // ---- Metode Pembayaran Selection ----
    const metodeCards = document.querySelectorAll('.metode-card');
    const metodeInput = document.getElementById('metode_pembayaran');

    metodeCards.forEach(card => {
        card.addEventListener('click', function () {
            metodeCards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            if (metodeInput) {
                metodeInput.value = this.dataset.nama;
            }
        });
    });

    // ---- Smooth scroll untuk anchor link ----
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // ---- Auto hide alert ----
    const alerts = document.querySelectorAll('.alert.auto-hide');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 3500);
    });
});

// Script for profile dropdown
const profileIcon = document.getElementById('profileIcon');
const profileMenu = document.getElementById('profileMenu');

profileIcon.addEventListener('click', function() {
    profileMenu.classList.toggle('show');
});

window.onclick = function(event) {
    if (!event.target.matches('#profileIcon')) {
        if (profileMenu.classList.contains('show')) {
            profileMenu.classList.remove('show');
        }
    }
};



document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const navContainer = document.querySelector('.nav-container');
    const overlay = document.getElementById('overlay');
    const navItems = document.querySelectorAll('.nav li');
    
    hamburger.addEventListener('click', function() {
        this.classList.toggle('active');
        navContainer.classList.toggle('active');
        overlay.classList.toggle('active');
        document.body.classList.toggle('no-scroll');
    });
    
    overlay.addEventListener('click', function() {
        hamburger.classList.remove('active');
        navContainer.classList.remove('active');
        this.classList.remove('active');
        document.body.classList.remove('no-scroll');
    });
    
    navItems.forEach(item => {
        item.addEventListener('click', function() {
            hamburger.classList.remove('active');
            navContainer.classList.remove('active');
            overlay.classList.remove('active');
            document.body.classList.remove('no-scroll');
        });
    });
});
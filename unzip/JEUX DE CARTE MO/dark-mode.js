document.addEventListener('DOMContentLoaded', function() {
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    const darkModeStylesheet = document.getElementById('dark-mode-stylesheet');
    const sunIcon = document.querySelector('.theme-toggle-sun');
    const moonIcon = document.querySelector('.theme-toggle-moon');

    
    function initDarkMode() {
        const savedMode = localStorage.getItem('darkMode');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        if (savedMode === 'dark' || (!savedMode && systemPrefersDark)) {
            enableDarkMode();
        } else {
            disableDarkMode();
        }
    }

  
    function enableDarkMode() {
        darkModeStylesheet.disabled = false;
        sunIcon.style.display = 'none';
        moonIcon.style.display = 'inline';
        localStorage.setItem('darkMode', 'dark');
        document.documentElement.setAttribute('data-theme', 'dark');
    }

    function disableDarkMode() {
        darkModeStylesheet.disabled = true;
        sunIcon.style.display = 'inline';
        moonIcon.style.display = 'none';
        localStorage.setItem('darkMode', 'light');
        document.documentElement.setAttribute('data-theme', 'light');
    }

    
    function toggleDarkMode() {
        if (darkModeStylesheet.disabled) {
            enableDarkMode();
        } else {
            disableDarkMode();
        }
    }

   
    darkModeToggle.addEventListener('click', toggleDarkMode);
    

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if (!localStorage.getItem('darkMode')) {
            if (e.matches) {
                enableDarkMode();
            } else {
                disableDarkMode();
            }
        }
    });

    
    initDarkMode();
});
// Navbar Blur
window.addEventListener('scroll', function () {
    var navbar = document.getElementById('navbar');
    if (window.scrollY > 0) {
        navbar.classList.add('bg-navbar-blur');
        navbar.classList.add('shadow-sm');
    } else {
        navbar.classList.remove('bg-navbar-blur');
        navbar.classList.remove('shadow-sm');
    }
});

// Theme update function for alert mode
document.addEventListener('DOMContentLoaded', function () {
    function updateAlertTheme() {
        const isDarkMode = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        const alertElements = document.querySelectorAll('.alert-mode');

        alertElements.forEach(function (element) {
            if (isDarkMode) {
                element.classList.add('alert-light-secondary');
                element.classList.remove('alert-light-info');
            } else {
                element.classList.add('alert-light-info');
                element.classList.remove('alert-light-secondary');
            }
        });
    }

    updateAlertTheme();

    const themeToggleButton = document.getElementById('toggle-dark');
    if (themeToggleButton) {
        themeToggleButton.addEventListener('click', function () {
            setTimeout(updateAlertTheme, 0);
        });
    }
});

// Scroll to top
const scrollToTopBtn = document.getElementById('scrollToTopBtn');

function toggleScrollToTopButton() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        scrollToTopBtn.style.display = 'block';
    } else {
        scrollToTopBtn.style.display = 'none';
    }
}

function smoothScrollTo(targetPosition, duration) {
    const start = window.scrollY || window.pageYOffset;
    const distance = targetPosition - start;
    let startTime = null;

    function scrollStep(timestamp) {
        if (!startTime) startTime = timestamp;
        const progress = timestamp - startTime;
        const scrollAmount = easeInOutCubic(progress, start, distance, duration);

        window.scrollTo(0, scrollAmount);

        if (progress < duration) {
            requestAnimationFrame(scrollStep);
        } else {
            window.scrollTo(0, targetPosition);
        }
    }

    requestAnimationFrame(scrollStep);
}

function easeInOutCubic(t, b, c, d) {
    t /= d / 2;
    if (t < 1) return c / 2 * t * t * t + b;
    t -= 2;
    return c / 2 * (t * t * t + 2) + b;
}

function scrollToTop() {
    smoothScrollTo(0, 600);
}

window.addEventListener('scroll', toggleScrollToTopButton);

scrollToTopBtn.addEventListener('click', scrollToTop);

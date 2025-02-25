//== Full page loader
$(document).ready(function () {
    setTimeout(() => {
        $('.full-page-loader').fadeOut();
    }, 300)
});


//== Theme Switcher
$(document).ready(function () {

    let theme = localStorage.getItem('theme');

    if (theme == 'dark') {
        $('body').attr('data-bs-theme', 'dark');
        $('.dark-theme-icon').addClass('d-none');
    } else {
        $('body').attr('data-bs-theme', 'light');
        $('.light-theme-icon').addClass('d-none');
    }

    $('.theme-icon').on('click', function () {
        let theme = localStorage.getItem('theme');
        theme == 'dark' ? localStorage.setItem('theme', 'light') : localStorage.setItem('theme', 'dark');
        window.location.reload();
    });

});




//== Sidebar Toggle
$(document).ready(function () {

    let sidebarAndContent = $('.sidebar-and-content');

    $('.sidebar-toggle-icon').on('click', function (e) {
        if (sidebarAndContent.hasClass("sidebar-collapsed")) {
            sidebarAndContent.removeClass('sidebar-collapsed');
            localStorage.setItem('sidebar-collapsed', 0)
        } else {
            sidebarAndContent.addClass('sidebar-collapsed');
            localStorage.setItem('sidebar-collapsed', 1)
        }
    })

    if (localStorage.getItem('sidebar-collapsed') == 1) {
        sidebarAndContent.addClass('sidebar-collapsed');
    }

    if (screen.width < 576) {
        sidebarAndContent.removeClass('sidebar-collapsed');
        localStorage.setItem('sidebar-collapsed', 0)
    }
});

//== Sidebar Nav Subitems Toggle
let sidebarNavLinks = document.querySelectorAll('.sidebar-nav-link');
sidebarNavLinks.forEach(function (toggleIcon) {
    toggleIcon.addEventListener('click', function (e) {

        let closestNavItem = toggleIcon.closest('.sidebar-nav-item');
        let subNavItems = closestNavItem.querySelector('.sidebar-nav-item-subitems');
        if (subNavItems) {
            e.preventDefault();
            subNavItems.classList.toggle('collapsed');
        }
    });
});



//===== Hide top level messages after 10 second
$(document).ready(function () {
    setTimeout(function () {
        $('.top-level-message').hide();
    }, 10000);
});


//===== Jquery Ajax Global Setup
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});


//===== Get Current Date in Format 12_Jan_2024__12_20_32_pm
function getCurrentFormattedDate() {
    const date = new Date();
    const day = String(date.getDate()).padStart(2, '0');
    const month = date.toLocaleString('default', { month: 'short' });
    const year = date.getFullYear();
    let hours = date.getHours();
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');
    const ampm = hours >= 12 ? 'pm' : 'am';

    hours = hours % 12 || 12;
    const formattedDate = `${day}_${month}_${year}__${hours}_${minutes}_${seconds}_${ampm}`;

    return formattedDate;
}





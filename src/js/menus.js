window.onscroll = function () { scrollFunction() };

function scrollFunction() {
    var headerNav = document.querySelector('#site-header');
    var pageScrolled = 'page-scrolled';
    var pageScrolledLimit = 35;

    if (document.body.scrollTop > pageScrolledLimit
        || document.documentElement.scrollTop > pageScrolledLimit) {
        headerNav.classList.add(pageScrolled);
    } else {
        headerNav.classList.remove(pageScrolled);
    }

    //console.log(document.documentElement.scrollTop);
}

// Initialize Banner Swiper
var bannerSwiper = new Swiper('#banner', {
    slidesPerView: 'auto',
    centeredSlides: true,
    spaceBetween: 20,
    autoplay: {
        delay: 5000,
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
});

// Initialize Swiper
var swiper = new Swiper('#providers > .swiper-container', {
    freeMode: true,
    slidesPerView: 3,
    loop: true,
    freeModeSticky: true,
    autoplay: {
        delay: 5000,
    },
});
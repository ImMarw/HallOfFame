document.addEventListener("DOMContentLoaded", function () {
    let carousel = new bootstrap.Carousel(document.querySelector("#hallOfFameCarousel"), {
        interval: 10000, // 10s
        pause: "hover",
        ride: "carousel"
    });
});
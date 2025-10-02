document.addEventListener('DOMContentLoaded', () => {
     // Initialize all sliders on the page
    document.querySelectorAll('.top_controls').forEach(sliderContainer => {
        const slider = sliderContainer.querySelector('.top_button');
        const prevButton = sliderContainer.querySelector('.prev-button'); 
        const nextButton = sliderContainer.querySelector('.next-button'); 

        const getItemWidth = () => {
            const firstItem = slider.querySelector('form');
            return firstItem ? firstItem.offsetWidth + 20 : 250;
        };

        nextButton.addEventListener('click', () => {
            slider.scrollBy({
                left: getItemWidth(),
                behavior: 'smooth'
            });
        });

        prevButton.addEventListener('click', () => {
            slider.scrollBy({
                left: -getItemWidth(),
                behavior: 'smooth'
            });
        });

        const checkScroll = () => {
            const atStart = slider.scrollLeft <= 10;
            const atEnd = slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10;
            
            prevButton.parentElement.classList.toggle('hidden', atStart);
            nextButton.parentElement.classList.toggle('hidden', atEnd);
        };

        slider.addEventListener('scroll', checkScroll);
        checkScroll(); 

        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(checkScroll, 100);
        });
    });
});
window.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('review-form');
    const errorMsg = document.getElementById('rating-error');
    const ratingInputs = document.querySelectorAll('input[name="rating"]');

    form.addEventListener('submit', function(e) {
        const ratingSelected = document.querySelector('input[name="rating"]:checked');

        if (!ratingSelected) {
            e.preventDefault();
            errorMsg.style.display = 'block';
            errorMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    ratingInputs.forEach(input => {
        input.addEventListener('change', () => {
            if (errorMsg.style.display === 'block') {
                errorMsg.style.display = 'none';
            }
        });
    });
});
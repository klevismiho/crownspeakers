
document.addEventListener('DOMContentLoaded', function () {
    const elementsToAnimate = document.querySelectorAll(
        '.wp-block-heru-logo-grid,' +
        '.wp-block-heru-split-card-with-highlights,' +
        '.wp-block-heru-split-with-image,' +
        '.wp-block-heru-split-card,' +
        '.wp-block-heru-testimonials,' +
        '.wp-block-heru-podcast,' +
        '.wp-block-heru-cta'
    );

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    // Add the animation class when element enters viewport
                    entry.target.classList.add('fade-in-up-visible');
                    // Unobserve after animation is triggered
                    observer.unobserve(entry.target);
                }
            });
        },
        {
            threshold: 0.1, // Trigger when 10% of element is visible
            rootMargin: '0px 0px -50px 0px', // Start animation 50px before element is fully visible
        }
    );

    elementsToAnimate.forEach((element) => {
        observer.observe(element);
    });
});
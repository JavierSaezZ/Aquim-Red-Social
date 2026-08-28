/* =========================================================
   DISTRIBUCIÓN DE BURBUJAS DECORATIVAS
   ========================================================= */

document.addEventListener('DOMContentLoaded', () => {
    const bubbles = [...document.querySelectorAll('.aquim-home-bubble')];

    if (!bubbles.length) return;

    const MARGIN = 15;
    const GAP = 25;

    // Reserva espacio para que las bubble no choquen durante su animación.
    const FLOAT_SAFETY = 45;
    const MAX_ATTEMPTS = 800;

    /* Utilidades de distribución
       --------------------------------------------------------- */

    function random(min, max) {
        return Math.random() * (max - min) + min;
    }

    function shuffle(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }

        return array;
    }

    function getSize(bubble) {
        const rect = bubble.getBoundingClientRect();

        return {
            width: rect.width,
            height: rect.height
        };
    }

    function collides(x, y, width, height, placed) {
        const radius1 = Math.max(width, height) / 2 + GAP + FLOAT_SAFETY;
        const centerX1 = x + width / 2;
        const centerY1 = y + height / 2;

        return placed.some(item => {
            const radius2 = Math.max(item.width, item.height) / 2 + FLOAT_SAFETY;
            const centerX2 = item.x + item.width / 2;
            const centerY2 = item.y + item.height / 2;
            const dx = centerX1 - centerX2;
            const dy = centerY1 - centerY2;
            const distance = Math.sqrt(dx * dx + dy * dy);

            return distance < radius1 + radius2;
        });
    }

    /* Movimiento individual
       --------------------------------------------------------- */

    function randomAnimation(bubble) {
        const x1 = random(-30, 30);
        const y1 = random(-35, 35);
        const x2 = random(-30, 30);
        const y2 = random(-35, 35);

        bubble.style.setProperty('--bubble-x1', `${x1}px`);
        bubble.style.setProperty('--bubble-y1', `${y1}px`);
        bubble.style.setProperty('--bubble-x2', `${x2}px`);
        bubble.style.setProperty('--bubble-y2', `${y2}px`);

        bubble.style.animationDuration = `${random(18, 32).toFixed(2)}s`;
        bubble.style.animationDelay = `${random(-20, 0).toFixed(2)}s`;
    }

    /* Posicionamiento sin solapamientos
       --------------------------------------------------------- */

    function positionBubbles() {
        bubbles.forEach(bubble => {
            bubble.classList.remove('is-ready');
        });

        const viewportWidth = window.innerWidth;
        const viewportHeight = window.innerHeight;
        const placed = [];

        // Cambiar el orden evita que una misma burbuja quede siempre relegada al último hueco.
        const randomOrder = shuffle([...bubbles]);

        randomOrder.forEach(bubble => {
            bubble.style.left = '';
            bubble.style.top = '';
            bubble.style.right = '';
            bubble.style.bottom = '';

            const { width, height } = getSize(bubble);
            const maxX = Math.max(MARGIN, viewportWidth - width - MARGIN);
            const maxY = Math.max(MARGIN, viewportHeight - height - MARGIN);

            let finalX = MARGIN;
            let finalY = MARGIN;
            let found = false;

            for (let attempt = 0; attempt < MAX_ATTEMPTS; attempt++) {
                const x = random(MARGIN, maxX);
                const y = random(MARGIN, maxY);

                if (!collides(x, y, width, height, placed)) {
                    finalX = x;
                    finalY = y;
                    found = true;
                    break;
                }
            }

            // Si no hay espacio con el margen completo, realiza otro intento con menor separación.
            if (!found) {
                for (let attempt = 0; attempt < MAX_ATTEMPTS; attempt++) {
                    const x = random(MARGIN, maxX);
                    const y = random(MARGIN, maxY);

                    const touching = placed.some(item => {
                        const centerX1 = x + width / 2;
                        const centerY1 = y + height / 2;
                        const centerX2 = item.x + item.width / 2;
                        const centerY2 = item.y + item.height / 2;
                        const distance = Math.hypot(centerX1 - centerX2, centerY1 - centerY2);

                        return distance <
                            Math.max(width, height) / 2 +
                            Math.max(item.width, item.height) / 2 +
                            15;
                    });

                    if (!touching) {
                        finalX = x;
                        finalY = y;
                        found = true;
                        break;
                    }
                }
            }

            bubble.style.left = `${finalX}px`;
            bubble.style.top = `${finalY}px`;

            randomAnimation(bubble);

            // La burbuja se hace visible únicamente al tener una posición definitiva.
            bubble.classList.add('is-ready');

            placed.push({
                x: finalX,
                y: finalY,
                width,
                height
            });
        });
    }

    /* Inicialización y redimensionado
       --------------------------------------------------------- */

    positionBubbles();

    let resizeTimer;

    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);

        resizeTimer = setTimeout(() => {
            positionBubbles();
        }, 250);
    });
});

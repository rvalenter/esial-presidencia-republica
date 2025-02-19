import confetti from "canvas-confetti";

export default {
    cofetti() {
        let params = {
            particleCount: 500, // Quantidade de confetes
            spread: 90, // O quanto eles se espalham
            startVelocity: 70, // Velocidade inicial
            origin: {x: 0, y: 0.5}, // Posição inicial na tela
            angle: 45 // Ângulo em que os confetes serão lançados
        };

        confetti(params);

        params.origin.x = 1;
        params.angle = 135;
        confetti(params);
    }
}

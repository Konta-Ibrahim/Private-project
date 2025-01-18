// Initialisation de QuaggaJS pour scanner les codes-barres
document.addEventListener('DOMContentLoaded', () => {
    const startScanner = () => {
        Quagga.init(
            {
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector("#scanner"), // Div pour la caméra
                },
                decoder: {
                    readers: ["code_128_reader", "ean_reader", "ean_8_reader"], // Formats supportés
                },
            },
            (err) => {
                if (err) {
                    console.error(err);
                    return;
                }
                console.log("Scanner prêt");
                Quagga.start();
            }
        );

        Quagga.onDetected((data) => {
            alert(`Code-barres détecté : ${data.codeResult.code}`);
            Quagga.stop();
        });
    };

    // Bouton pour démarrer le scanner
    document.querySelector("#startScanner").addEventListener("click", startScanner);
});

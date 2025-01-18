<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>

    <title>QR Code Scanner</title>
    <style>
        /* Add some basic styling */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        #qr-reader {
            width: 100%;
            max-width: 500px;
            height: 300px;
        }

        #output {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div>
        <div id="qr-reader"></div>
        <div id="output">
            <h3>QR Code Result:</h3>
            <p id="result">Waiting for scan...</p>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <script>
        // Initialize QR code scanner
        function startScanner() {
            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                // Show the result on successful scan
                document.getElementById('result').innerText = decodedText;
                // Optionally, stop the scanner after a successful scan
                html5QrCode.stop();
            };

            const qrCodeErrorCallback = (errorMessage) => {
                // Log errors
                console.error(errorMessage);
            };

            const config = {
                fps: 10, // Frame per second for scanning
                qrbox: 250, // Box size for QR scanning
                aspectRatio: 1.0, // Aspect ratio of the QR code box
            };

            // Start QR code scanning
            const html5QrCode = new Html5Qrcode("qr-reader");
            html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
                .catch((err) => {
                    // Handle initialization error
                    console.error("Error initializing QR scanner:", err);
                    alert("Error initializing camera. Please allow camera access.");
                });
        }

        // Start the scanner when the page loads
        window.onload = startScanner;
    </script>
</body>
</html>

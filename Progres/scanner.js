$(document).ready(function () {
    Quagga.init(
        {
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector("#scanner-container"),
                constraints: {
                    width: 640,
                    height: 480,
                    facingMode: "environment"
                }
            },
            decoder: {
                readers: ["code_128_reader", "ean_reader", "ean_8_reader"]
            }
        },
        function (err) {
            if (err) {
                console.error(err);
                alert("Error initializing barcode scanner.");
                return;
            }
            Quagga.start();
        }
    );

    Quagga.onDetected(function (data) {
        const barcode = data.codeResult.code;
        $("#barcode-result").text(`Detected Barcode: ${barcode}`);
        $("#product-form").show();

        $("#product-form").off("submit").on("submit", function (e) {
            e.preventDefault();

            const productName = $("#product-name").val();
            const productPrice = $("#product-price").val();
            const productImage = $("#product-image")[0].files[0];

            if (!productName || !productPrice || !productImage) {
                alert("Please fill in all fields and upload an image.");
                return;
            }

            const formData = new FormData();
            formData.append("barcode", barcode);
            formData.append("name", productName);
            formData.append("price", productPrice);
            formData.append("image", productImage);

            $.ajax({
                url: "add_product.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert(response);
                    $("#product-form")[0].reset();
                    $("#product-form").hide();
                },
                error: function () {
                    alert("Error adding product.");
                }
            });

            Quagga.stop();
        });
    });
});

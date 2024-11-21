let script = function () {
    this.showlock = function () {
        let dateobj = new Date();
        let months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        let year = dateobj.getFullYear();
        let month = months[dateobj.getMonth()];
        let date = dateobj.getDate();
        let hour = dateobj.getHours();
        let min = String(dateobj.getMinutes()).padStart(2, '0');
        let sec = String(dateobj.getSeconds()).padStart(2, '0');
        let timeFormatted = this.totwelvehourformat(hour);
        let timeElement = document.querySelector('.TimeAndDate');
        if (timeElement) {
            timeElement.innerHTML = `${month} ${date}, ${year} ${timeFormatted.time}:${min}:${sec} ${timeFormatted.am_pm}`;
        }
        setTimeout(this.showlock.bind(this), 1000);
    };

    this.totwelvehourformat = function (hour) {
        let am_pm = 'AM';
        if (hour >= 12) {
            am_pm = 'PM';
        }
        if (hour > 12) {
            hour = hour - 12;
        }
        if (hour === 0) {
            hour = 12;
        }
        return {
            time: hour,
            am_pm: am_pm
        };
    };

    this.registerEvents = function () {
        document.addEventListener('click', (e) => {
            let targetEl = e.target;
            let addToOrderClasses = ['productImage', 'ProductName', 'ProductPrice'];
            if (addToOrderClasses.some(className => targetEl.classList.contains(className))) {
                let productContainer = targetEl.closest('div.productColContainer');
                if (productContainer) {
                    let productName = productContainer.getAttribute('data-name');
                    let productPrice = parseFloat(productContainer.getAttribute('data-price'));
                    let productStock = parseInt(productContainer.getAttribute('data-stock'));

                    // Prevent ordering if stock is empty
                    if (productStock === 0) {
                        alert(`Sorry, "${productName}" is out of stock.`);
                        return;
                    }

                    let dialogForm = `
                        <h6 class="dialogProductName">${productName}<span class="floatRight">$${productPrice.toFixed(2)}</span></h6>
                        <input type="number" id="orderQTY" class="form-control" placeholder="Enter quantity..." min="1" max="${productStock}" />
                        <small class="text-muted">Stock available: ${productStock}</small>
                    `;

                    BootstrapDialog.confirm({
                        title: 'Add to Order',
                        type: BootstrapDialog.TYPE_DEFAULT,
                        message: dialogForm,
                        callback: (addOrder) => {
                            if (addOrder) {
                                let orderQTY = parseInt(document.getElementById('orderQTY').value);
                                
                                // Check for valid input
                                if (isNaN(orderQTY) || orderQTY < 1) {
                                    alert("Please enter a valid quantity greater than 0.");
                                    return;
                                }
                                if (orderQTY > productStock) {
                                    alert(`Quantity exceeds available stock! Maximum available: ${productStock}.`);
                                    return;
                                }

                                // Proceed if valid
                                console.log(`Added ${orderQTY} of ${productName} to the cart.`);
                            }
                        }
                    });
                }
            }
        });
    };

    this.initialize = function () {
        this.showlock();
        this.registerEvents();
    };
};

let loadscript = new script();
loadscript.initialize();

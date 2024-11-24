let script = function () {
    this.cartItems = []; // Array to store cart items

    // Function to update the cart table and total
    this.updateCartTable = function () {
        const tableBody = document.querySelector('#pos_items_tbl tbody');
        const totalElement = document.querySelector('.item_total_value');

        // Clear the table
        tableBody.innerHTML = '';

        let totalAmount = 0;

        // Populate the table with cart items
        this.cartItems.forEach((item, index) => {
            let itemTotal = item.price * item.quantity;
            totalAmount += itemTotal;

            // Append row with Edit and Delete buttons
            let row = `
                <tr data-index="${index}">
                    <td>${index + 1}</td>
                    <td>${item.name}</td>
                    <td>$${item.price.toFixed(2)}</td>
                    <td>${item.quantity}</td>
                    <td>$${itemTotal.toFixed(2)}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-item">Edit</button>
                        <button class="btn btn-sm btn-danger delete-item">Delete</button>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });

        // Update total amount
        totalElement.textContent = `$${totalAmount.toFixed(2)}`;

        // Attach event listeners to Edit and Delete buttons
        this.addCartEventListeners();
    };

    // Attach event listeners for Edit and Delete actions
    this.addCartEventListeners = function () {
        const editButtons = document.querySelectorAll('.edit-item');
        const deleteButtons = document.querySelectorAll('.delete-item');

        editButtons.forEach((button) => {
            button.addEventListener('click', (e) => {
                const rowIndex = e.target.closest('tr').dataset.index;
                this.editCartItem(rowIndex);
            });
        });

        deleteButtons.forEach((button) => {
            button.addEventListener('click', (e) => {
                const rowIndex = e.target.closest('tr').dataset.index;
                this.deleteCartItem(rowIndex);
            });
        });
    };

    // Edit a cart item
    this.editCartItem = function (index) {
        const item = this.cartItems[index];
        const dialogForm = `
            <h6>${item.name} ($${item.price.toFixed(2)})</h6>
            <input type="number" id="editQuantity" class="form-control" value="${item.quantity}" min="1" max="${item.stock}" />
            <small>Available stock: ${item.stock}</small>
        `;

        BootstrapDialog.show({
            title: 'Edit Item',
            message: dialogForm,
            buttons: [
                {
                    label: 'Save',
                    cssClass: 'btn-primary',
                    action: (dialog) => {
                        const newQuantity = parseInt(document.getElementById('editQuantity').value);
                        if (newQuantity > 0 && newQuantity <= item.stock) {
                            this.cartItems[index].quantity = newQuantity;
                            this.updateCartTable();
                            dialog.close();
                        } else {
                            Swal.fire('Invalid quantity', `Enter a value between 1 and ${item.stock}.`, 'warning');
                        }
                    }
                },
                {
                    label: 'Cancel',
                    action: (dialog) => dialog.close()
                }
            ]
        });
    };

    // Delete a cart item
    this.deleteCartItem = function (index) {
        Swal.fire({
            title: 'Are you sure?',
            text: `Do you want to remove "${this.cartItems[index].name}" from the cart?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                // Restore stock
                const item = this.cartItems[index];
                const productContainer = document.querySelector(`div.productColContainer[data-name="${item.name}"]`);
                if (productContainer) {
                    const currentStock = parseInt(productContainer.getAttribute('data-stock'));
                    productContainer.setAttribute('data-stock', currentStock + item.quantity);
                }

                // Remove the item
                this.cartItems.splice(index, 1);
                this.updateCartTable();
                Swal.fire('Deleted!', 'The item has been removed from your cart.', 'success');
            }
        });
    };


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
            hour -= 12;
        }
        if (hour === 0) {
            hour = 12;
        }
        return { time: hour, am_pm };
    };




    // Register click events for adding products to the cart
    this.registerEvents = function () {
        document.addEventListener('click', (e) => {
            const targetEl = e.target;
            if (targetEl.classList.contains('productImage') || targetEl.classList.contains('ProductName') || targetEl.classList.contains('ProductPrice')) {
                const productContainer = targetEl.closest('.productColContainer');
                if (productContainer) {
                    const name = productContainer.dataset.name;
                    const price = parseFloat(productContainer.dataset.price);
                    const stock = parseInt(productContainer.dataset.stock);

                    if (stock === 0) {
                        Swal.fire('Out of stock', `Sorry, "${name}" is not available right now.`, 'error');
                        return;
                    }

                    // Check if the item is already in the cart
                    const existingItem = this.cartItems.find((item) => item.name === name);
                    if (existingItem) {
                        Swal.fire('Item already in cart', `You can edit the quantity in the cart.`, 'info');
                        return;
                    }

                    // Prompt to add item to cart
                    const dialogForm = `
                        <h6>${name} ($${price.toFixed(2)})</h6>
                        <input type="number" id="addQuantity" class="form-control" placeholder="Enter quantity..." min="1" max="${stock}" />
                        <small>Available stock: ${stock}</small>
                    `;

                    BootstrapDialog.show({
                        title: 'Add to Cart',
                        message: dialogForm,
                        buttons: [
                            {
                                label: 'Add',
                                cssClass: 'btn-primary',
                                action: (dialog) => {
                                    const quantity = parseInt(document.getElementById('addQuantity').value);
                                    if (quantity > 0 && quantity <= stock) {
                                        this.cartItems.push({ name, price, quantity, stock: stock - quantity });
                                        productContainer.setAttribute('data-stock', stock - quantity);
                                        this.updateCartTable();
                                        dialog.close();
                                    } else {
                                        Swal.fire('Invalid quantity', `Enter a value between 1 and ${stock}.`, 'warning');
                                    }
                                }
                            },
                            {
                                label: 'Cancel',
                                action: (dialog) => dialog.close()
                            }
                        ]
                    });
                }
            }
        });
    };

    // Initialize the script
    this.initialize = function () {
        this.showlock();
        this.registerEvents();
    };
};

let loadscript = new script();
loadscript.initialize();

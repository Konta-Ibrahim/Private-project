//lecteur automatise de qrcode

const qrReader = new Html5Qrcode("qr-reader");
const barcodeField = document.getElementById("barcode");
const toggleFocusButton = document.getElementById("toggle-focus");
const form = document.getElementById("barcode-form");
let focusEnabled = true; // État initial : focus activé

// Fonction pour gérer le scan et soumettre le formulaire
function handleScan(decodedText) {
    if (decodedText) {
        barcodeField.value = decodedText; // Insérer le code scanné dans le champ
        submitForm(decodedText); // Soumettre automatiquement le formulaire
    }
}

// Fonction pour soumettre le formulaire via `fetch`
function submitForm(barcode) {
    const formData = new FormData();
    formData.append("barcode", barcode);
    formData.append("quantity", 1); // Quantité par défaut

    fetch("home.php", {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            console.log("Produit ajouté :", data.message);
            barcodeField.value = ""; // Réinitialise le champ
            if (focusEnabled) barcodeField.focus(); // Rétablit le focus si activé
        })
        .catch((error) => {
            console.error("Erreur lors de la soumission :", error);
        });
}

// Initialisation du lecteur QR avec la configuration nécessaire
qrReader
    .start(
        { facingMode: "environment" },
        { fps: 10 },
        (decodedText) => {
            handleScan(decodedText);
        }
    )
    .catch((err) => {
        console.error("Erreur de démarrage du lecteur QR :", err);
    });

// Gestion du focus initial
document.addEventListener("DOMContentLoaded", () => {
    if (focusEnabled) barcodeField.focus(); // Initialise le focus si activé
});

// Éviter que le champ perde le focus
barcodeField.addEventListener("focusout", () => {
    if (focusEnabled) barcodeField.focus(); // Rétablir le focus si activé
});

// Bouton pour activer/désactiver le focus
toggleFocusButton.addEventListener("click", () => {
    focusEnabled = !focusEnabled; // Bascule l'état
    toggleFocusButton.textContent = focusEnabled
        ? "Désactiver Focus"
        : "Activer Focus"; // Met à jour le texte du bouton
    if (focusEnabled) barcodeField.focus(); // Donne le focus immédiatement si activé
});

 
// Toggle dropdown visibility
const userBtn = document.getElementById('user-btn');
const userBox = document.querySelector('.user-box');

userBtn.addEventListener('click', () => {
    userBox.classList.toggle('show');
});

// Close dropdown when clicking outside
document.addEventListener('click', (event) => {
    if (!userBox.contains(event.target) && event.target !== userBtn) {
        userBox.classList.remove('show');
    }
});




// Calculator Logic
let display = document.getElementById('calc-display');

function appendNumber(num) {
    display.value += num;
}

function appendOperator(op) {
    display.value += op;
}

function appendDot() {
    if (!display.value.includes('.')) {
        display.value += '.';
    }
}

function clearDisplay() {
    display.value = '';
}

function calculate() {
    try {
        display.value = eval(display.value);
    } catch (e) {
        display.value = 'Error';
    }
}

// Payment Validation
function validatePayment() {
    const amountGiven = parseFloat(document.getElementById('amount_given').value);
    const grandTotal = parseFloat(document.querySelector('.grand-total').innerText);

    if (amountGiven >= grandTotal) {
        document.getElementById('payment-form').submit();
    } else {
        alert('Le montant donné est insuffisant.');
    }
}

function showPaymentDialog(paymentMethod) {
    Swal.fire({
        title: `Paiement par ${paymentMethod}`,
        text: `Vous avez choisi de payer par ${paymentMethod}. Confirmez-vous ce choix ?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Confirmer',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.isConfirmed) {
            // Envoyer le mode de paiement au serveur
            fetch('process_payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ payment_mode: paymentMethod }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Succès !', `Paiement par ${paymentMethod} effectué.`, 'success');
                } else {
                    Swal.fire('Erreur', 'Une erreur est survenue lors du paiement.', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Erreur', 'Une erreur est survenue lors de la communication avec le serveur.', 'error');
            });
        }
    });
}
function validatePayment() {
    const grandTotal = parseFloat(document.querySelector('.grand-total').innerText);
    const amountGiven = parseFloat(document.getElementById('amount_given').value);

    if (amountGiven >= grandTotal) {
        Swal.fire({
            title: 'Paiement validé !',
            text: `Le montant donné est suffisant. Merci pour votre achat !`,
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            // Soumettre le formulaire ou effectuer une autre action
            document.getElementById('payment-form').submit();
        });
    } else {
        Swal.fire({
            title: 'Erreur',
            text: 'Le montant donné est insuffisant.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }
}

document.getElementById('toggle-buttons').addEventListener('click', function () {
    const eBtn = document.querySelector('.e-btn');
    if (eBtn.style.display === 'none') {
        eBtn.style.display = 'flex';
    } else {
        eBtn.style.display = 'none';
    }
});



document.getElementById('toggle-theme').addEventListener('click', function () {
    const body = document.body;
    if (body.classList.contains('dark-mode')) {
        body.classList.remove('dark-mode');
        body.classList.add('light-mode');
        this.textContent = 'Mode Sombre';
        localStorage.setItem('theme', 'light');
    } else {
        body.classList.remove('light-mode');
        body.classList.add('dark-mode');
        this.textContent = 'Mode Clair';
        localStorage.setItem('theme', 'dark');
    }
});

// Charger le mode sauvegardé
const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'dark') {
    document.body.classList.add('dark-mode');
    document.getElementById('toggle-theme').textContent = 'Mode Clair';
} else {
    document.body.classList.add('light-mode');
    document.getElementById('toggle-theme').textContent = 'Mode Sombre';
}


// Fonction pour afficher une boîte de dialogue selon le mode de paiement
function showPaymentDialog(paymentMethod) {
    let htmlContent = '';

    if (paymentMethod === 'Cash') {
        htmlContent = `
            <label for='amount_given'>Montant Donné :</label>
            <input type='number' id='amount_given' class='swal2-input' placeholder='Entrez le montant' min='0' step='0.01'>
        `;
    } else {
        htmlContent = `
            <p>Confirmez le paiement par <strong>${paymentMethod}</strong>.</p>
        `;
    }

    Swal.fire({
        title: paymentMethod,
        html: htmlContent,
        showCancelButton: true,
        confirmButtonText: 'Valider',
        preConfirm: () => {
            if (paymentMethod === 'Cash') {
                const amountGiven = document.getElementById('amount_given').value;
                if (!amountGiven || parseFloat(amountGiven) < grandTotal) {
                    Swal.showValidationMessage('Le montant donné est insuffisant.');
                }
                return { amountGiven: parseFloat(amountGiven) };
            } else {
                return { paymentMethod }; // Pour les autres méthodes
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            if (paymentMethod === 'Cash') {
                processCashPayment(result.value.amountGiven);
            } else {
                processOtherPayment(paymentMethod);
            }
        }
    });
}

// Fonction pour traiter le paiement en espèces
function processCashPayment(amountGiven) {
    if (amountGiven >= grandTotal) {
        const change = amountGiven - grandTotal;
        Swal.fire({
            icon: 'success',
            title: 'Paiement Validé',
            html: `Merci pour votre paiement. <br> Monnaie à rendre : <strong>${change.toFixed(2)}</strong>`,
            confirmButtonText: 'OK'
        }).then(() => {
            submitPayment('Cash', amountGiven);
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Montant Insuffisant',
            text: 'Veuillez saisir un montant suffisant.'
        });
    }
}

// Fonction pour traiter les autres modes de paiement
function processOtherPayment(paymentMethod) {
    Swal.fire({
        icon: 'success',
        title: 'Paiement Validé',
        html: `Merci pour votre paiement via <strong>${paymentMethod}</strong>.`,
        confirmButtonText: 'OK'
    }).then(() => {
        submitPayment(paymentMethod);
    });
}

// Fonction pour soumettre les données de paiement au serveur
function submitPayment(paymentMethod, amountGiven = 0) {
    const paymentData = {
        paymentMethod,
        amountGiven,
        grandTotal,
        ticketNumber: generateTicketNumber()
    };

    // Exemple de soumission (à remplacer par un appel AJAX réel si nécessaire)
    console.log('Données de paiement envoyées au serveur :', paymentData);
    generateReceipt(paymentData);
}

// Fonction pour générer un reçu
function generateReceipt(paymentData) {
    const receipt = `
        <div>
            <h3>Reçu de Paiement</h3>
            <p>Numéro de Ticket : ${paymentData.ticketNumber}</p>
            <p>Date : ${new Date().toLocaleString()}</p>
            <p>Montant Total : ${grandTotal.toFixed(2)}</p>
            <p>Mode de Paiement : ${paymentData.paymentMethod}</p>
            ${paymentData.paymentMethod === 'Cash' ? `<p>Montant Donné : ${paymentData.amountGiven.toFixed(2)}</p>` : ''}
        </div>
    `;

    Swal.fire({
        title: 'Reçu de Paiement',
        html: receipt,
        confirmButtonText: 'Imprimer'
    }).then(() => {
        window.print(); // Imprime le reçu
    });
}

// Fonction pour générer un numéro de ticket unique
function generateTicketNumber() {
    return 'TCKT-' + Math.floor(100000 + Math.random() * 900000);
}

// Variable simulée pour le montant total
const grandTotal = 150.00; // Exemple de montant total (à remplacer dynamiquement)



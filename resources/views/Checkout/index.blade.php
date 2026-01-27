<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Checkout - FreshMart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-light">

@include('layouts.header')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-4">

                    <h4 class="mb-4 text-center fw-bold">
                        <i class="fa-solid fa-lock text-success me-2"></i>
                        Pago seguro
                    </h4>

                    <form method="POST" action="{{ route('checkout.pagar') }}" novalidate>
                        @csrf

                        {{-- NOMBRE --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre en la tarjeta</label>
                            <input type="text"
                                   class="form-control"
                                   placeholder="Juan Pérez"
                                   required>
                        </div>

                        {{-- TARJETA --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Número de tarjeta
                                <span class="float-end text-muted">
                                    <i class="fa-brands fa-cc-visa me-1"></i>
                                    <i class="fa-brands fa-cc-mastercard"></i>
                                </span>
                            </label>

                            <input type="text"
                                   id="cardNumber"
                                   class="form-control"
                                   placeholder="4242 4242 4242 4242"
                                   inputmode="numeric"
                                   maxlength="19"
                                   required>
                        </div>

                        <div class="row">
                            {{-- EXPIRACIÓN --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Expiración</label>

                                <input type="text"
                                       id="expiry"
                                       class="form-control"
                                       placeholder="MM/YY"
                                       maxlength="5"
                                       inputmode="numeric"
                                       required>

                                <div class="invalid-feedback">
                                    La tarjeta está vencida o la fecha no es válida.
                                </div>
                            </div>

                            {{-- CVV --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    CVV
                                    <i class="fa-solid fa-circle-info text-muted"
                                       title="Código de seguridad"></i>
                                </label>

                                <input type="text"
                                       id="cvv"
                                       class="form-control"
                                       placeholder="***"
                                       maxlength="4"
                                       inputmode="numeric"
                                       autocomplete="off"
                                       required>
                            </div>
                        </div>

                        <button type="submit"
                                class="btn btn-success w-100 py-2 fw-bold mt-3">
                            <i class="fa-solid fa-credit-card me-2"></i>
                            Confirmar pago
                        </button>

                        <p class="text-center text-muted small mt-3">
                            🔒 Transacción protegida con cifrado SSL
                        </p>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
    /* ========= TARJETA ========= */
    const cardInput = document.getElementById('cardNumber');
    cardInput.addEventListener('input', () => {
        let value = cardInput.value.replace(/\D/g, '').substring(0, 16);
        cardInput.value = value.replace(/(.{4})/g, '$1 ').trim();
    });

    /* ========= EXPIRACIÓN ========= */
    const expiryInput = document.getElementById('expiry');

    expiryInput.addEventListener('input', () => {
        let value = expiryInput.value.replace(/\D/g, '').substring(0, 4);

        if (value.length >= 3) {
            value = value.substring(0, 2) + '/' + value.substring(2);
        }

        expiryInput.value = value;
        expiryInput.classList.remove('is-invalid');
    });

    expiryInput.addEventListener('blur', () => {
        const value = expiryInput.value;
        if (!value.includes('/')) return;

        const [month, year] = value.split('/').map(Number);

        const now = new Date();
        const currentMonth = now.getMonth() + 1;
        const currentYear = now.getFullYear() % 100;

        if (
            month < 1 || month > 12 ||
            year < currentYear ||
            (year === currentYear && month < currentMonth)
        ) {
            expiryInput.classList.add('is-invalid');
        } else {
            expiryInput.classList.remove('is-invalid');
        }
    });

    /* ========= CVV ENMASCARADO ========= */
    const cvvInput = document.getElementById('cvv');
    let realCVV = '';

    cvvInput.addEventListener('input', (e) => {
        const char = e.data;

        // Borrar
        if (char === null) {
            realCVV = realCVV.slice(0, -1);
        }
        // Solo números
        else if (/\d/.test(char) && realCVV.length < 4) {
            realCVV += char;
        }

        // Mostrar solo asteriscos
        cvvInput.value = '*'.repeat(realCVV.length);
    });

    /* ========= BLOQUEO SUBMIT ========= */
    document.querySelector('form').addEventListener('submit', function (e) {
        if (expiryInput.classList.contains('is-invalid') || realCVV.length < 3) {
            e.preventDefault();
            cvvInput.focus();
        }
    });
</script>

</body>
</html>

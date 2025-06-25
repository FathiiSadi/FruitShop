<?php

use app\components\PaymentComponent;
?>

<body>
    <form id="payment-form" method="POST" action="/checkout/process-payment">
        <?= \yii\helpers\Html::hiddenInput('_csrf', Yii::$app->request->csrfToken) ?>

        <div class="one-liner mt-150 mb-150 justify-content-center">
            <!-- Card Number Frame -->
            <div class="input-container card-number">
                <label for="card-number">Card number</label>
                <div class="card-frame" data-element="card-number"></div>
                <div class="icon-container payment-method">
                </div>
                <p class="error-message__card-number"></p>
            </div>

            <button id="pay-button" disabled>
                £<?= number_format($paymentModel->amount, 2) ?>
            </button>
        </div>

        <p class="error-message"></p>
        <p class="success-payment-message"></p>

        <?php if (isset($paymentModel->payment_status) && $paymentModel->payment_status === 'failed'): ?>
            <div class="alert alert-danger">
                Payment failed. <?= \yii\helpers\Html::a('Try again', ['/checkout/payment'], ['class' => 'alert-link']) ?>.
            </div>
        <?php endif; ?>
    </form>

    <script src="https://cdn.checkout.com/js/framesv2.min.js"></script>
    <script>
        var payButton = document.getElementById("pay-button");
        var form = document.getElementById("payment-form");

        Frames.init(<?php env('PUBLIC_KEY') ?>);

        var logos = generateLogos();

        function generateLogos() {
            var logos = {};
            logos["card-number"] = {
                src: "card",
                alt: "card number logo",
            };
            logos["expiry-date"] = {
                src: "exp-date",
                alt: "expiry date logo",
            };
            logos["cvv"] = {
                src: "cvv",
                alt: "cvv logo",
            };
            return logos;
        }

        var errors = {};
        errors["card-number"] = "Please enter a valid card number";
        errors["expiry-date"] = "Please enter a valid expiry date";
        errors["cvv"] = "Please enter a valid cvv code";

        Frames.addEventHandler(
            Frames.Events.FRAME_VALIDATION_CHANGED,
            onValidationChanged
        );

        function onValidationChanged(event) {
            var e = event.element;

            if (event.isValid || event.isEmpty) {
                if (e === "card-number" && !event.isEmpty) {
                    showPaymentMethodIcon();
                }
                setDefaultIcon(e);
                clearErrorIcon(e);
                clearErrorMessage(e);
            } else {
                if (e === "card-number") {
                    clearPaymentMethodIcon();
                }
                setDefaultErrorIcon(e);
                setErrorIcon(e);
                setErrorMessage(e);
            }
        }

        function clearErrorMessage(el) {
            var selector = ".error-message__" + el;
            var message = document.querySelector(selector);
            message.textContent = "";
        }

        function clearErrorIcon(el) {
            var logo = document.getElementById("icon-" + el + "-error");
            logo.style.removeProperty("display");
        }

        function showPaymentMethodIcon(parent, pm) {
            if (parent) parent.classList.add("show");

            var logo = document.getElementById("logo-payment-method");
            if (pm) {
                var name = pm.toLowerCase();
                logo.setAttribute("src", "/images/card-icons/" + name + ".svg");
                logo.setAttribute("alt", pm || "payment method");
            }
            logo.style.removeProperty("display");
        }

        function clearPaymentMethodIcon(parent) {
            if (parent) parent.classList.remove("show");

            var logo = document.getElementById("logo-payment-method");
            logo.style.setProperty("display", "none");
        }

        function setErrorMessage(el) {
            var selector = ".error-message__" + el;
            var message = document.querySelector(selector);
            message.textContent = errors[el];
        }

        function setDefaultIcon(el) {
            var selector = "icon-" + el;
            var logo = document.getElementById(selector);
            logo.setAttribute("src", "/images/card-icons/" + logos[el].src + ".svg");
            logo.setAttribute("alt", logos[el].alt);
        }

        function setDefaultErrorIcon(el) {
            var selector = "icon-" + el;
            var logo = document.getElementById(selector);
            logo.setAttribute("src", "/images/card-icons/" + logos[el].src + "-error.svg");
            logo.setAttribute("alt", logos[el].alt);
        }

        function setErrorIcon(el) {
            var logo = document.getElementById("icon-" + el + "-error");
            logo.style.setProperty("display", "block");
        }


        Frames.addEventHandler(
            Frames.Events.CARD_TOKENIZATION_FAILED,
            onCardTokenizationFailed
        );

        function onCardTokenizationFailed(error) {
            console.log("CARD_TOKENIZATION_FAILED: %o", error);
            Frames.enableSubmitForm();
        }

        Frames.addEventHandler(
            Frames.Events.PAYMENT_METHOD_CHANGED,
            paymentMethodChanged
        );

        function paymentMethodChanged(event) {
            var pm = event.paymentMethod;
            let container = document.querySelector(".icon-container.payment-method");

            if (!pm) {
                clearPaymentMethodIcon(container);
            } else {
                clearErrorIcon("card-number");
                showPaymentMethodIcon(container, pm);
            }
        }

        form.addEventListener("submit", onSubmit);

        function onSubmit(event) {
            event.preventDefault();
            Frames.submitCard();
        }



        Frames.addEventHandler(
            Frames.Events.CARD_VALIDATION_CHANGED,
            cardValidationChanged
        );

        function cardValidationChanged() {
            payButton.disabled = !Frames.isCardValid();
        }

        Frames.addEventHandler(Frames.Events.CARD_TOKENIZED, onCardTokenized);

        function onCardTokenized(event) {
            console.log("Card tokenization completed: %o", event);

            document.querySelectorAll('input[name="token"]').forEach(el => el.remove());
            document.querySelectorAll('input[name="attempt_3ds"]').forEach(el => el.remove());

            var tokenInput = document.createElement("input");
            tokenInput.type = "hidden";
            tokenInput.name = "token";
            tokenInput.value = event.token;
            form.appendChild(tokenInput);

            var threeDSInput = document.createElement("input");
            threeDSInput.type = "hidden";
            threeDSInput.name = "attempt_3ds";
            threeDSInput.value = "true";
            form.appendChild(threeDSInput);

            form.submit();
        }
    </script>
</body>
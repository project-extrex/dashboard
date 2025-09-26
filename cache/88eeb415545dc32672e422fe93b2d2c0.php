<?php $__env->startSection('title', 'CheckOut'); ?>

<?php $__env->startSection('content'); ?>
<script src="https://js.stripe.com/v3/"></script>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">

<div class="bg-white p-6 rounded shadow-md w-full max-w-md">
    <h1 class="text-2xl font-bold mb-4">Checkout</h1>
    <div id="payment-element"></div>
    <button id="submit" class="mt-4 w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">
        Pay $20
    </button>
    <div id="payment-message" class="text-red-500 mt-2"></div>
</div>

<script>
const stripe = Stripe("<?php echo e($stripe_publicable_key); ?>"); // Correct embedding

async function initPayment() {
    const res = await fetch("/payment/create", {
        method: "POST",
        headers: { "Content-Type": "application/json" }
    });
    const data = await res.json();
    const clientSecret = data.clientSecret;

    const elements = stripe.elements({ clientSecret });
    const paymentElement = elements.create("payment");
    paymentElement.mount("#payment-element");

    document.querySelector("#submit").addEventListener("click", async () => {
        const { error } = await stripe.confirmPayment({
            elements,
            confirmParams: { return_url: "<?php echo e($redr); ?>" },
        });

        if (error) {
            document.querySelector("#payment-message").textContent = error.message;
        }
    });
}

initPayment();
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make("../layout", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /project/workspace/theme/extrax/payment/checkout.blade.php ENDPATH**/ ?>
<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const cardName = ref('Habib Hossain')
const cardNumber = ref('')
const cardExpiry = ref('')
const cardCvc = ref('')
const zipCode = ref('4100')

const isProcessing = ref(false)
const showSuccessModal = ref(false)

function formatCardNumber(e: Event) {
    const target = e.target as HTMLInputElement
    let v = target.value.replace(/\D/g, '').substring(0, 16)
    let parts = []
    for (let i = 0; i < v.length; i += 4) {
        parts.push(v.substring(i, i + 4))
    }
    cardNumber.value = parts.join(' ')
}

function formatExpiry(e: Event) {
    const target = e.target as HTMLInputElement
    let v = target.value.replace(/\D/g, '').substring(0, 4)
    if (v.length >= 3) {
        cardExpiry.value = v.substring(0, 2) + '/' + v.substring(2, 4)
    } else {
        cardExpiry.value = v
    }
}

function triggerExpressPay() {
    showSuccessModal.value = true
}

function handlePaymentSubmit() {
    isProcessing.value = true
    setTimeout(() => {
        isProcessing.value = false
        showSuccessModal.value = true
    }, 1000)
}
</script>

<template>
    <Head title="Payment Checkout" />

    <div class="checkout-grid">
        <!-- LEFT: STRIPE PAYMENT FORM -->
        <div class="checkout-card">
            <div class="card-title">
                <span>Pay Consultation Fee</span>
                <span class="powered-by">Powered by <strong>Stripe</strong></span>
            </div>

            <!-- EXPRESS PAY BUTTON -->
            <div class="express-pay-box">
                <div class="express-pay-title">Express Checkout</div>
                <button class="express-btn" type="button" @click="triggerExpressPay">
                    <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                        <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 6.35c.66-.82 1.11-1.96.99-3.1-.96.04-2.13.64-2.81 1.44-.61.71-1.14 1.87-1 2.99 1.08.08 2.17-.51 2.82-1.33z"/>
                    </svg>
                    Pay with Apple Pay / Google Pay
                </button>
            </div>

            <div class="divider-or">Or pay with card</div>

            <!-- CREDIT CARD FORM -->
            <form @submit.prevent="handlePaymentSubmit">
                <div class="form-group">
                    <label for="cardName">Name on Card</label>
                    <div class="input-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input id="cardName" v-model="cardName" type="text" class="form-control has-icon" required placeholder="Full Name" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="cardNumber">Card Number</label>
                    <div class="input-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                        </svg>
                        <input id="cardNumber" :value="cardNumber" type="text" class="form-control has-icon card-input" placeholder="4242 •••• •••• 4242" maxlength="19" required @input="formatCardNumber" />
                    </div>
                </div>

                <div class="form-row-dual">
                    <div class="form-group">
                        <label for="cardExpiry">Expiration Date</label>
                        <input id="cardExpiry" :value="cardExpiry" type="text" class="form-control card-input" placeholder="MM / YY" maxlength="5" required @input="formatExpiry" />
                    </div>

                    <div class="form-group">
                        <label for="cardCvc">CVC / CVC2</label>
                        <input id="cardCvc" v-model="cardCvc" type="password" class="form-control card-input" placeholder="123" maxlength="4" required />
                    </div>
                </div>

                <div class="form-group">
                    <label for="zipCode">Billing Postal Code</label>
                    <input id="zipCode" v-model="zipCode" type="text" class="form-control" required />
                </div>

                <div class="submit-wrap">
                    <button type="submit" class="btn-submit-pay" :disabled="isProcessing">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                        </svg>
                        {{ isProcessing ? 'Processing Payment...' : 'Pay $120.00 Now' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- RIGHT: ORDER SUMMARY -->
        <div class="summary-card">
            <h3>Order Summary</h3>

            <div class="doctor-item">
                <img src="https://images.unsplash.com/photo-1537368910025-700350fe46c7?auto=format&fit=crop&q=80&w=150" alt="Dr. Marcus Vance" />
                <div>
                    <b>Dr. Marcus Vance</b>
                    <span>Neurology Telehealth Visit</span>
                </div>
            </div>

            <div class="price-breakdown">
                <div class="price-row">
                    <span class="lbl">Consultation Fee</span>
                    <span class="val">$120.00</span>
                </div>
                <div class="price-row">
                    <span class="lbl">Platform Booking Fee</span>
                    <span class="val-free">FREE</span>
                </div>
                <div class="price-row">
                    <span class="lbl">Taxes & Processing</span>
                    <span class="val">$0.00</span>
                </div>

                <div class="price-row total">
                    <span>Total Amount Due</span>
                    <b>$120.00</b>
                </div>
            </div>

            <div class="guarantee-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                100% Money Back Guarantee if cancelled 2h prior
            </div>
        </div>
    </div>

    <!-- PAYMENT SUCCESS MODAL -->
    <div v-if="showSuccessModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
            <h3>Payment Successful!</h3>
            <p>We have processed your <strong>$120.00</strong> payment via Stripe. A formal invoice receipt has been sent to your email.</p>

            <Link href="/patient/appointments" class="btn btn-primary">Go to My Appointments</Link>
        </div>
    </div>
</template>

<style scoped>
.checkout-grid { display: grid; grid-template-columns: 1fr 380px; gap: 28px; align-items: start; }
@media (max-width: 1024px) { .checkout-grid { grid-template-columns: 1fr; } }

.checkout-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 32px; box-shadow: var(--shadow-card); }
@media (max-width: 600px) { .checkout-card { padding: 20px; } }

.card-title { font-size: 18px; font-weight: 800; color: var(--forest); margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--line); padding-bottom: 14px; }
.powered-by { font-size: 12px; font-weight: 600; color: var(--ink-muted); }

.express-pay-box { margin-bottom: 24px; }
.express-pay-title { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-muted); margin-bottom: 10px; }
.express-btn { width: 100%; height: 48px; border-radius: var(--radius-md); background: #000; color: #fff; font-size: 15px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 8px; border: none; cursor: pointer; transition: opacity 150ms ease; }
.express-btn:hover { opacity: 0.9; }

.divider-or { display: flex; align-items: center; gap: 12px; margin: 20px 0; color: var(--ink-muted); font-size: 12px; font-weight: 700; text-transform: uppercase; }
.divider-or::before, .divider-or::after { content: ""; flex: 1; height: 1px; background: var(--line); }

.form-group { margin-bottom: 18px; }
.form-group label { font-size: 12.5px; font-weight: 700; color: var(--ink); display: block; margin-bottom: 6px; }

.input-wrap { position: relative; display: flex; align-items: center; }
.input-wrap svg { position: absolute; left: 14px; color: var(--ink-muted); pointer-events: none; }
.form-control { width: 100%; height: 46px; border-radius: var(--radius-md); border: 1px solid var(--line); background: var(--cream); padding: 0 16px; font-size: 14px; color: var(--ink); transition: border-color 150ms ease, background-color 150ms ease; }
.form-control.has-icon { padding-left: 42px; }
.form-control:focus { outline: none; border-color: var(--forest); background: var(--card); }
.card-input { font-family: var(--font-mono); letter-spacing: 0.05em; }
.form-row-dual { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

.submit-wrap { margin-top: 24px; }
.btn-submit-pay { width: 100%; height: 52px; border-radius: 999px; background: var(--forest); color: #fff; font-size: 15px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: var(--shadow-sm); border: none; cursor: pointer; transition: background-color 150ms ease; }
.btn-submit-pay:hover { background: var(--forest-2); }
.btn-submit-pay:disabled { opacity: 0.7; cursor: not-allowed; }

.summary-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 28px; box-shadow: var(--shadow-card); position: sticky; top: 92px; }
.summary-card h3 { font-size: 17px; font-weight: 800; color: var(--forest); margin-bottom: 20px; border-bottom: 1px solid var(--line); padding-bottom: 12px; }

.doctor-item { display: flex; gap: 14px; align-items: center; padding-bottom: 18px; border-bottom: 1px dashed var(--line); margin-bottom: 18px; }
.doctor-item img { width: 56px; height: 56px; border-radius: var(--radius-md); object-fit: cover; background: var(--cream-alt); }
.doctor-item b { font-size: 15px; font-weight: 800; color: var(--forest); display: block; }
.doctor-item span { font-size: 12.5px; color: var(--ink-muted); }

.price-breakdown { display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; }
.price-row { display: flex; justify-content: space-between; font-size: 13.5px; }
.price-row .lbl { color: var(--ink-muted); }
.price-row .val { font-weight: 600; }
.price-row .val-free { color: #15803D; font-weight: 700; }
.price-row.total { border-top: 1px solid var(--line); padding-top: 12px; margin-top: 6px; font-weight: 800; font-size: 16px; }
.price-row.total b { font-family: var(--font-mono); font-size: 20px; color: var(--forest); }

.guarantee-note { display: flex; align-items: center; justify-content: center; gap: 6px; font-size: 12px; color: var(--ink-muted); margin-top: 14px; text-align: center; }

.modal-overlay { position: fixed; inset: 0; background: rgba(22,24,15,0.6); backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center; z-index: 100; padding: 20px; }
.modal-card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius-xl); padding: 36px; max-width: 440px; width: 100%; text-align: center; box-shadow: var(--shadow-lift); }
.modal-icon { width: 60px; height: 60px; border-radius: 50%; background: #DCFCE7; color: #15803D; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; }
.modal-icon svg { width: 30px; height: 30px; }
.modal-card h3 { font-size: 22px; font-weight: 800; color: var(--forest); margin-bottom: 8px; }
.modal-card p { font-size: 14px; color: var(--ink-muted); line-height: 1.5; margin-bottom: 24px; }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 48px; padding: 0 28px; border-radius: 999px; font-size: 14.5px; font-weight: 600; transition: all 150ms ease; width: 100%; text-decoration: none; }
.btn-primary { background: var(--forest); color: #fff; box-shadow: var(--shadow-sm); }
.btn-primary:hover { background: var(--forest-2); }
</style>

# Laundry App Pricing & Calculation Rules

This document outlines the core mathematical and business logic for the transaction system. If calculations appear incorrect or pricing bugs arise, refer to this document to understand how the system is *supposed* to work.

## 1. Corporate (B2B) vs. Normal (B2C) Distinction

The system completely decouples regular users from corporate partner users. This affects pricing, discounts, and available services.

### A. Priority Surcharge (ASAP)
* **Corporate:** 25% surcharge on the subtotal.
* **Normal:** 20% surcharge on the subtotal.

### B. Delivery Fees (Based on Total Weight)
* **Total Weight Calculation:** The system only sums the weight of items where the service `unit_type` is `'kg'`. Piece-based items (`pcs`) do NOT add to the physical delivery weight calculation to prevent wildly inflated fees (e.g., 5 jackets = 5 pcs, NOT 5 kg).
* **Base Condition:** Delivery fee is only applied if the `delivery_method` is NOT `dropoff` AND the `subtotal > 0`.
* **Corporate Brackets:**
  * `0 - 50 kg` = Rp 250,000
  * `51 - 150 kg` = Rp 500,000
  * `> 150 kg` = Rp 1,000,000
* **Normal Brackets:**
  * `0 - 3 kg` = Rp 50,000
  * `4 - 7 kg` = Rp 100,000
  * `> 7 kg` = Rp 150,000
* **Delivery Fee Cap:** The delivery fee can NEVER exceed 50% of the total service subtotal. If it does, it is capped at `subtotal * 0.50`.

### C. Promotional Discounts
* **Corporate:** Automatically receives a flat **15% discount** off the grand total. They do NOT use the promo code system.
* **Normal:** Must enter a valid promo code (e.g., a 10% discount code). 
* **Frontend Display:** In the dashboard and tracking views, the system dynamically checks if the user is corporate (showing "-15% Corporate Discount") or if they applied a custom promo code (showing the specific percentage, e.g., "-10%").

## 2. "Do the best" Auto-Assignment Logic (Bundles)

When users select a bulk weight bundle and allow the system to auto-assign services, the backend handles the distribution.

* **Corporate Bulk (e.g., 50kg, 150kg, 300kg):**
  * The selected bundle weight is mathematically split: **85%** to the primary service, **15%** to the secondary service within the chosen tier.
  * *CRITICAL RULE (Precision):* If the service is weight-based (`kg`), the system allows 1 decimal point (e.g., 42.5 kg). If the service is piece-based (`pcs`), the system forces a round to a whole integer (0 decimal points) to prevent impossible quantities like "42.5 pieces".
* **Normal Tiers:**
  * **Essential (kg):** Hardcoded split of 2.5kg for the first service and 1.0kg for the second.
  * **Signature / Bespoke (pcs):** Takes the estimated quantity and splits it roughly **60% / 40%**, always enforcing whole integers.

## 3. Financial Calculation Flow (`Transaction.php`)

To debug the final price, follow this exact order of operations in `recalculatePricing()`:
1. **Subtotal:** Sum of `(weight or qty) * unit_price` for all items.
2. **ASAP Surcharge:** Calculated from `Subtotal`.
3. **Delivery Fee:** Calculated from `total_weight` (kg only), then capped at 50% of `Subtotal`.
4. **Grand Total (Before Promo):** `Subtotal + ASAP Surcharge + Delivery Fee`.
5. **Promo Cut:** Calculated from the `Grand Total` (either via a Promo Code % or the automatic 15% Corporate cut).
6. **Final Total Price:** `Grand Total - Promo Cut`.

*Note: Any time a service is added, removed, or a transaction flag (like priority or corporate) is toggled, `syncTotal()` must be called on the Transaction model to re-run this exact sequence.*

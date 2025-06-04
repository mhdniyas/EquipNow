# 🧾 Rental Shop App – Workflow Documentation Checklist

### ✅ Project Setup
- [ ] Laravel installed with Breeze (auth system)
- [ ] Tailwind CSS configured
- [ ] Alpine.js set up for interactivity
- [ ] Database seeded with:
  - [ ] Equipment
  - [ ] Categories/Subcategories
  - [ ] Combos (bundled items)
  - [ ] Roles: Admin, Salesman, Staff

---

### 👤 User Roles
- [ ] Admin: Full access, reports, user mgmt
- [ ] Salesman: Bookings, returns, daily report
- [ ] Staff: Maintenance updates only

---

### 📅 Daily Store Workflow

#### 🏁 Opening
- [ ] Staff logs in
- [ ] Reviews today’s bookings, returns, payments
- [ ] Prepares available inventory
- [ ] Flags any damaged/maintenance items

#### 📥 New Booking (Walk-in / Phone)
- [ ] Add customer or pick existing
- [ ] Add equipment or select a combo
- [ ] Add bonus/free accessories (if any)
- [ ] Set rental duration (from/to)
- [ ] Upload KYC (Aadhaar, etc.)
- [ ] Auto-calculate rent + deposit
- [ ] Generate PDF invoice
- [ ] Mark payment as Paid / Partial / Due
- [ ] Confirm order → equipment marked reserved

#### 📦 Combo/Bundle Booking
- [ ] Pre-defined combo selected OR manually added
- [ ] Bonus items marked as free
- [ ] One bill generated for all
- [ ] Inventory deducted per item, not just combo

#### 📤 Return Handling
- [ ] Select ongoing rental
- [ ] Inspect condition: Good / Damaged / Needs repair
- [ ] Update return in system
- [ ] Process refund (full/partial/none)
- [ ] Mark equipment as Available / Repair

#### 🔧 Maintenance Workflow
- [ ] Staff flags item as “Needs Repair”
- [ ] Task logged in Maintenance section
- [ ] Technician updates when done
- [ ] Admin reviews and re-enables item

#### 📈 Daily Closing
- [ ] Review daily bookings/returns
- [ ] Cash/UPI report generated
- [ ] WhatsApp/email summary sent to owner
- [ ] Inventory reset for next day
- [ ] Logout

---

### 🧰 Equipment Management
- [ ] Create/Edit/Delete Equipment
- [ ] Assign to category & subcategory
- [ ] Track quantity in/out
- [ ] Tag as Available / In Use / Maintenance

---

### 📦 Combo Management
- [ ] Admin defines bundles (e.g., “Painting Kit”)
- [ ] Set pricing and components
- [ ] Mark some items as free/bonus
- [ ] Used in multi-item bookings

---

### 📜 Invoicing & Reports
- [ ] Invoice includes all items, with free items at ₹0
- [ ] GST/tax lines (if applicable)
- [ ] Deposit breakdown
- [ ] Daily report includes:
  - [ ] Bookings count
  - [ ] Returns summary
  - [ ] Damages
  - [ ] Total revenue + refunds
- [ ] Option to export as PDF/CSV

---

### 🛠 Admin Panel Checklist
- [ ] User/Staff management
- [ ] Role assignment
- [ ] Equipment list and stock view
- [ ] Combo creator (add/edit combos)
- [ ] Maintenance tasks tracking
- [ ] Booking history search
- [ ] Reports view/export

---

### 📋 Documentation Tasks
- [ ] Add screenshot per screen
- [ ] SOP for new staff onboarding
- [ ] How-to guide for common flows (booking, return, refund)
- [ ] Troubleshooting section (double booking, stock errors, etc.)
- [ ] Upload this doc in `/docs/WORKFLOW_CHECKLIST.md`

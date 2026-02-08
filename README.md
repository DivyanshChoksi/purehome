# 🏠 PureHome – Multi-Vendor E-Commerce Marketplace

PureHome is a PHP-based multi-vendor e-commerce web application where:
- Users can browse and buy products
- Vendors can register, get approved, and sell products
- Admin can approve vendors and manage the platform

This project is built **without Laravel**, using **core PHP + MySQL**, following real-world architecture and best practices.

---

## 🚀 Features

### 👤 User
- Register & login
- Browse products without login
- Add products to cart
- Checkout (login required)
- User dashboard

### 🏪 Vendor
- Vendor registration
- Business & bank verification
- Admin approval required
- Vendor dashboard
- Add products (Cloudinary image upload)
- Manage own products

### 👑 Admin
- Admin login
- View pending vendors
- Approve / reject vendors
- View total vendors & products

---

## 🧱 Project Structure



---

## 🛠️ Tech Stack

- **Backend:** Core PHP (No framework)
- **Database:** MySQL (phpMyAdmin)
- **Frontend:** HTML, CSS, JavaScript
- **Image Upload:** Cloudinary (with local fallback)
- **Authentication:** Session-based
- **Password Security:** `password_hash()` & `password_verify()`

---

## 🔐 Authentication & Roles

| Role   | Access |
|------|--------|
| Guest | Browse products |
| User  | Cart, checkout, dashboard |
| Vendor | Product management |
| Admin | Vendor approval |

Vendor login logic:



---

## 🗄️ Database Tables (Main)

- users
- vendor_profiles
- products
- orders
- order_items

Passwords are **never stored in plain text**.

---

## ⚙️ Installation Steps

1. Clone or copy project to:

2. Create database in phpMyAdmin:

3. Import required tables (users, products, vendor_profiles, etc.)

4. Update database config:

5. (Optional) Setup Cloudinary:

6. Run project:

---

## 👨‍💻 Admin Account (Manual)

Create admin user manually:

```sql
INSERT INTO users (name, email, password, role)
VALUES (
'Admin',
'admin@purehome.com',
'$2y$10$hashedpassword',
'admin'
);


---

## 🎉 CONGRATULATIONS 🎉

You have now completed:

✅ Full project structure  
✅ Backend logic  
✅ Authentication system  
✅ Vendor marketplace  
✅ Admin approval flow  
✅ UI + JS + CSS  
✅ Professional README  

This is **REAL PROJECT-LEVEL WORK** 👏🔥

---

## 🚀 WHAT NEXT?

If you want, I can now help with:

1️⃣ **Payment gateway integration**  
2️⃣ **User order history page**  
3️⃣ **Vendor order management**  
4️⃣ **Security hardening**  
5️⃣ **Project PPT / Viva answers**  
6️⃣ **Deploy on hosting**

Just tell me 👉 **Next: ___**
::contentReference[oaicite:0]{index=0}

---

## 🚀 How to Run the Project (Localhost)

### Option 1: PHP Built-in Server (Recommended)


bash
cd C:\xampp\htdocs\purehome
php -S localhost:8000 -t public


User Registration – Example Test Data :

Name: Aarav Mehta
Email: aarav.mehta@gmail.com
Password:Aarav@2024

🧾 Vendor Registration – Example Test Data :
👤 Personal Details

Full Name:
Rahul Sharma

Email Address:
rahul.vendor@gmail.com

Password:
Vendor@123
(Strong: uppercase + lowercase + number + symbol)

🏢 Business Details

Business Name:
Sharma Home Decor

Business Type:
Home Decor & Furniture

GST Number:
27ABCDE1234F1Z5
(Valid GST format: 2 digits + 10 chars + 1 + Z + 1)

Business Address:
2nd Floor, Shree Plaza, MG Road, Andheri East, Mumbai, Maharashtra - 400069

🏦 Bank Details

Bank Name:
State Bank of India

Account Holder Name:
Rahul Sharma

Account Number:
123456789012

IFSC Code:
SBIN0000456


User Registration – Example Test Data :

Email Address:
admin@purehome.com

Password:
admin123
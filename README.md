# 🌸 Pavitra – Premium Handcrafted Saree E-commerce Store

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%207.3-777BB4?style=flat-square&logo=php)](https://www.php.net/)
[![MySQL Database](https://img.shields.io/badge/Database-MySQL-4479A1?style=flat-square&logo=mysql)](https://www.mysql.com/)
[![Bootstrap Framework](https://img.shields.io/badge/Frontend-Bootstrap%205-7952B3?style=flat-square&logo=bootstrap)](https://getbootstrap.com/)
[![Payment Gateway](https://img.shields.io/badge/Payment-Razorpay-02042B?style=flat-square&logo=razorpay)](https://razorpay.com/)

**Pavitra** is an elegant, fully-featured e-commerce platform specifically designed for presenting and purchasing handcrafted Indian heritage sarees—specializing in **Gota Patti, Bandhej, Banarasi, Pittan Work, and Pure Silk Sarees**. Built with native PHP and PDO database wrappers, it incorporates standard e-commerce workflows like custom shopping cart management, product catalogs, transactional emails, and Razorpay payment integration.

---

## ✨ Features

- **🛍️ Premium Product Showcase:** Dynamic catalogs with custom categories, pagination, and multi-image galleries.
- **🛒 Shopping Cart & Checkout System:** Intuitive, AJAX-driven shopping cart updates with seamless session control.
- **💳 Razorpay Payment Gateway:** Safe and smooth local currency processing for card, netbanking, and UPI payments.
- **📧 Transactional Emails:** Immediate booking confirmations and payment notifications powered by **PHPMailer**.
- **📊 Admin Control Panel:** Robust dashboard (built using AdminLTE templates) to manage orders, banners, products, categories, and site settings.
- **🛡️ Secure Connection API:** Fully environment-driven setup using a `.env` configuration file to safeguard sensitive credentials.

---

## 🛠️ Tech Stack & Dependencies

- **Backend:** PHP (>= 7.3) with PDO for secure, prepared SQL queries.
- **Database:** MySQL / MariaDB (MariaDB 10.6+).
- **Frontend Styling:** HTML5, CSS3, Bootstrap 5, and AdminLTE (for the dashboard).
- **PHP Libraries (managed via Composer):**
  - `razorpay/razorpay` – Payment API Client.
  - `phpmailer/phpmailer` – SMTP Email Transfer Agent.
- **JavaScript Libraries:** jQuery, Magnific Popup, DataTables, and other AdminLTE plugins.

---

## 🚀 Local Installation & Setup Guide

Ensure you have a local web server environment installed (like **XAMPP**, **WAMP**, or **Laragon**) along with **Composer** and **Git**.

### 1. Clone the Repository
Clone the project into your local server root folder (e.g., `C:/xampp/htdocs/` for XAMPP):
```bash
cd C:/xampp/htdocs/
git clone https://github.com/your-username/Pavitra.git
```

### 2. Configure Environment Variables
- Copy the `.env.example` file in the root folder and rename the copy to `.env`:
```bash
cp .env.example .env
```
- Open `.env` and fill in your database details, SMTP parameters, and Razorpay API credentials:
```env
DB_HOST=localhost
DB_NAME=if0_39971069_ecomm
DB_USER=root
DB_PASS=""

# Add your credentials for full payment & email functionality:
RAZORPAY_KEY_ID=your_razorpay_key_id
RAZORPAY_KEY_SECRET=your_razorpay_key_secret
```

### 3. Install Dependencies
Download and restore the necessary PHP vendor libraries using Composer:
```bash
composer install
```

### 4. Import the Database Schema
1. Open your browser and navigate to your database manager (typically `http://localhost/phpmyadmin`).
2. Create a new database named `if0_39971069_ecomm` (or the name you specified in `.env`).
3. Select the database, navigate to the **Import** tab, choose the database dump file located at:
   `[Project Root]/database/if0_39971069_ecomm.sql`
4. Click **Go** / **Import** to seed tables and initial product catalogs.

### 5. Run the Application
Open your browser and navigate to:
```url
http://localhost/Pavitra/
```

---

## 📁 Key Project Directory Structure

```
Pavitra/
├── admin/               # Administrative Dashboard (AdminLTE pages)
├── bower_components/    # Dashboard frontend dependencies (jQuery, DataTables, etc.)
├── config/              # Payment configuration files (e.g., razorpay.php)
├── database/            # Database scripts & initial SQL dumps (.sql files)
├── dist/                # Main site compiled assets (CSS, JS, images)
├── images/              # Dynamic product catalog images & brand assets
├── includes/            # Reusable header, footer, navbar, and database connection files
├── vendor/              # Composer generated dependency folder (gitignored)
├── .env                 # Environment configuration (gitignored, private)
├── .env.example         # Reference environment template file
├── .gitignore           # Git ignore list configuration
├── composer.json        # Declared project dependencies list
├── index.php            # Homepage / Main Catalog View
└── README.md            # Project overview & documentation (this file)
```

---

## 🔒 Security Best Practices

- **Never commit `.env` to Git:** The `.env` file contains production secrets and passwords. Make sure `.gitignore` remains configured to ignore `.env`.
- **Database Sanitization:** All database interactions are written using PHP Data Objects (PDO) with prepared statements to secure inputs against SQL injection.
- **Admin Security:** Ensure the admin directory access has strong authentication checks enabled in production environments.

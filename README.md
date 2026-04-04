# Nexus Market Platform | Online Second-Hand Marketplace

Nexus Market is a premium, full-stack web application developed for an online second-hand marketplace platform. This platform provides a secure and modern environment for users to list pre-loved treasures, browse diverse categories, and manage transactions with a seamless interface.

## Key Features

### User Platform

- Modern Authentication: Secure login and registration with password hashing.
- Dynamic Marketplace: Browse listings by category (Electronics, Clothing, Books, Collectibles).
- Interactive Details: Detailed product views with condition and availability status.
- Glassmorphism Design: High-end aesthetic with vibrant gradients, dark mode, and smooth animations.
- Secure Shopping Cart: Manage items, calculate subtotals, and update quantities.
- Streamlined Checkout: Transaction processing with stock auto-deduction.
- **Order History**: Track past marketplace discoveries with status tracking.

### Administrative Console

- Dashboard Stats: Real-time monitoring of community growth, inventory volume, and platform revenue.
- Live Inventory Management: Full control over product listings (Add/Remove listings).
- Transaction Logs: Overview of the latest platform activities.

## Setup Instructions

1. Prerequisites: Ensure XAMPP or a similar local server environment is installed.
2. **Directory Placement**: Clone/Copy the project folder into `c:/xampp/htdocs/marketplace`.
3. Database Configuration:
   - Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
   - Create a new database named `marketplace`.
   - Import the `marketplace.sql' provided
4. Environment Check:
   - Verify that the `includes/db.php` credentials match your local MySQL settings (defaults: `host=localhost`, `user=root`, `pass=""`).
5. Launch: Access the platform via `http://localhost/marketplace` in your browser.

## Developer Information

Name - Aman Singh 
Student ID - 5144770
Course: Internet Tools - Term Project

## Security Implementation

- SQL Injection Protection**: Implemented using PHP Data Objects (PDO) with prepared statements.
- Password Security: Passwords are encrypted using the `password_hash` algorithm.
- Session Management: Secure sessions used for authentication across all relevant pages.

---

© 2026 Nexus Market Platform Project. Built with excellence for the second-hand economy.
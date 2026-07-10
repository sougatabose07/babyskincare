# BabySkinCare - E-Commerce Platform

A Laravel 12 e-commerce platform with product management, shopping cart, guest checkout, and Razorpay payment integration.

## Features

- **Product Management**: Browse products by categories
- **Shopping Cart**: Session-based cart with quantity control
- **Guest Checkout**: No login required for purchasing
- **Payment Gateway**: Razorpay integration (test mode)
- **Order Management**: Admin dashboard for order tracking and status updates
- **Stock Management**: Product inventory tracking with automatic decrement on order
- **Admin Panel**: Complete admin interface for managing products, categories, orders, and banners

## Prerequisites

- PHP 8.1+
- Composer
- MySQL/MariaDB
- Node.js & npm (for Vite assets)

## Setup Instructions

### 1. Install Dependencies

```bash
cd c:\xampp\htdocs\localfolder
composer install
npm install
```

### 2. Environment Configuration

Copy the `.env.example` template if needed:

```bash
cp .env.example .env
```

Configure these variables in `.env`:

```env

RAZORPAY_KEY=rzp_test_xxxxxxxxxxxxx
RAZORPAY_SECRET=xxxxxxxxxxxxxxxx
```

Generate the app key:

```bash
php artisan key:generate
```

### 3. Database Setup


Run migrations:

```bash
php artisan migrate
```

Run seeders to create admin user and sample data:

```bash
php artisan db:seed
```

## Admin Access

### Default Admin Credentials

After running migrations and seeders:

- **Email**: `admin@babyskincare.sb`
- **Password**: `BabySkinCare@2026`

---

## Testing the Payment Flow

### Test Guest Checkout with Razorpay

1. Navigate to `http://localhost:8000/products`
2. Add products to cart
3. Go to cart and click "Checkout"
4. Fill guest information (name, email, address, etc.)
5. Click "Pay with Razorpay (test)"
6. Use these test card details:
   - **Card Number**: `4111 1111 1111 1111`
   - **Expiry**: `12/25`
   - **CVV**: `123`

### Admin Order Management

1. Login at `http://localhost:8000/admin/dashboard`
2. Navigate to "Orders"
3. View customer details and order items
4. Update order status (pending → paid, shipped, delivered, cancelled)

### Stock Management

1. Go to Admin → Products
2. Set stock quantity for a product
3. When orders are placed, stock decrements automatically
4. Cart validation prevents overselling

---

## Database Schema

### Core Tables

- **users**: Admin users with `is_admin` flag
- **categories**: Product categories with URL slugs
- **products**: Products with stock, pricing, category links
- **orders**: Guest orders storing customer name, email, address
- **order_items**: Order line items with quantity and pricing
- **banners**: Promotional banners with sort order

---

## Troubleshooting

### Database Connection Error
```bash
# Verify credentials in .env
php artisan migrate:fresh
php artisan migrate
```

### Razorpay Payment Issues
- Confirm `RAZORPAY_KEY` and `RAZORPAY_SECRET` in `.env`
- Use only test mode credentials
- Verify test card details

### Admin Login Fails
```bash
php artisan db:seed
```

---

## License

MIT License

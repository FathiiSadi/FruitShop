# 🍎 FruitShop - Demo E-commerce Application

A beautiful, fully-functional fruit shop e-commerce application built with Yii2 framework. **No database required!** All data is stored in JSON files for easy testing and demonstration.

![Yii2](https://img.shields.io/badge/Yii2-2.0.52-blue)
![PHP](https://img.shields.io/badge/PHP-8.2+-purple)
![License](https://img.shields.io/badge/license-MIT-green)

## ✨ Features

- 🛒 **Shopping Cart** - Add products, update quantities, remove items
- 📦 **Order Management** - Place orders, track status, view history
- 👤 **User Accounts** - Registration, login, profile management
- 🏠 **Address Book** - Save multiple delivery addresses
- 💳 **Payment Processing** - Support for Visa and Cash on Delivery
- 📊 **Admin Dashboard** - Manage products, orders, and users
- 🎨 **Beautiful UI** - Modern, responsive design
- 📱 **Mobile Friendly** - Works perfectly on all devices

## 🚀 Quick Start (No Database Setup Required!)

### Prerequisites

- PHP 8.2 or higher
- Composer

### Option 1: Quick Start Script (Recommended)

**For Mac/Linux:**
```bash
./start.sh
```

**For Windows:**
```bash
start.bat
```

The script will automatically:
- Check PHP and Composer installation
- Install dependencies
- Configure directories
- Start the development server

### Option 2: Manual Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/FathiiSadi/FruitShop.git
   cd FruitShop
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Start the development server**
   ```bash
   php yii serve
   ```

4. **Open your browser**
   ```
   http://localhost:8080
   ```

That's it! No database configuration needed. The application uses JSON files in the `data/` directory.

## 👥 Demo Accounts

### Admin Account
- **Email:** admin@fruitshop.com
- **Password:** admin123
- **Access:** Full admin panel access

### User Accounts
- **Email:** john@example.com / **Password:** password123
- **Email:** jane@example.com / **Password:** password123
- **Email:** demo@fruitshop.com / **Password:** demo123

## 📁 Project Structure

```
FruitShop/
├── data/                    # JSON data files (replaces database)
│   ├── products.json       # Product catalog (12 fruits)
│   ├── users.json          # User accounts
│   ├── orders.json         # Customer orders
│   ├── addresses.json      # Delivery addresses
│   ├── cart.json           # Shopping carts
│   ├── cart_item.json      # Cart items
│   ├── order_items.json    # Order line items
│   └── payments.json       # Payment records
├── helpers/
│   └── DataLoader.php      # JSON data management helper
├── models/                  # Application models
├── controllers/             # Application controllers
├── views/                   # Application views
├── web/                     # Web accessible files
└── assets_static/           # Images, CSS, JS
```

## 🛍️ Sample Products

The application comes pre-loaded with 12 delicious fruits:

1. **Fresh Red Apple** - $2.50
2. **Organic Bananas** - $1.20
3. **Juicy Oranges** - $3.00
4. **Fresh Strawberries** - $5.50
5. **Organic Blueberries** - $6.00
6. **Sweet Watermelon** - $10.00
7. **Green Grapes** - $4.50
8. **Ripe Mangoes** - $3.75
9. **Fresh Pineapple** - $4.00
10. **Organic Raspberries** - $6.50
11. **Fresh Kiwi** - $2.80
12. **Sweet Peaches** - $3.50

## 🎯 How It Works

Instead of using a traditional database, this application stores all data in JSON files located in the `data/` directory. The `DataLoader` helper class provides a simple API for reading and writing data:

```php
use app\helpers\DataLoader;

// Load all products
$products = DataLoader::findAll('products');

// Find a product by ID
$product = DataLoader::findById('products', 1);

// Find products by category
$fruits = DataLoader::findBy('products', 'category', 'Fruits');

// Add a new product
DataLoader::insert('products', [
    'name' => 'Fresh Apples',
    'price' => 2.99,
    'category' => 'Fruits',
    'stock' => 100
]);

// Update a product
DataLoader::update('products', 1, ['price' => 2.75]);

// Delete a product
DataLoader::delete('products', 1);
```

## 🔧 Customization

### Adding New Products

Edit `data/products.json` and add your products:

```json
{
    "id": 13,
    "name": "Your Fruit Name",
    "price": 4.99,
    "description": "Description here",
    "category": "Fruits",
    "stock": 100,
    "image_url": "assets_static/img/products/your-image.jpg",
    "created_at": "2024-01-26 10:00:00",
    "updated_at": "2024-01-26 10:00:00"
}
```

### Modifying User Accounts

Edit `data/users.json` to add or modify user accounts.

### Changing Order Status

Edit `data/orders.json` to update order statuses:
- `pending` - Order placed, awaiting processing
- `processing` - Order is being prepared
- `shipped` - Order has been shipped
- `delivered` - Order delivered successfully
- `cancelled` - Order cancelled

## 🎨 Screenshots

(Add your screenshots here)

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📝 License

This project is open-source and available under the MIT License.

## 🙏 Acknowledgments

- Built with [Yii2 Framework](https://www.yiiframework.com/)
- Icons and images from various free sources
- Inspired by modern e-commerce platforms

## 📧 Contact

For questions or support, please open an issue on GitHub.

---

**Note:** This is a demonstration application. For production use, consider implementing a proper database system and additional security measures.

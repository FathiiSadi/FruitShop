# 🚀 FruitShop Setup Guide

Welcome to FruitShop! This guide will help you get the application running in minutes.

## 📋 Prerequisites

Before you begin, ensure you have:

- **PHP 8.2 or higher** installed
- **Composer** (PHP dependency manager)
- A web browser

### Check Your PHP Version

```bash
php -v
```

You should see something like: `PHP 8.2.x` or higher

### Install Composer (if not installed)

Visit: https://getcomposer.org/download/

## 🎯 Installation Steps

### Step 1: Download the Project

```bash
# Clone from GitHub
git clone https://github.com/FathiiSadi/FruitShop.git

# Navigate to the project directory
cd FruitShop
```

### Step 2: Install Dependencies

```bash
composer install
```

This will download all required PHP packages. It may take a few minutes.

### Step 3: Start the Application

```bash
php yii serve
```

You should see:
```
Server started on http://localhost:8080
Document root is "/path/to/FruitShop/web"
Quit the server with CTRL-C.
```

### Step 4: Open in Browser

Open your web browser and go to:
```
http://localhost:8080
```

🎉 **That's it!** The application is now running.

## 👤 Login Credentials

### Admin Access
- **URL:** http://localhost:8080/admin
- **Email:** admin@fruitshop.com
- **Password:** admin123

### Customer Accounts
- **Email:** john@example.com | **Password:** password123
- **Email:** jane@example.com | **Password:** password123
- **Email:** demo@fruitshop.com | **Password:** demo123

## 📂 Understanding the Data Files

All application data is stored in JSON files in the `data/` directory:

```
data/
├── products.json      # 12 fruit products
├── users.json         # User accounts
├── orders.json        # Customer orders
├── addresses.json     # Delivery addresses
├── cart.json          # Shopping carts
├── cart_item.json     # Items in carts
├── order_items.json   # Items in orders
└── payments.json      # Payment records
```

You can edit these files directly to:
- Add new products
- Create user accounts
- Modify orders
- Update prices

**Important:** Always maintain valid JSON format!

## 🛠️ Customization

### Adding New Products

1. Open `data/products.json`
2. Add a new product entry:

```json
{
    "id": 13,
    "name": "Fresh Grapes",
    "price": 4.99,
    "description": "Sweet seedless grapes",
    "category": "Fruits",
    "stock": 150,
    "image_url": "assets_static/img/products/grapes.jpg",
    "created_at": "2024-01-26 10:00:00",
    "updated_at": "2024-01-26 10:00:00"
}
```

3. Save the file
4. Refresh your browser

### Adding Product Images

1. Place your image in: `web/assets_static/img/products/`
2. Update the `image_url` in `products.json`
3. Recommended image size: 400x400 pixels

### Creating New Users

1. Open `data/users.json`
2. Add a new user:

```json
{
    "id": 5,
    "username": "newuser",
    "email": "newuser@example.com",
    "password_hash": "$2y$13$...",
    "auth_key": "random_key_here",
    "access_token": "random_token_here",
    "role": "user"
}
```

**Note:** For password hashing, use PHP's `password_hash()` function.

## 🎨 Customizing the Look

### Change Colors

Edit: `web/assets_static/css/style.css`

### Modify Layout

Edit: `views/layouts/main.php`

### Update Logo

Replace: `web/assets_static/img/logo.png`

## 🐛 Troubleshooting

### Port 8080 Already in Use

Use a different port:
```bash
php yii serve --port=8081
```

Then visit: http://localhost:8081

### Composer Install Fails

Try:
```bash
composer install --ignore-platform-reqs
```

### Permission Errors

Make sure the `data/` directory is writable:
```bash
chmod -R 755 data/
```

### Page Not Found (404)

Make sure you're accessing:
```
http://localhost:8080
```

NOT:
```
http://localhost:8080/web
```

## 📱 Testing Features

### 1. Browse Products
- Visit the home page
- Click on products to view details
- Use the category filter

### 2. Shopping Cart
- Add products to cart
- Update quantities
- Remove items
- Proceed to checkout

### 3. Place an Order
- Add items to cart
- Click "Checkout"
- Fill in delivery address
- Select payment method
- Confirm order

### 4. Admin Panel
- Login as admin
- Manage products
- View orders
- Manage users

## 🚀 Deployment

For production deployment, consider:

1. **Use a proper web server** (Apache/Nginx)
2. **Enable caching** for better performance
3. **Implement a real database** for scalability
4. **Add SSL certificate** for security
5. **Set up backups** for data files

## 📚 Additional Resources

- [Yii2 Documentation](https://www.yiiframework.com/doc/guide/2.0/en)
- [PHP Manual](https://www.php.net/manual/en/)
- [Composer Documentation](https://getcomposer.org/doc/)

## 💡 Tips

- **Backup data files** before making changes
- **Use JSON validators** when editing data files
- **Check browser console** for JavaScript errors
- **Review PHP error logs** if something breaks

## 🆘 Need Help?

- Check the [README.md](README.md) file
- Open an issue on GitHub
- Review the code comments

---

**Happy Shopping! 🛒🍎🍊🍓**

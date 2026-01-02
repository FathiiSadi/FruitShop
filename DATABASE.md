# Database Setup Guide

This guide explains how to set up and configure the PostgreSQL database for the FruitShop application.

## Prerequisites

- PostgreSQL database (local or hosted on Render.com)
- PHP 7.4 or higher with PDO PostgreSQL extension
- Composer

## Quick Setup (Using Render.com Database)

The application is pre-configured to work with the Render.com PostgreSQL database. Follow these steps:

### 1. Copy Environment File

```bash
cp .env.example .env
```

The `.env.example` file already contains the Render.com database credentials:

```
DB_DSN="pgsql:host=dpg-d5begcjuibrs73ce8lag-a;port=5432;dbname=fruitshop_db;sslmode=require"
DB_USERNAME="fruitadmin"
DB_PASSWORD="6otaPMavrBeSxd7NayEGdP47NEEeuCaJ"
```

### 2. Run Database Setup Script

```bash
./setup-db.sh
```

This script will:
- Create the `.env` file if it doesn't exist
- Test the database connection
- Run all migrations to create tables
- Seed the database with initial data from JSON files

### 3. Verify Setup

After running the setup script, you should see:
```
✓ Database setup completed successfully!
```

## Manual Setup

If you prefer to set up the database manually:

### 1. Create .env File

```bash
cp .env.example .env
```

Edit `.env` and update the database credentials if needed.

### 2. Run Migrations

```bash
php yii migrate
```

This will:
- Create all necessary tables (users, products, cart, orders, etc.)
- Set up foreign key relationships
- Create indexes for better performance
- Populate the database with initial data

## Database Schema

The application uses the following tables:

### Core Tables

- **users** - User accounts and authentication
- **products** - Product catalog
- **cart** - Shopping carts
- **cart_item** - Items in shopping carts
- **orders** - Customer orders
- **order_items** - Items in orders
- **addresses** - Shipping addresses
- **payments** - Payment transactions

### Relationships

```
users
  ├── cart (one-to-many)
  ├── orders (one-to-many)
  └── addresses (one-to-many)

products
  ├── cart_item (one-to-many)
  └── order_items (one-to-many)

orders
  ├── order_items (one-to-many)
  ├── payments (one-to-many)
  └── addresses (belongs-to)
```

## Initial Data

The database is seeded with:
- 4 demo users (including admin)
- 12 products across different categories
- Sample cart data
- Sample orders and payments

### Default Users

| Username | Email | Password | Role |
|----------|-------|----------|------|
| admin | admin@fruitshop.com | (hashed) | admin |
| john_doe | john@example.com | (hashed) | user |
| jane_smith | jane@example.com | (hashed) | user |
| demo_user | demo@fruitshop.com | (hashed) | user |

## Docker Setup

If you're using Docker, the database connection is configured automatically through environment variables in `docker-compose.yml`.

### Build and Run with Docker

```bash
docker-compose up -d
```

The application will connect to the Render.com PostgreSQL database automatically.

### Run Migrations in Docker

```bash
docker-compose exec app php yii migrate
```

## Troubleshooting

### Connection Failed

If you see a database connection error:

1. **Check credentials** - Verify the database credentials in `.env` match your database
2. **Check SSL** - Render.com requires SSL, ensure `sslmode=require` is in the DSN
3. **Check network** - Ensure your server can reach the database host
4. **Check firewall** - Render.com databases may have IP restrictions

### Migration Errors

If migrations fail:

1. **Check database exists** - Ensure the database `fruitshop_db` exists
2. **Check permissions** - Ensure the user has CREATE TABLE permissions
3. **Check existing tables** - If tables already exist, you may need to drop them first

### Reset Database

To completely reset the database:

```bash
# Rollback all migrations
php yii migrate/down all

# Re-run migrations
php yii migrate
```

## Environment Variables

The application uses these database-related environment variables:

| Variable | Description | Example |
|----------|-------------|---------|
| `DB_DSN` | Database connection string | `pgsql:host=localhost;port=5432;dbname=fruitshop_db` |
| `DB_USERNAME` | Database username | `fruitadmin` |
| `DB_PASSWORD` | Database password | `your_password` |

## Production Deployment

For production deployment:

1. **Update .env** - Set `YII_ENV=prod` and `YII_DEBUG=false`
2. **Secure credentials** - Never commit `.env` to version control
3. **Use environment variables** - Set database credentials via server environment
4. **Enable caching** - The schema cache is enabled by default in `config/db.php`

## Database Maintenance

### Backup

To backup the database:

```bash
PGPASSWORD=6otaPMavrBeSxd7NayEGdP47NEEeuCaJ pg_dump -h dpg-d5begcjuibrs73ce8lag-a -U fruitadmin fruitshop_db > backup.sql
```

### Restore

To restore from backup:

```bash
PGPASSWORD=6otaPMavrBeSxd7NayEGdP47NEEeuCaJ psql -h dpg-d5begcjuibrs73ce8lag-a -U fruitadmin fruitshop_db < backup.sql
```

## Additional Resources

- [Yii2 Database Guide](https://www.yiiframework.com/doc/guide/2.0/en/db-dao)
- [Yii2 Migrations](https://www.yiiframework.com/doc/guide/2.0/en/db-migrations)
- [PostgreSQL Documentation](https://www.postgresql.org/docs/)
- [Render.com PostgreSQL](https://render.com/docs/databases)

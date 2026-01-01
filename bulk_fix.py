import os
import re

replacements = {
    r"ProductID": "id",
    r"CartID": "id",
    r"CartItemID": "id",
    r"UserID": "user_id",
    r"Description": "description",
    r"ImageURL": "image_url",
    r"CreatedAt": "created_at",
    r"UpdatedAt": "updated_at",
    r"AddedAt": "added_at",
    r"authKey": "auth_key",
    r"address_id": "id", # For Addresses table PK
    r"return 'user';": "return 'users';",
    r"return 'Cart';": "return 'cart';",
    r"return 'CartItem';": "return 'cart_item';",
}

# Special mapping for order_id in Orders table
order_id_replacement = r"order_id"

root_dir = "/Users/fathi.alsadi/Desktop/fruitShop"
folders = ["models", "controllers", "modules", "config"]

for folder in folders:
    path = os.path.join(root_dir, folder)
    if not os.path.exists(path):
        continue
    for root, dirs, files in os.walk(path):
        for file in files:
            if file.endswith(".php"):
                file_path = os.path.join(root, file)
                with open(file_path, "r") as f:
                    content = f.read()
                
                new_content = content
                for old, new in replacements.items():
                    new_content = re.sub(old, new, new_content)
                
                # Fix order_id specifically in Orders.php and related
                if "order" in file.lower():
                     new_content = re.sub(r"order_id", "id", new_content)

                if new_content != content:
                    with open(file_path, "w") as f:
                        f.write(new_content)
                    print(f"Updated {file_path}")

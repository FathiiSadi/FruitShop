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
    r"address_id": "id",
}

root_dir = "/Users/fathi.alsadi/Desktop/fruitShop"

for root, dirs, files in os.walk(root_dir):
    if "vendor" in root or ".git" in root:
        continue
    for file in files:
        if file.endswith(".php"):
            file_path = os.path.join(root, file)
            with open(file_path, "r") as f:
                content = f.read()
            
            new_content = content
            for old, new in replacements.items():
                new_content = re.sub(old, new, new_content)
            
            # Special case for order_id -> id
            new_content = re.sub(r"order_id", "id", new_content)

            if new_content != content:
                with open(file_path, "w") as f:
                    f.write(new_content)
                print(f"Updated {file_path}")

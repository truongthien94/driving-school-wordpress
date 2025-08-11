# Hero Items Management Guide

## Overview
Hero Items là custom post type được tạo để quản lý động tất cả các items trong Hero Section của trang chủ SBS Portal. Hệ thống hỗ trợ **7 Hero Items** với layout linh hoạt theo thiết kế.

## Features
- **7 Hero Items** có thể cấu hình hoàn toàn
- **Layout động** tự động điều chỉnh theo số lượng items
- **Sắp xếp thứ tự** hiển thị bằng trường "Display Order"
- **Icons đa dạng** với 15+ options có sẵn
- **Links tùy chỉnh** cho mỗi item
- **Fallback content** khi chưa có items

## Layout Structure

### Section 1: Main Hero Items (Items 1-3)
- **Item 1**: Hiển thị chính, to nhất (order = 1)
- **Items 2-3**: Hiển thị theo cặp ở hàng 2 (order = 2, 3)

### Section 2: Bottom Row Items (Items 4-7)
- **Items 4-7**: Hiển thị 4 cột ngang (order = 4, 5, 6, 7)
- Layout: `col-xl-3` cho mỗi item

## Admin Panel Access

### 1. Truy cập Hero Items
- Vào **WordPress Admin → Hero Items**
- Hoặc **Hero Items → All Hero Items**

### 2. Tạo Hero Item mới
- Click **"Add New Hero Item"**
- Điền các trường:
  - **Title**: Tiêu đề hiển thị
  - **Description**: Mô tả chi tiết
  - **Link URL**: Link khi click (để trống nếu không cần)
  - **Icon**: Chọn icon từ dropdown
  - **Display Order**: Thứ tự hiển thị (1-7)

### 3. Chỉnh sửa Hero Item
- Click vào **Title** của item cần sửa
- Thay đổi các trường cần thiết
- Click **"Update"**

### 4. Quick Edit
- Hover vào item → Click **"Quick Edit"**
- Chỉnh sửa nhanh **Display Order**
- Click **"Update"**

## Available Icons
- `car` - Ô tô
- `bus` - Xe buýt
- `building` - Tòa nhà
- `calendar` - Lịch
- `briefcase` - Cặp tài liệu
- `star` - Ngôi sao
- `heart` - Trái tim
- `shield` - Khiên bảo vệ
- `users` - Người dùng
- `home` - Nhà
- `cog` - Bánh răng
- `plus` - Dấu cộng

## Display Order Logic
- **Order 1**: Hiển thị ở phần chính (main section) - to nhất
- **Order 2-3**: Hiển thị ở hàng 2 (2 cột)
- **Order 4-7**: Hiển thị ở hàng 3 (4 cột ngang)
- Items được sắp xếp theo số thứ tự tăng dần
- Nếu không có order, sử dụng thứ tự tạo

## Sample Data Structure

### Items 1-3 (Main Section)
1. **SBS自動車** - order: 1, icon: car
2. **SBSドライビングスクール姉崎** - order: 2, icon: bus
3. **SBSドライビングスクール稲毛** - order: 3, icon: bus

### Items 4-7 (Bottom Row)
4. **姉崎詳細** - order: 4, icon: building
5. **稲毛詳細** - order: 5, icon: building
6. **予約システム** - order: 6, icon: calendar
7. **マッチングシステム** - order: 7, icon: briefcase

## Database Schema
```sql
wp_posts (post_type = 'hero_item')
├── ID
├── post_title
├── post_content
└── post_status

wp_postmeta
├── _hero_item_description
├── _hero_item_link
├── _hero_item_icon
└── _hero_item_order
```

## Customization

### Thêm Icon mới
1. Mở file `functions.php`
2. Tìm function `sbs_get_icon()`
3. Thêm case mới cho icon

### Thay đổi Layout
1. Mở file `portal.php`
2. Chỉnh sửa HTML structure
3. Điều chỉnh CSS classes

### Thay đổi số lượng items
1. Sửa parameter trong `sbs_get_hero_items(7)`
2. Điều chỉnh layout logic
3. Cập nhật CSS grid

## Troubleshooting

### Items không hiển thị
- Kiểm tra **post_status** = "publish"
- Kiểm tra **Display Order** có hợp lệ
- Clear cache nếu sử dụng caching plugin

### Layout bị vỡ
- Kiểm tra CSS classes
- Đảm bảo Bootstrap CSS được load
- Kiểm tra responsive breakpoints

### Icons không hiển thị
- Kiểm tra file SVG icons có tồn tại
- Kiểm tra function `sbs_get_icon()`
- Clear browser cache

## Best Practices

### Content Management
- Sử dụng **Display Order** để sắp xếp logic
- Đặt **Description** ngắn gọn, dễ hiểu
- Sử dụng **Icons** phù hợp với nội dung

### Performance
- Không tạo quá 7 items (ảnh hưởng performance)
- Sử dụng **caching** cho hero items
- Optimize images nếu sử dụng thumbnails

### SEO
- Đặt **Title** có ý nghĩa
- Sử dụng **Description** mô tả rõ ràng
- Thêm **Link** đến trang liên quan

## Support
Nếu gặp vấn đề, kiểm tra:
1. WordPress debug log
2. Browser console errors
3. Theme compatibility
4. Plugin conflicts

# Banner Items Management Guide

## Overview
Banner Items là custom post type được tạo để quản lý động tất cả các banner trong Banner Carousel Section của trang chủ SBS Portal. Hệ thống hỗ trợ **2 loại Image Source** với layout carousel linh hoạt.

## Features
- **Unlimited Banner Items** có thể cấu hình hoàn toàn
- **2 Image Source Options**: Media Library và External URL
- **Link tùy chỉnh** cho mỗi banner (mở tab mới)
- **Sắp xếp thứ tự** hiển thị bằng trường "Display Order"
- **Seamless loop** tự động duplicate banners
- **Fallback content** khi chưa có items

## Layout Structure

### Banner Carousel Section
- **Original Banners**: Hiển thị theo thứ tự từ database
- **Duplicate Banners**: Tự động duplicate để tạo seamless loop
- **Responsive Design**: Tự động điều chỉnh theo số lượng banners

## Admin Panel Access

### 1. Truy cập Banner Items
- Vào **WordPress Admin → Banner Items**
- Hoặc **Banner Items → All Banner Items**

### 2. Tạo Banner Item mới
- Click **"Add New Banner Item"**
- Điền các trường:
  - **Title**: Tiêu đề banner (dùng làm alt text)
  - **Image Source**: Chọn 1 trong 2 loại:
    - **Upload Image to Media Library**: Upload ảnh vào WordPress Media Library
    - **Use External URL**: Sử dụng ảnh từ nguồn bên ngoài
  - **External Image URL**: URL ảnh bên ngoài (nếu chọn External URL)
  - **Link URL**: Link khi click banner (để trống nếu không cần)
  - **Display Order**: Thứ tự hiển thị (1 = first)

### 3. Chỉnh sửa Banner Item
- Click vào **Title** của banner cần sửa
- Thay đổi các trường cần thiết
- Click **"Update"**

### 4. Quick Edit
- Hover vào banner → Click **"Quick Edit"**
- Chỉnh sửa nhanh **Display Order**
- Click **"Update"**

## Image Source Options

### Option 1: Media Library (Featured Image) ⭐ **KHUYẾN NGHỊ**
- Upload ảnh trực tiếp vào WordPress Media Library
- Sử dụng WordPress Media Library
- Tối ưu hóa tự động
- **Ưu điểm**: Quản lý dễ dàng, tối ưu hóa tự động, backup tự động
- **Nhược điểm**: Tốn dung lượng hosting

#### **Cách sử dụng Media Library:**
1. Chọn **"Upload Image to Media Library"**
2. Click **"Set Featured Image"** hoặc **"Add Media"**
3. Upload ảnh mới hoặc chọn từ thư viện
4. Ảnh sẽ tự động được tối ưu hóa

### Option 2: External URL
- Sử dụng ảnh từ nguồn bên ngoài
- Nhập URL đầy đủ
- **Ưu điểm**: Tiết kiệm dung lượng, sử dụng CDN
- **Nhược điểm**: Phụ thuộc vào nguồn bên ngoài

#### **Cách sử dụng External URL:**
1. Chọn **"Use External URL"**
2. Nhập URL đầy đủ vào **External Image URL**
3. **Ví dụ URLs:**
   - `https://example.com/image.jpg` - External website
   - `<?php echo get_template_directory_uri(); ?>/assets/images/gallery-1.jpg` - Theme image
   - `<?php echo get_template_directory_uri(); ?>/assets/images/gallery-2.jpg` - Theme image
   - `<?php echo get_template_directory_uri(); ?>/assets/images/gallery-3.jpg` - Theme image

## Link Functionality

### Link Behavior
- **Có Link**: Click banner mở tab mới với URL đã cấu hình
- **Không có Link**: Banner không clickable
- **Target**: Luôn mở tab mới (`target="_blank"`)

### Link Types Supported
- **Internal Links**: Trang trong website
- **External Links**: Website bên ngoài
- **File Links**: PDF, documents, etc.

## Display Order Logic

### Ordering System
- **Order 1**: Hiển thị đầu tiên
- **Order 2**: Hiển thị thứ hai
- **Order n**: Hiển thị theo thứ tự tăng dần
- Items được sắp xếp theo số thứ tự tăng dần
- Nếu không có order, sử dụng thứ tự tạo

### Seamless Loop
- Banners được duplicate tự động
- Tạo hiệu ứng carousel vô tận
- Không bị giật khi loop

## Sample Data Structure

### Default Banners (3 items) - Sử dụng External URLs (Theme Images)
1. **SBS ドライビングスクール 教習風景** - order: 1, URL: theme assets/gallery-1.jpg
2. **SBS 自動車整備 サービス** - order: 2, URL: theme assets/gallery-2.jpg
3. **SBS 施設案内** - order: 3, URL: theme assets/gallery-3.jpg

### Sample Data Features
- **External URLs**: Sử dụng ảnh có sẵn trong theme
- **Link URLs**: Đặt sẵn "#" (có thể thay đổi)
- **Display Order**: 1, 2, 3 theo thứ tự

## Database Schema
```sql
wp_posts (post_type = 'banner_item')
├── ID
├── post_title
├── post_content
└── post_status

wp_postmeta
├── _banner_item_image_url
├── _banner_item_link_url
├── _banner_item_order
└── _banner_item_use_local

wp_postmeta (Featured Image)
└── _thumbnail_id
```

## Customization

### Thay đổi số lượng banners
1. Sửa parameter trong `sbs_get_banner_items(10)`
2. Điều chỉnh CSS carousel nếu cần
3. Cập nhật JavaScript carousel logic

### Thay đổi carousel behavior
1. Mở file `portal.php`
2. Chỉnh sửa HTML structure
3. Điều chỉnh duplicate logic

### CSS Styling
1. Mở file CSS của theme
2. Tìm `.banner-item` và `.banner-carousel-track`
3. Điều chỉnh styles theo ý muốn

## Troubleshooting

### Banners không hiển thị
- Kiểm tra **post_status** = "publish"
- Kiểm tra **Display Order** có hợp lệ
- Kiểm tra **Image Source** đã cấu hình đúng
- Clear cache nếu sử dụng caching plugin

### Images không load
- **Media Library**: Kiểm tra đã upload và set Featured Image
- **External URL**: Kiểm tra URL có hợp lệ và accessible
- Kiểm tra **Image Source** radio button đã chọn đúng

### Links không hoạt động
- Kiểm tra **Link URL** đã nhập đúng
- Kiểm tra URL có hợp lệ
- Kiểm tra JavaScript console errors
- Kiểm tra popup blocker

### Carousel không chạy
- Kiểm tra CSS carousel styles
- Kiểm tra JavaScript carousel script
- Kiểm tra số lượng banners (cần ít nhất 2)
- Kiểm tra responsive breakpoints

## Best Practices

### Content Management
- Sử dụng **Display Order** để sắp xếp logic
- Đặt **Title** mô tả rõ nội dung banner
- **Media Library**: Sử dụng cho ảnh mới, quan trọng
- **External URLs**: Sử dụng cho ảnh có sẵn, tạm thời

### Performance
- **Media Library**: Tối ưu hóa kích thước (max 1920x1080)
- **External URLs**: Sử dụng CDN và ảnh đã tối ưu
- **Số lượng banners**: Không quá 10 banners (ảnh hưởng performance)
- **Caching**: Sử dụng caching cho banner items

### SEO
- Đặt **Title** có ý nghĩa (dùng làm alt text)
- Sử dụng **Media Library** với alt text tùy chỉnh
- Thêm **Link** đến trang liên quan
- Tối ưu hóa ảnh với tên file có ý nghĩa

### User Experience
- **Link URLs**: Luôn mở tab mới để không mất trang hiện tại
- **Image Quality**: Sử dụng ảnh chất lượng cao
- **Loading Speed**: Tối ưu hóa kích thước ảnh
- **Responsive**: Đảm bảo hiển thị tốt trên mọi thiết bị

## Support
Nếu gặp vấn đề, kiểm tra:
1. WordPress debug log
2. Browser console errors
3. Theme compatibility
4. Plugin conflicts
5. Image permissions
6. URL accessibility

## Advanced Features

### Custom Carousel Controls
- Thêm navigation arrows
- Thêm pagination dots
- Thêm autoplay controls
- Thêm pause on hover

### Responsive Breakpoints
- Mobile: 1 banner visible
- Tablet: 2 banners visible
- Desktop: 3+ banners visible

### Animation Effects
- Fade transitions
- Slide transitions
- Zoom effects
- Parallax effects

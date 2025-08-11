# Blog Posts Management Guide

## Overview
Blog Posts là custom post type mới được tạo để quản lý các bài viết blog và tin tức cho SBS Portal. Thay vì sử dụng mock-data.json, tất cả dữ liệu blog giờ đây được lưu trữ trong database WordPress.

## Features
- **Custom Post Type**: `blog` với giao diện admin riêng biệt
- **Custom Fields**: Category, Status, Display Order
- **Featured Image Support**: Hỗ trợ hình ảnh đại diện
- **Admin Enhancements**: Custom columns, sorting, filtering
- **Dynamic Display**: Tự động hiển thị trên frontend

## Admin Panel Access
1. **WordPress Admin** → **Blog Posts** (menu chính)
2. **Add New**: Tạo bài viết mới
3. **All Blog Posts**: Quản lý tất cả bài viết

## Custom Fields

### 1. Category
- **BLOG**: Bài viết blog thông thường
- **NEWS**: Tin tức, thông báo
- **EVENT**: Sự kiện, hoạt động
- **CAMPAIGN**: Chiến dịch, khuyến mãi

### 2. Status
- **Draft**: Bản nháp (không hiển thị)
- **Published**: Đã xuất bản (hiển thị trên frontend)
- **Private**: Riêng tư (chỉ admin thấy)

### 3. Display Order
- Số thứ tự hiển thị (1 = đầu tiên)
- Sắp xếp theo thứ tự tăng dần

## How to Create a Blog Post

### Step 1: Basic Information
1. **Title**: Tiêu đề bài viết
2. **Content**: Nội dung chính (sử dụng WordPress editor)
3. **Excerpt**: Tóm tắt ngắn gọn (hiển thị trên card)

### Step 2: Featured Image
1. **Set Featured Image**: Sử dụng box bên phải
2. **Upload**: Tải ảnh mới từ máy tính
3. **Media Library**: Chọn từ thư viện có sẵn

### Step 3: Custom Fields
1. **Category**: Chọn danh mục phù hợp
2. **Status**: Đặt trạng thái xuất bản
3. **Order**: Sắp xếp thứ tự hiển thị

### Step 4: Publish
1. **Preview**: Xem trước bài viết
2. **Publish**: Xuất bản bài viết
3. **Update**: Cập nhật thay đổi

## Admin List Features

### Custom Columns
- **Title**: Tên bài viết
- **Category**: Danh mục
- **Status**: Trạng thái
- **Order**: Thứ tự hiển thị
- **Date**: Ngày tạo/cập nhật

### Sorting & Filtering
- **Click column headers** để sắp xếp
- **Category**: Sắp xếp theo danh mục
- **Status**: Sắp xếp theo trạng thái
- **Order**: Sắp xếp theo thứ tự

## Frontend Display

### Blog Section (Portal Page)
- Hiển thị 6 bài viết mới nhất
- Sắp xếp theo Display Order
- Chỉ hiển thị bài viết có Status = "published"

### Blog Cards
- **Featured Image**: Hình ảnh đại diện
- **Title**: Tiêu đề bài viết (có link)
- **Excerpt**: Tóm tắt nội dung
- **Category**: Tag danh mục
- **Date**: Ngày đăng

### Archive Page
- **URL**: `/blog/` (tự động tạo)
- **Layout**: Grid layout với pagination
- **Filtering**: Theo danh mục và trạng thái

## Sample Data
Khi kích hoạt theme, 5 bài viết mẫu sẽ được tạo tự động:

1. **敬愛学園女子バレー部敬愛学園女子バレー部** (BLOG, Order: 1)
2. **富士山ツーリング** (NEWS, Order: 2)
3. **奥多摩ツーリング** (BLOG, Order: 3)
4. **春の教習キャンペーン開始** (NEWS, Order: 4)
5. **新しい教習車両を導入しました** (BLOG, Order: 5)

## Database Schema

### Post Meta Fields
- `_blog_post_category`: Danh mục bài viết
- `_blog_post_status`: Trạng thái xuất bản
- `_blog_post_order`: Thứ tự hiển thị

### WordPress Fields
- `post_title`: Tiêu đề bài viết
- `post_content`: Nội dung chính
- `post_excerpt`: Tóm tắt
- `post_status`: Trạng thái WordPress
- `post_type`: 'blog'

## Functions

### Main Functions
- `sbs_get_blog_posts($limit, $category)`: Lấy danh sách bài viết
- `sbs_register_blog_post_post_type()`: Đăng ký custom post type
- `sbs_create_sample_blog_posts()`: Tạo dữ liệu mẫu

### Usage Examples
```php
// Get all published blog posts
$posts = sbs_get_blog_posts();

// Get only NEWS category posts
$news_posts = sbs_get_blog_posts(10, 'NEWS');

// Get limited posts
$recent_posts = sbs_get_blog_posts(3);
```

## Migration from Mock Data
- **Old**: Sử dụng `mock-data.json` với `blog_posts`
- **New**: Sử dụng WordPress database với `blog_post` CPT
- **Benefits**: Quản lý dễ dàng, SEO tốt hơn, tương tác người dùng

## Troubleshooting

### Common Issues
1. **Posts not showing**: Kiểm tra Status = "published"
2. **Wrong order**: Kiểm tra Display Order field
3. **No images**: Đảm bảo đã set Featured Image
4. **Category not working**: Kiểm tra meta field `_blog_post_category`

### Debug Tips
- Sử dụng `var_dump($posts)` để kiểm tra data
- Kiểm tra WordPress debug log
- Verify meta fields trong database

## Best Practices
1. **Always set Featured Image** cho mỗi bài viết
2. **Use meaningful categories** để phân loại rõ ràng
3. **Set proper Display Order** để sắp xếp logic
4. **Write compelling excerpts** để thu hút người đọc
5. **Regular updates** để giữ nội dung mới mẻ

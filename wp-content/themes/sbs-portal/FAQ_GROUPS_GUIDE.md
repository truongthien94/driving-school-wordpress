# FAQ Groups Management Guide

## Overview
FAQ Groups là custom post type mới được tạo để quản lý các nhóm câu hỏi thường gặp cho SBS Portal. Thay vì sử dụng mock-data.json, tất cả dữ liệu FAQ giờ đây được lưu trữ trong database WordPress.

## Features
- **Custom Post Type**: `faq_group` với giao diện admin riêng biệt
- **Custom Fields**: Color, Expanded State, Display Order
- **Dynamic Questions**: Thêm/xóa câu hỏi động trong mỗi group
- **Admin Enhancements**: Custom columns, sorting, filtering
- **Dynamic Display**: Tự động hiển thị trên frontend

## Admin Panel Access
1. **WordPress Admin** → **FAQ Groups** (menu chính)
2. **Add New**: Tạo nhóm FAQ mới
3. **All FAQ Groups**: Quản lý tất cả nhóm FAQ

## Custom Fields

### 1. Group Settings
- **Color**: Màu sắc cho nhóm FAQ (color picker)
- **Default State**: Trạng thái mặc định (Collapsed/Expanded)
- **Display Order**: Thứ tự hiển thị (1 = đầu tiên)

### 2. FAQ Questions
- **Question Text**: Nội dung câu hỏi
- **Brief Answer**: Trả lời ngắn gọn
- **Detailed Answer**: Trả lời chi tiết
- **Default State**: Trạng thái mặc định của câu hỏi

## How to Create an FAQ Group

### Step 1: Basic Information
1. **Title**: Tên nhóm FAQ (ví dụ: "これから免許を取る方")
2. **Color**: Chọn màu sắc cho nhóm
3. **Default State**: Chọn trạng thái mặc định
4. **Display Order**: Sắp xếp thứ tự hiển thị

### Step 2: Add Questions
1. **Click "Add New Question"** để thêm câu hỏi mới
2. **Fill in fields**:
   - Question Text: Nội dung câu hỏi
   - Brief Answer: Trả lời ngắn gọn
   - Detailed Answer: Trả lời chi tiết
   - Default State: Collapsed/Expanded
3. **Repeat** cho tất cả câu hỏi cần thiết

### Step 3: Manage Questions
- **Edit**: Click vào các field để chỉnh sửa
- **Remove**: Click "Remove" để xóa câu hỏi
- **Reorder**: Các câu hỏi sẽ hiển thị theo thứ tự thêm vào

### Step 4: Publish
1. **Preview**: Xem trước nhóm FAQ
2. **Publish**: Xuất bản nhóm FAQ
3. **Update**: Cập nhật thay đổi

## Admin List Features

### Custom Columns
- **Title**: Tên nhóm FAQ
- **Questions**: Số lượng câu hỏi trong nhóm
- **Color**: Màu sắc của nhóm (visual preview)
- **Order**: Thứ tự hiển thị
- **Date**: Ngày tạo/cập nhật

### Sorting & Filtering
- **Click column headers** để sắp xếp
- **Questions**: Sắp xếp theo số lượng câu hỏi
- **Order**: Sắp xếp theo thứ tự hiển thị

## Frontend Display

### FAQ Section (Portal Page)
- Hiển thị tất cả nhóm FAQ
- Sắp xếp theo Display Order
- Chỉ hiển thị nhóm có Status = "published"

### FAQ Groups
- **Group Header**: Tên nhóm với màu sắc tương ứng
- **Expand/Collapse**: Click để mở/đóng nhóm
- **Questions**: Danh sách câu hỏi trong nhóm

### FAQ Items
- **Question**: Tiêu đề câu hỏi (clickable)
- **Answer**: Nội dung trả lời (expandable)
- **Visual States**: Plus/minus icons cho trạng thái

## Sample Data
Khi kích hoạt theme, 1 nhóm FAQ mẫu sẽ được tạo tự động:

**これから免許を取る方** (Color: #DD1F01, Order: 1)
- Câu hỏi 1: "入学から卒業までの流れをざっと教えて欲しいのですが…"
- Câu hỏi 2: "入所前に一度どんなところか見ておきたいのですが、見学は出来ますか？"

## Database Schema

### Post Meta Fields
- `_faq_group_color`: Màu sắc nhóm FAQ
- `_faq_group_expanded`: Trạng thái mặc định
- `_faq_group_order`: Thứ tự hiển thị
- `_faq_group_questions`: Mảng câu hỏi và trả lời

### Questions Structure
```php
array(
    'id' => 'unique_id',
    'question' => 'Question text',
    'answer' => 'Brief answer',
    'detail' => 'Detailed answer',
    'expanded' => '0' // 0 = collapsed, 1 = expanded
)
```

## Functions

### Main Functions
- `sbs_get_faq_groups()`: Lấy danh sách nhóm FAQ
- `sbs_register_faq_group_post_type()`: Đăng ký custom post type
- `sbs_create_sample_faq_groups()`: Tạo dữ liệu mẫu

### Usage Examples
```php
// Get all FAQ groups
$faq_groups = sbs_get_faq_groups();

// Access group data
foreach ($faq_groups as $group) {
    echo $group['title']; // Group title
    echo $group['color']; // Group color
    echo $group['expanded']; // Default state
    
    // Access questions
    foreach ($group['questions'] as $question) {
        echo $question['question']; // Question text
        echo $question['answer']; // Brief answer
        echo $question['detail']; // Detailed answer
    }
}
```

## Migration from Mock Data
- **Old**: Sử dụng `mock-data.json` với `faq_groups`
- **New**: Sử dụng WordPress database với `faq_group` CPT
- **Benefits**: Quản lý dễ dàng, SEO tốt hơn, tương tác người dùng

## Troubleshooting

### Common Issues
1. **Groups not showing**: Kiểm tra Status = "published"
2. **Wrong order**: Kiểm tra Display Order field
3. **No questions**: Đảm bảo đã thêm câu hỏi vào nhóm
4. **Color not working**: Kiểm tra meta field `_faq_group_color`

### Debug Tips
- Sử dụng `var_dump($faq_groups)` để kiểm tra data
- Kiểm tra WordPress debug log
- Verify meta fields trong database

## Best Practices
1. **Use meaningful group titles** để phân loại rõ ràng
2. **Set appropriate colors** để tạo visual hierarchy
3. **Write clear questions** và comprehensive answers
4. **Set proper display order** để sắp xếp logic
5. **Regular updates** để giữ nội dung mới mẻ

## Advanced Features
- **Dynamic question management**: Thêm/xóa câu hỏi không giới hạn
- **Flexible content**: Hỗ trợ cả brief và detailed answers
- **State management**: Kiểm soát trạng thái mở/đóng
- **Ordering system**: Sắp xếp linh hoạt theo nhu cầu

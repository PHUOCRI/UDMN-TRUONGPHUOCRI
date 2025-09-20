<?php
/**
 * ACF Fields Configuration
 */

if (!defined('ABSPATH')) {
    exit;
}

class VJL_Travel_ACF_Fields {
    
    public function __construct() {
        add_action('acf/init', array($this, 'register_acf_fields'));
    }
    
    public function register_acf_fields() {
        if (function_exists('acf_add_local_field_group')) {
            $this->register_tour_fields();
            $this->register_destination_fields();
            $this->register_service_fields();
            $this->register_testimonial_fields();
            $this->register_gallery_fields();
        }
    }
    
    private function register_tour_fields() {
        acf_add_local_field_group(array(
            'key' => 'group_tour_fields',
            'title' => __('Thông tin Tour', 'vjl-travel'),
            'fields' => array(
                array(
                    'key' => 'field_tour_price',
                    'label' => __('Giá Tour', 'vjl-travel'),
                    'name' => 'tour_price',
                    'type' => 'number',
                    'instructions' => __('Nhập giá tour (VNĐ)', 'vjl-travel'),
                    'required' => 1,
                    'default_value' => '',
                    'placeholder' => '1000000',
                ),
                array(
                    'key' => 'field_tour_duration',
                    'label' => __('Thời gian', 'vjl-travel'),
                    'name' => 'tour_duration',
                    'type' => 'text',
                    'instructions' => __('Ví dụ: 1 ngày, 2 ngày 1 đêm', 'vjl-travel'),
                    'required' => 1,
                    'default_value' => '',
                    'placeholder' => '1 ngày',
                ),
                array(
                    'key' => 'field_tour_departure',
                    'label' => __('Điểm khởi hành', 'vjl-travel'),
                    'name' => 'tour_departure',
                    'type' => 'text',
                    'instructions' => __('Điểm khởi hành của tour', 'vjl-travel'),
                    'required' => 1,
                    'default_value' => 'TP Huế',
                ),
                array(
                    'key' => 'field_tour_destinations',
                    'label' => __('Điểm đến', 'vjl-travel'),
                    'name' => 'tour_destinations',
                    'type' => 'repeater',
                    'instructions' => __('Danh sách các điểm đến trong tour', 'vjl-travel'),
                    'required' => 1,
                    'sub_fields' => array(
                        array(
                            'key' => 'field_destination_name',
                            'label' => __('Tên điểm đến', 'vjl-travel'),
                            'name' => 'destination_name',
                            'type' => 'text',
                            'required' => 1,
                        ),
                        array(
                            'key' => 'field_destination_description',
                            'label' => __('Mô tả', 'vjl-travel'),
                            'name' => 'destination_description',
                            'type' => 'textarea',
                        ),
                    ),
                    'min' => 1,
                    'max' => 10,
                ),
                array(
                    'key' => 'field_tour_includes',
                    'label' => __('Bao gồm', 'vjl-travel'),
                    'name' => 'tour_includes',
                    'type' => 'repeater',
                    'instructions' => __('Các dịch vụ bao gồm trong tour', 'vjl-travel'),
                    'sub_fields' => array(
                        array(
                            'key' => 'field_include_item',
                            'label' => __('Dịch vụ', 'vjl-travel'),
                            'name' => 'include_item',
                            'type' => 'text',
                            'required' => 1,
                        ),
                    ),
                    'min' => 1,
                    'max' => 20,
                ),
                array(
                    'key' => 'field_tour_excludes',
                    'label' => __('Không bao gồm', 'vjl-travel'),
                    'name' => 'tour_excludes',
                    'type' => 'repeater',
                    'instructions' => __('Các dịch vụ không bao gồm trong tour', 'vjl-travel'),
                    'sub_fields' => array(
                        array(
                            'key' => 'field_exclude_item',
                            'label' => __('Dịch vụ', 'vjl-travel'),
                            'name' => 'exclude_item',
                            'type' => 'text',
                            'required' => 1,
                        ),
                    ),
                    'min' => 0,
                    'max' => 20,
                ),
                array(
                    'key' => 'field_tour_schedule',
                    'label' => __('Lịch trình', 'vjl-travel'),
                    'name' => 'tour_schedule',
                    'type' => 'repeater',
                    'instructions' => __('Lịch trình chi tiết của tour', 'vjl-travel'),
                    'sub_fields' => array(
                        array(
                            'key' => 'field_schedule_time',
                            'label' => __('Thời gian', 'vjl-travel'),
                            'name' => 'schedule_time',
                            'type' => 'text',
                            'required' => 1,
                            'placeholder' => '08:00',
                        ),
                        array(
                            'key' => 'field_schedule_activity',
                            'label' => __('Hoạt động', 'vjl-travel'),
                            'name' => 'schedule_activity',
                            'type' => 'textarea',
                            'required' => 1,
                        ),
                    ),
                    'min' => 1,
                    'max' => 20,
                ),
                array(
                    'key' => 'field_tour_gallery',
                    'label' => __('Thư viện ảnh', 'vjl-travel'),
                    'name' => 'tour_gallery',
                    'type' => 'gallery',
                    'instructions' => __('Thêm ảnh cho tour', 'vjl-travel'),
                ),
                array(
                    'key' => 'field_tour_featured',
                    'label' => __('Tour nổi bật', 'vjl-travel'),
                    'name' => 'tour_featured',
                    'type' => 'true_false',
                    'instructions' => __('Đánh dấu tour nổi bật', 'vjl-travel'),
                    'default_value' => 0,
                ),
                array(
                    'key' => 'field_tour_rating',
                    'label' => __('Đánh giá', 'vjl-travel'),
                    'name' => 'tour_rating',
                    'type' => 'number',
                    'instructions' => __('Đánh giá từ 1-5 sao', 'vjl-travel'),
                    'min' => 1,
                    'max' => 5,
                    'step' => 0.1,
                    'default_value' => 5,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'tours',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
        ));
    }
    
    private function register_destination_fields() {
        acf_add_local_field_group(array(
            'key' => 'group_destination_fields',
            'title' => __('Thông tin Điểm đến', 'vjl-travel'),
            'fields' => array(
                array(
                    'key' => 'field_destination_location',
                    'label' => __('Vị trí', 'vjl-travel'),
                    'name' => 'destination_location',
                    'type' => 'google_map',
                    'instructions' => __('Chọn vị trí trên bản đồ', 'vjl-travel'),
                    'required' => 1,
                ),
                array(
                    'key' => 'field_destination_address',
                    'label' => __('Địa chỉ', 'vjl-travel'),
                    'name' => 'destination_address',
                    'type' => 'text',
                    'instructions' => __('Địa chỉ chi tiết', 'vjl-travel'),
                    'required' => 1,
                ),
                array(
                    'key' => 'field_destination_opening_hours',
                    'label' => __('Giờ mở cửa', 'vjl-travel'),
                    'name' => 'destination_opening_hours',
                    'type' => 'text',
                    'instructions' => __('Ví dụ: 8:00 - 17:00', 'vjl-travel'),
                    'default_value' => '8:00 - 17:00',
                ),
                array(
                    'key' => 'field_destination_ticket_price',
                    'label' => __('Giá vé', 'vjl-travel'),
                    'name' => 'destination_ticket_price',
                    'type' => 'text',
                    'instructions' => __('Giá vé tham quan', 'vjl-travel'),
                    'placeholder' => 'Miễn phí hoặc 50,000 VNĐ',
                ),
                array(
                    'key' => 'field_destination_features',
                    'label' => __('Tính năng nổi bật', 'vjl-travel'),
                    'name' => 'destination_features',
                    'type' => 'repeater',
                    'instructions' => __('Các tính năng nổi bật của điểm đến', 'vjl-travel'),
                    'sub_fields' => array(
                        array(
                            'key' => 'field_feature_name',
                            'label' => __('Tên tính năng', 'vjl-travel'),
                            'name' => 'feature_name',
                            'type' => 'text',
                            'required' => 1,
                        ),
                        array(
                            'key' => 'field_feature_description',
                            'label' => __('Mô tả', 'vjl-travel'),
                            'name' => 'feature_description',
                            'type' => 'textarea',
                        ),
                    ),
                    'min' => 1,
                    'max' => 10,
                ),
                array(
                    'key' => 'field_destination_gallery',
                    'label' => __('Thư viện ảnh', 'vjl-travel'),
                    'name' => 'destination_gallery',
                    'type' => 'gallery',
                    'instructions' => __('Thêm ảnh cho điểm đến', 'vjl-travel'),
                ),
                array(
                    'key' => 'field_destination_rating',
                    'label' => __('Đánh giá', 'vjl-travel'),
                    'name' => 'destination_rating',
                    'type' => 'number',
                    'instructions' => __('Đánh giá từ 1-5 sao', 'vjl-travel'),
                    'min' => 1,
                    'max' => 5,
                    'step' => 0.1,
                    'default_value' => 5,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'destinations',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
        ));
    }
    
    private function register_service_fields() {
        acf_add_local_field_group(array(
            'key' => 'group_service_fields',
            'title' => __('Thông tin Dịch vụ', 'vjl-travel'),
            'fields' => array(
                array(
                    'key' => 'field_service_price',
                    'label' => __('Giá dịch vụ', 'vjl-travel'),
                    'name' => 'service_price',
                    'type' => 'number',
                    'instructions' => __('Nhập giá dịch vụ (VNĐ)', 'vjl-travel'),
                    'required' => 1,
                    'default_value' => '',
                    'placeholder' => '500000',
                ),
                array(
                    'key' => 'field_service_duration',
                    'label' => __('Thời gian', 'vjl-travel'),
                    'name' => 'service_duration',
                    'type' => 'text',
                    'instructions' => __('Thời gian thực hiện dịch vụ', 'vjl-travel'),
                    'required' => 1,
                    'default_value' => '',
                    'placeholder' => '2 giờ',
                ),
                array(
                    'key' => 'field_service_features',
                    'label' => __('Tính năng', 'vjl-travel'),
                    'name' => 'service_features',
                    'type' => 'repeater',
                    'instructions' => __('Các tính năng của dịch vụ', 'vjl-travel'),
                    'sub_fields' => array(
                        array(
                            'key' => 'field_service_feature',
                            'label' => __('Tính năng', 'vjl-travel'),
                            'name' => 'service_feature',
                            'type' => 'text',
                            'required' => 1,
                        ),
                    ),
                    'min' => 1,
                    'max' => 15,
                ),
                array(
                    'key' => 'field_service_icon',
                    'label' => __('Icon', 'vjl-travel'),
                    'name' => 'service_icon',
                    'type' => 'text',
                    'instructions' => __('Tên icon Font Awesome (ví dụ: fa-car, fa-hotel)', 'vjl-travel'),
                    'default_value' => 'fa-star',
                ),
                array(
                    'key' => 'field_service_featured',
                    'label' => __('Dịch vụ nổi bật', 'vjl-travel'),
                    'name' => 'service_featured',
                    'type' => 'true_false',
                    'instructions' => __('Đánh dấu dịch vụ nổi bật', 'vjl-travel'),
                    'default_value' => 0,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'services',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
        ));
    }
    
    private function register_testimonial_fields() {
        acf_add_local_field_group(array(
            'key' => 'group_testimonial_fields',
            'title' => __('Thông tin Đánh giá', 'vjl-travel'),
            'fields' => array(
                array(
                    'key' => 'field_testimonial_author',
                    'label' => __('Tên khách hàng', 'vjl-travel'),
                    'name' => 'testimonial_author',
                    'type' => 'text',
                    'instructions' => __('Tên người đánh giá', 'vjl-travel'),
                    'required' => 1,
                ),
                array(
                    'key' => 'field_testimonial_rating',
                    'label' => __('Đánh giá sao', 'vjl-travel'),
                    'name' => 'testimonial_rating',
                    'type' => 'number',
                    'instructions' => __('Đánh giá từ 1-5 sao', 'vjl-travel'),
                    'required' => 1,
                    'min' => 1,
                    'max' => 5,
                    'step' => 1,
                    'default_value' => 5,
                ),
                array(
                    'key' => 'field_testimonial_tour',
                    'label' => __('Tour đã tham gia', 'vjl-travel'),
                    'name' => 'testimonial_tour',
                    'type' => 'post_object',
                    'instructions' => __('Chọn tour mà khách hàng đã tham gia', 'vjl-travel'),
                    'post_type' => array('tours'),
                    'allow_null' => 1,
                ),
                array(
                    'key' => 'field_testimonial_avatar',
                    'label' => __('Ảnh đại diện', 'vjl-travel'),
                    'name' => 'testimonial_avatar',
                    'type' => 'image',
                    'instructions' => __('Ảnh đại diện của khách hàng', 'vjl-travel'),
                ),
                array(
                    'key' => 'field_testimonial_featured',
                    'label' => __('Đánh giá nổi bật', 'vjl-travel'),
                    'name' => 'testimonial_featured',
                    'type' => 'true_false',
                    'instructions' => __('Đánh dấu đánh giá nổi bật', 'vjl-travel'),
                    'default_value' => 0,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'testimonials',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
        ));
    }
    
    private function register_gallery_fields() {
        acf_add_local_field_group(array(
            'key' => 'group_gallery_fields',
            'title' => __('Thông tin Thư viện ảnh', 'vjl-travel'),
            'fields' => array(
                array(
                    'key' => 'field_gallery_images',
                    'label' => __('Ảnh', 'vjl-travel'),
                    'name' => 'gallery_images',
                    'type' => 'gallery',
                    'instructions' => __('Thêm ảnh vào thư viện', 'vjl-travel'),
                    'required' => 1,
                ),
                array(
                    'key' => 'field_gallery_description',
                    'label' => __('Mô tả', 'vjl-travel'),
                    'name' => 'gallery_description',
                    'type' => 'textarea',
                    'instructions' => __('Mô tả về bộ ảnh', 'vjl-travel'),
                ),
                array(
                    'key' => 'field_gallery_location',
                    'label' => __('Địa điểm', 'vjl-travel'),
                    'name' => 'gallery_location',
                    'type' => 'text',
                    'instructions' => __('Địa điểm chụp ảnh', 'vjl-travel'),
                ),
                array(
                    'key' => 'field_gallery_date',
                    'label' => __('Ngày chụp', 'vjl-travel'),
                    'name' => 'gallery_date',
                    'type' => 'date_picker',
                    'instructions' => __('Ngày chụp ảnh', 'vjl-travel'),
                ),
                array(
                    'key' => 'field_gallery_featured',
                    'label' => __('Thư viện nổi bật', 'vjl-travel'),
                    'name' => 'gallery_featured',
                    'type' => 'true_false',
                    'instructions' => __('Đánh dấu thư viện nổi bật', 'vjl-travel'),
                    'default_value' => 0,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'gallery',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
        ));
    }
}

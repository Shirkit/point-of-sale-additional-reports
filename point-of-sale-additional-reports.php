<?php
/**
 * Plugin Name:       Additional Reports for POS
 * Plugin URI:        https://github.com/Shirkit/point-of-sale-additional-reports
 * Description:       Adds more reports for the POS System.
 * Version:           1.0.0
 * Author:            Shirkit
 * License:           MIT License
 * License URI:       https://raw.githubusercontent.com/Shirkit/point-of-sale-additional-reports/master/LICENSE
 * GitHub Plugin URI: https://github.com/Shirkit/point-of-sale-additional-reports
 */

add_filter('woocommerce_reports_charts', 'my_custom_woocommerce_admin_reports', 30, 1);
function my_custom_woocommerce_admin_reports( $reports ) {
    $custom_product_sales = array(
        'custom_product_sales' => array(
            'title'         => 'Custom Products Sales',
            'description'   => '',
            'hide_title'    => true,
            'callback'      => 'custom_products_sales_callback',
        ),
    );
    $orders_with_comments = array(
        'orders_with_comments' => array(
            'title'         => 'Orders With Comments',
            'description'   => '',
            'hide_title'    => true,
            'callback'      => 'orders_with_comments_callback',
        ),
    );
    // This can be: orders, customers, stock or taxes, based on where we want to insert our new reports page
    $reports['pos']['reports'] = array_merge( $reports['pos']['reports'], $custom_product_sales);
	$reports['pos']['reports'] = array_merge( $reports['pos']['reports'], $orders_with_comments);
    return $reports;
}

function custom_products_sales_callback() {
	include_once('class-wc-admin-report-custom-product.php');
    $report = new WC_POS_Report_Custom_Product_Sales();
    $report->output_report();
}

function orders_with_comments_callback() {
	include_once('class-wc-admin-report-order-comments.php');
    $report = new WC_Report_Orders_With_Comments();
    $report->output_report();
}
?>


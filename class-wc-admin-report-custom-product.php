<?php
class WC_POS_Report_Custom_Product_Sales extends WC_Admin_Report
{
    /**
     * Output the report.
     */
    public function output_report()
    {
        $ranges = array(
            'year' => __('Year', 'woocommerce') ,
            'last_month' => __('Last month', 'woocommerce') ,
            'month' => __('This month', 'woocommerce') ,
			'7day' => __('Last 7 days', 'woocommerce')
        );
        $current_range = !empty($_GET['range']) ? sanitize_text_field($_GET['range']) : 'month';
        if (!in_array($current_range, array(
            'custom',
            'year',
            'last_month',
            '7day'
        )))
        {
            $current_range = 'month';
        }
        $this->check_current_range_nonce($current_range);
        $this->calculate_current_range($current_range);
        $hide_sidebar = true;
        include (WC()->plugin_path() . '/includes/admin/views/html-report-by-date.php');
    }
    /**
     * Get the main chart.
     */
    public function get_main_chart()
    {
        global $wpdb;
		$custom_product_id = get_option('wc_pos_custom_product_id');
        $result = $this->get_order_report_data(array(
            'data' => array(
                '_product_id' => array(
                    'type' => 'order_item_meta',
                    'order_item_type' => 'line_item',
                    'function' => '',
                    'name' => 'custom_product_id'
                ),
                '_line_total' => array(
                    'type' => 'order_item_meta',
                    'order_item_type' => 'line_item',
                    'function' => '',
                    'name' => 'price'
                ),
				'order_item_id' => array(
                    'type' => 'order_item',
                    'order_item_type' => 'line_item',
                    'function' => '',
                    'name' => 'product_item_id'
                ),
				'order_item_name' => array(
					'type' => 'order_item',
					'order_item_type' => 'line_item',
					'function' => '',
					'name' => 'name',
				),
				'order_id' => array(
					'type' => 'order_item',
					'order_item_type' => 'line_item',
					'function' => '',
					'name' => 'order_id',
				)
            ),
			'where_meta' => array(
				array(
					'type' => 'order_item_meta',
					'meta_key' => '_product_id',
					'meta_value' => strval($custom_product_id),
					'operator' => '=',
				)
			),
            'query_type' => 'get_results',
            'filter_range' => true,
        ));
?>
    <table class="widefat">
      <thead>
          <tr>
              <th><strong>Product Name</strong></th>
              <th><strong>Product Price</strong></th>
              <th><strong>Order ID</strong></th>
          </tr>
      </thead>
      <tbody>
          <?php foreach ($result as $order)
        {
			$button = array(
				'url'    => admin_url( 'post.php?post=' . $order->order_id . ' &action=edit' ),
				'name'   => __( 'View order', 'woocommerce' ),
				'action' => 'view',
			);
?>
          <tr>
              <td><?php echo $order->name; ?></td>
			  <td><?php echo $order->price; ?></td>
			  <td><?php printf( '<a class="button tips %s" href="%s" data-tip="%s">%s</a>', esc_attr( $button['action'] ), esc_url( $button['url'] ), esc_attr( $button['name'] ), esc_attr( $button['name'] ) ); ?></td>
          </tr>
          <?php
        } ?>
      </tbody>
    </table>
    <?php
    }
}
?>

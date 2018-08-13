<?php
/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


$order = wc_get_order( $order_id );

$_lang = get_custom_field('X_lang');
global $sitepress; $sitepress->switch_lang($_lang);

$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
?>
<h2><?php _e( 'Order Details', 'woocommerce' ); ?></h2>

<table class="shop_table order_details">
	<thead>
		<tr>
			<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th class="product-total"><?php _e( 'Total', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach( $order->get_items() as $item_id => $item ) {
				$product = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );

				wc_get_template( 'order/order-details-item.php', array(
					'order'			     => $order,
					'item_id'		     => $item_id,
					'item'			     => $item,
					'show_purchase_note' => $show_purchase_note,
					'purchase_note'	     => $product ? get_post_meta( $product->id, '_purchase_note', true ) : '',
					'product'	         => $product,
				) );
			}
		?>
		<?php do_action( 'woocommerce_order_items_table', $order ); ?>
	</tbody> 

	
 
	<tfoot>  
		<?php $totals = $order->get_order_item_totals(); ?>
		<tr>
			<th scope="row"><?php echo $totals['cart_subtotal']['label']; ?></th>
			<td><?php echo $totals['cart_subtotal']['value']; ?></td>
		</tr>

		<?php foreach($order->get_items('fee') as $_fee) : ?>   
			 <!-- <?php echo '<pre>'.print_r($_fee->get_data(), true).'</pre>'; ?>   -->
			<tr class="fee">
				<th><?php echo $_fee['name']; ?></th>
				<td data-title="<?php echo $_fee['name']; ?>"><?php echo wc_price($_fee['total']); ?>  
				</td>
			</tr>
		<?php endforeach; ?> 

		<tr>
			<th scope="row"><?php echo $totals['order_total']['label']; ?></th>
			<td><?php echo $totals['order_total']['value']; ?></td>
		</tr>
	</tfoot> 
</table>

<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>

<?php wc_get_template( 'order/order-details-customer.php', array( 'order' =>  $order ) ); ?>

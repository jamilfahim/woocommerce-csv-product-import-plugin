<?php
/*********************************************
 *        admin display
 *********************************************/

function wc_csv_product_menu_page() {
   add_menu_page(
       __( 'Wc Csv Upload', 'softtech' ),
       'Wc Csv Upload',
       'manage_options',
       'csvupload',
       'wc_csv_deshboard_display',
       'dashicons-image-filter',
       //plugins_url( 'myplugin/images/icon.png' ),
       58
   );
 
}

add_action( 'admin_menu','wc_csv_product_menu_page') ;


/**
 * Display a custom menu page
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function wc_csv_deshboard_display(){
	
	?>
	 <h2 class="wc-csv-title"> Import products </h2>
	<div class="deshboard">
		<div class="l-box">
        <h2 class="wc-csv-title"> Import products from a CSV file </h2>
			<p class="wc-slug">This tool allows you to automatic import (or merge) product data to your store from a CSV file.</p>
            <form action="" method="post" enctype="multipart/form-data">
            <!-- <span>Select time</span>
            <input type="time" name="csv_time">   -->
            <p>Choose a CSV file from your computer:</p>   
            <input type="file" name="file_upload" id="file_upload"><br>
            <input type="submit" value="Upload" name="submit">
            </form>
		</div>
	</div>
	
	
<?php
 
 $upload_dir = wp_upload_dir();
 $upload_dir_url = $upload_dir['path'];
 //var_dump($upload_dir_url);
 
if (isset($_FILES["file_upload"]) ) {
    $dir = $upload_dir_url."/";
    $file_name = $_FILES["file_upload"]["name"];
    $size = $_FILES["file_upload"]["size"];
    $tmp_file_name = $_FILES["file_upload"]["tmp_name"];
    // echo $tmp_file_name . "<br>";
    // echo $file_name . "<br>";
    // echo $size;
    $file_uploaded = move_uploaded_file($tmp_file_name, $dir . $file_name);
    //echo $upload_dir['url'] . '/' . $file_name;

    update_option('latest_csv_product', $upload_dir['url'] . '/' . $file_name);
    update_option('csv_time',$_POST["csv_time"]);
   
           
    //get csv product data
    $start_row = 1;
    $dir_file= get_option('latest_csv_product');
    $csv_file = fopen($dir_file, "r");
   
    $read_data = fgetcsv($csv_file, 1000, ",");
    if (($csv_file = fopen($dir_file, "r")) !== FALSE) {

        $products_data = [];
      
        var_dump($products_data);
        
        while(($read_data = fgetcsv($csv_file, 1000, ",")) !== FALSE){
            
            //push all product in array
            $products_data[] = [
                "product_id" => $read_data[0],
                "product_type" => $read_data[1],
                "product_sku" => $read_data[2],
                "product_name" => $read_data[3],
                "product_Published" => $read_data[4],
                "is_featured" => $read_data[5],
                "visibility_in_catalog" => $read_data[6],
                "short_description" => $read_data[7],
                "description" => $read_data[8],
                "date_sale_price_starts" => $read_data[9],
                "date_sale_price_ends" => $read_data[10],
                "tax_status" => $read_data[11],
                "tax_class" => $read_data[12],
                "in_stock" => $read_data[13],
                "stock" => $read_data[14],
                "backorders_allowed" => $read_data[15],
                "sold_individually" => $read_data[16],
                "weight_lbs" => $read_data[17],
                "length_in" => $read_data[18],
                "width_in" => $read_data[19],
                "height_in" => $read_data[20],
                "allow_customer_reviews" => $read_data[21],
                "purchase_note" => $read_data[22],
                "sale_price" => $read_data[23],
                "regular_price" => $read_data[24],
                "categories" => $read_data[25],
                "tags" => $read_data[26],
                "shipping_class" => $read_data[27],
                "images" => $read_data[28],
                "download_limit" => $read_data[29],
                "download_expiry_days" => $read_data[30],
                "parent" => $read_data[31],
                "grouped_products" => $read_data[32],
                "upsells" => $read_data[33],
                "cross_sells" => $read_data[34],
                "external_URL" => $read_data[35],
                "button_text" => $read_data[36],
                "position" => $read_data[37],
                "attribute_1_name" => $read_data[38],
                "attribute_1_value(s)" => $read_data[39],
                "attribute_1_visible" => $read_data[40],
                "attribute_1_global" => $read_data[41],
                "attribute_2_name" => $read_data[42],
                "Attribute_2_value(s)" => $read_data[43],
                "attribute_2_visible" => $read_data[44],
                "attribute_2_global" => $read_data[45],
                "meta" => $read_data[46],
                "wpcom_is_markdown" => $read_data[47],
                "download_1_name" => $read_data[48],
                "download_1_URL" => $read_data[49],
                "download_2_name" => $read_data[50],
                "Download_2_URL" => $read_data[51],
                //"is_featured" => $read_data[5],
            ]; 
             
        }
        
                //skip first row
        $removed = array_shift($products_data);
    
    
                       
                        foreach($products_data as $product_data){
                               
                            // if($my_products_id != $all_product_id){
                                    $product = array(
                                        'post_author' => get_current_user_id( ),
                                        //'ID' => $product_data["product_id"],
                                        'post_content' => $product_data["description"],
                                        'post_status' => "publish",
                                        'post_title' => $product_data["product_name"],
                                        'post_parent' => '',
                                        'post_type' => "product",
                                    ); 
                                    //Create products
                                    $post_id = wp_insert_post( $product, $wp_error );
                                    wp_set_object_terms( $post_id, $product_data["product_type"], 'product_type' );
                                    wp_set_object_terms($post_id, $product_data["categories"], 'product_cat', true);
                                    wp_set_object_terms($post_id, $product_data["tags"], 'product_tag', true);
                                    update_post_meta( $variation_id, '_thumbnail_id',  $product_data["images"] );
                                    set_post_thumbnail( $post_id, $product_data["images"] );
                                    update_post_meta( $post_id, '_visibility', $product_data["visibility_in_catalog"] );
                                    update_post_meta( $post_id, '_stock_status', $product_data["in_stock"]);
                                    //update_post_meta($post_id, '_product_image_gallery',implode(',', array_keys($product_data["images"])) );
                                    //update_post_meta( $my_products_id, 'total_sales', $product_data["product_type"] );
                                    //update_post_meta( $my_products_id, '_downloadable', $product_data["product_type"] );
                                    //update_post_meta( $my_products_id, '_virtual', $product_data["product_type"] );
                                    update_post_meta( $post_id, '_regular_price', $product_data["regular_price"] );
                                    update_post_meta( $post_id, '_sale_price', $product_data["sale_price"] );
                                    update_post_meta( $post_id, '_purchase_note', $product_data["purchase_note"] );
                                    update_post_meta( $post_id, '_featured', $product_data["is_featured"] );
                                    update_post_meta( $post_id, '_weight',$product_data["weight_lbs"] );
                                    update_post_meta( $post_id, '_length', $product_data["length_in"] );
                                    update_post_meta( $post_id, '_width', $product_data["width_in"] );
                                    update_post_meta( $post_id, '_height', $product_data["height_in"] );
                                    update_post_meta( $post_id, '_sku',$product_data["product_sku"] );
                                    update_post_meta( $post_id, '_sale_price_dates_from', $product_data["date_sale_price_starts"] );
                                    update_post_meta( $post_id, '_sale_price_dates_to', $product_data["date_sale_price_ends"] );
                                    update_post_meta( $post_id, '_price', $product_data["regular_price"] );
                                    update_post_meta( $post_id, '_sold_individually', $product_data["sold_individually"] );
                                    update_post_meta( $post_id, '_manage_stock', $product_data["stock"] );
                                    update_post_meta( $post_id, '_backorders', $product_data["backorders_allowed"] );
                                    update_post_meta( $post_id, '_stock', $product_data["stock"]);
                                    update_post_meta( $post_id, '_tax_status', $product_data["tax_status"]);
                                    update_post_meta( $post_id, '_tax_class', $product_data["tax_class"]);
                                    update_post_meta( $post_id, '_product_url', $product_data["external_URL"]);
                                    update_post_meta( $post_id, '_button_text', $product_data["button_text"]);
                                    update_post_meta( $post_id, 'upsell_ids', $product_data["upsells"]);
                                    update_post_meta( $post_id, 'crosssell_ids', $product_data["cross_sells"]);
                                    update_post_meta( $post_id, '_download_limit', $product_data["download_limit"]);
                                    update_post_meta( $post_id, '_download_expiry', $product_data["download_expiry_days"]);
                                
                    
                                    // Add Featured Image to Product
                                    $image_url        = $product_data["images"]; // Define the image URL here
                                    $image_name       = basename($image_url);
                                    $upload_dir       = wp_upload_dir(); // Set upload folder
                                    $image_data       = file_get_contents($image_url); // Get image data
                                    $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
                                    $filename         = basename( $unique_file_name ); // Create image file name
    
                                    // Check folder permission and define file location
                                    if( wp_mkdir_p( $upload_dir['path'] ) ) {
                                    $file = $upload_dir['path'] . '/' . $filename;
                                    } else {
                                    $file = $upload_dir['basedir'] . '/' . $filename;
                                    }
    
                                    // Create the image  file on the server
                                    file_put_contents( $file, $image_data );
    
                                    // Check image file type
                                    $wp_filetype = wp_check_filetype( $filename, null );
    
                                    // Set attachment data
                                    $attachment = array(
                                        'post_mime_type' => $wp_filetype['type'],
                                        'post_title'     => sanitize_file_name( $filename ),
                                        'post_content'   => '',
                                        'post_status'    => 'inherit'
                                    );
    
                                    // Create the attachment
                                    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
    
                                    // Include image.php
                                    require_once(ABSPATH . 'wp-admin/includes/image.php');
    
                                    // Define attachment metadata
                                    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    
                                    // Assign metadata to attachment
                                    wp_update_attachment_metadata( $attach_id, $attach_data );
    
                                    // And finally assign featured image to post
                                    set_post_thumbnail( $post_id, $attach_id );
                                // }elseif( $my_products_id == $all_product_id ){
    
                                //     $product = array(
                                //         'post_author' => get_current_user_id( ),
                                //         'ID' => $product_data["product_id"],
                                //         'post_content' => $product_data["description"],
                                //         'post_status' => "publish",
                                //         'post_title' => $product_data["product_name"],
                                //         'post_parent' => '',
                                //         'post_type' => "product",
                                //     ); 
                                //     //Create products
                                //     $post_id = wp_update_post( $product, $wp_error );
            
                                // } 
                        }
                    //    }
            
       
    
                    
                    
               
                
            
        fclose($csv_file);
    
            }
    
}



    
    


    
    



}







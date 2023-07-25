<?php
/*
Plugin Name: Reqser.com
Plugin URI: https://reqser.com
Description: Reqser Connection Plugin
Version: 1.0.0
Author: Joris Klostermann
Author URI: https://reqser.com
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: reqser
*/


function my_plugin_enqueue_admin_scripts() {
    wp_enqueue_script( 'my-plugin-admin-script', plugin_dir_url( __FILE__ ) . 'admin.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_style( 'my-plugin-admin-style', plugin_dir_url( __FILE__ ) . 'admin.css', array(), '1.0.0' );
      // Add inline CSS styles
   $custom_css = '
   .custom-image-logo {
       max-width: 100px; /* Adjust the maximum width as per your requirements */
       height: auto; /* Maintain the aspect ratio */
   }
   .custom-image-icon {
    max-width: 100px; /* Adjust the maximum width as per your requirements */
    height: 20px; /* Maintain the aspect ratio */
}
   .custom-image-text {
    max-width: 150px; /* Adjust the maximum width as per your requirements */
    height: auto; /* Maintain the aspect ratio */
    }

   .image-container {
       display: flex; /* Display images in a row */
       align-items: center; /* Align images vertically */
       gap: 10px; /* Add spacing between images */
   }

   .full-width-input {
        width: 100%;
        box-sizing: border-box;
    }

    .review-entry-row {
        display: flex;
        justify-content: space-between;
    }
    
    .review-entry-box {
        width: 32%; /* You can adjust as per your need */
        border: 1px solid #ccc;
        padding: 5px;
        box-sizing: border-box;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .review-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    a.custom-link {
        color: inherit;      /* Make the link color the same as surrounding text */
        text-decoration: none;   /* Removes the underline */
    }
   ';

   wp_add_inline_style( 'my-plugin-admin-style', $custom_css );
}
add_action( 'admin_enqueue_scripts', 'my_plugin_enqueue_admin_scripts' );
function my_plugin_custom_admin_box() {
    // Define the box content
    $box_content = '<div class="image-container">';
    $box_content .= '<img src="https://www.reqser.com/images/icon.svg" alt="Custom Image" class="custom-image-logo">';
    $box_content .= '<img src="https://www.reqser.com/images/logo-w-claim.svg" alt="Custom Image" class="custom-image-text">';
    $box_content .= '</div>';
    // Output the box
    echo '<div class="my-plugin-admin-box inside">';
    echo $box_content;
    echo '</div>';

    $api_key = get_option('reqser_api_key');
    // Add the API key input field
    $add_api_key = '<div class="inside">
        <form name="post" action="" method="post" id="save_api_key" class="initial-form hide-if-no-js">
            <div class="input-text-wrap" id="title-wrap">
                <label for="title">API Key von <a href="https://www.reqser.com/settings/api" target="_blank">Reqser.com/settings/api</a> eintragen</label>
                <input type="text" value="'.$api_key.'" name="reqser_api_key" id="title" autocomplete="off" style="margin-top: 10px;">
            </div>
            <p class="textarea-wrap submit" style="margin-top: 10px;">
                <input type="hidden" name="action" value="save_api_key">
                <input type="submit" name="save" id="save-post" class="button button-primary" value="Speichern">
                <br class="clear">
            </p>
        </form>
    </div>';
    if ($api_key){
        $access_token = api_get_acces_token($api_key);
        if (!$access_token){
            $error_message = 'API Connection Failed! Please verify your API Key.';
            //set_transient('my_plugin_error_message', $error_message, 6000); // Set the error message transient for 60 seconds
            echo '<div style="background-color: #ffcccc; color: #ff0000; padding: 10px; margin-bottom: 10px;">' . $error_message . '</div>';
            echo $add_api_key;
        } else {
            $success_message = 'API Connection Success';
            echo '<div id="success_message" style="background-color: #c3e6cb; color: #155724; padding: 10px; margin-bottom: 10px;">' . $success_message . '</div>';
            connection_success($access_token);
        }
    } else {
        echo $add_api_key;
    }  
}

function connection_success($access_token){
    $user_id = get_current_user_id();
    $fields['user_id'] = $user_id;
    $fields['cms'] = 'wordpress';
    $fields['cms_version'] = get_bloginfo('version');
    $fields['php_version'] = phpversion();

    $request = curl_init();
    $url = 'https://reqser.com/api/module_request';
    curl_setopt($request, CURLOPT_URL, $url);
    $authorization = "Authorization: Bearer ".$access_token;
    curl_setopt($request, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
    curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($request, CURLOPT_TIMEOUT, 5);
    //curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($fields));
    curl_setopt($request, CURLOPT_SSL_VERIFYPEER, 0);
    $result_request = curl_exec($request);
    curl_close($request);
    $result_request = json_decode($result_request, true);
    //echo nl2br(print_r($result_request, true));
    if ($result_request && sizeof($result_request) > 0){
       // echo $result_request;
        //echo nl2br(print_r($result_request, true));
        /*if (in_array('google_review', $result_request['activated_products'])){
            echo '<div style="margin-bottom: 20px"><h3><b>'.$text['title'].'</b>';
        }*/
        if ($result_request['products'] && sizeof($result_request['products']) > 0){
            foreach($result_request['products'] as $key => $value){
                if(isset($value['link']) && !empty($value['link'])) {
                    echo '<a class="custom-link" href="'.$value['link'].'">';
                }
                echo '<div style="margin-bottom: 10px; display: flex; align-items: center;"><img src="https://www.reqser.com/storage/images/google_review.svg" alt="Icon" class="custom-image-icon" style="margin-right: 10px;"><h3 style="margin: 0; line-height: 1;"><b>'.$value['title'].'</b></h3></div>';
                if ($key == 'google_review'){
                    if (isset($value['reviews']) && sizeof($value['reviews']) > 0){
                        foreach($value['reviews'] as $review_name => $review){
                            echo '<div style="margin-bottom: 10px; display: flex; align-items: center;"><h3 style="margin: 0; line-height: 1;"><b>'.$review_name.'</b> '.$review['rating'].'</h3></div>';
                            if (isset($review['latest_entries']) && sizeof($review['latest_entries']) > 0) {
                                echo '<div class="review-entry-row">';
                                foreach($review['latest_entries'] as $latest_entries){
                                    echo '<div class="review-entry-box">';
                                        echo '<div class="review-entry">';
                                        $rating = $latest_entries['review_rating'];
                                        for ($i = 0; $i < $rating; $i++) {
                                            echo '<span class="fas fa-star"></span>';
                                        }
                                        for ($i = $rating; $i < 5; $i++) {
                                            echo '<span class="far fa-star"></span>';
                                        }
                                        echo date("d.m.Y", strtotime($latest_entries['created_at']));
                                        echo '</div>';
                                        
                                        echo '<div class="review-entry"><b>' . $latest_entries['name'] . '</b></div>';
                                        echo '<div class="review-entry review-text">' . $latest_entries['text'] . '</div>';
                                        
                                    echo '</div>';
                                }
                                echo '</div>';
                            }
                        }
                    }
                }
                if(isset($value['link']) && !empty($value['link'])) {
                    echo '</a>';
                }

            }
        }
        
        /*foreach($result_request as $key => $value){
            if ($key == 'login_link'){
                echo '<div><a href="'.$value.'&user='.$user_id.'" target="_blank" class="button button-primary" style="color: white;">Direkt zu Reqser.com</a></div>';
            } elseif ($key == 'products'){
                foreach($value as $products_name => $product_value){
                    echo '<div class="inside">';
                    echo '<h2>'.strtoupper($products_name).'</h2>';
                    if (sizeof($product_value['content']) == 0){
                        $error_message = 'No Entries aviable';
                        echo '<div style="background-color: #c3e6cb; color: #155724; padding: 10px; margin-bottom: 10px;">' . $error_message . '</div>';
                    } else {
                        $max_count = false;
                        if (isset($product_value['amount'])){
                            $max_count = (int)$product_value['amount'];
                            $i = 0;
                        }
                        foreach($product_value['content'] as $user_name => $content){
                            if (is_array($content) && sizeof($content) > 0){
                                foreach($content as $text_type => $text){
                                    if (isset($text['link'])){
                                        echo '<a href="'.$text['link'].'&user='.$user_id.'" style="text-decoration: none; color: black">';
                                    }
                                    echo '<div style="margin-bottom: 20px"><h3><b>'.$text['title'].'</b>';
                                    if (isset($user_name)) echo ' -> '.$user_name;
                                    echo '</h3>';
                                    if (isset($text['body'])) echo $text['body'];
                                    echo '</div>';  
                                    if (isset($text['link'])){
                                        echo '</a>';
                                    }
                       
                                    if ($max_count != false){
                                        $i ++;
                                        if ($max_count <= $i){
                                            echo '<div style="margin-bottom: 20px">';
                                            echo 'Max Show Amount = '.$max_count;
                                            echo '</div>'; 
                                            break;
                                        } 
                                    }                          
                                }   
                            } else {
                                $success_message = 'No Entrys found';
                                if ($user_name == 'link'){
                                    echo '<a href="'.$content.'&user='.$user_id.'" style="text-decoration: none; color: black">';
                                }
                                echo '<div style="background-color: #c3e6cb; color: #155724; padding: 10px; margin-bottom: 10px;">' . $success_message . '</div>';
                                if ($user_name == 'link'){
                                    echo '</a>';
                                }
                            }
                    
                           
                          
                        }
                    }
                    echo '</div>';
                }
            } else {
                //echo print_r($value);
            }
        }*/
    }

}

function api_get_acces_token($api_key){
    $url = 'https://reqser.com/api/token?key='.$api_key;  //Gewünschter API Key einsetzen
    $login = curl_init();
    curl_setopt($login, CURLOPT_URL, $url);
    curl_setopt($login, CURLOPT_POST, 1);
    curl_setopt($login, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($login, CURLOPT_TIMEOUT, 5);
    curl_setopt($login, CURLOPT_SSL_VERIFYPEER, 1);
    $result_login = curl_exec($login);
    $result_login = json_decode($result_login, true);
    curl_close($login);
    if ($result_login && $result_login["access_token"] != ''){
        return $result_login["access_token"];
    } else {
        return false;
    }
}

function my_plugin_save_api_key() {
    if (isset($_POST['action']) && $_POST['action'] === 'save_api_key') {
        if (isset($_POST['reqser_api_key'])) {
            $api_key = sanitize_text_field($_POST['reqser_api_key']);
            update_option('reqser_api_key', $api_key);
        }
    }
}
add_action('init', 'my_plugin_save_api_key');

function my_plugin_add_custom_admin_box() {
    // Add the custom box to the admin dashboard
    add_meta_box( 'my-plugin-custom-box', 'Reqser.com', 'my_plugin_custom_admin_box', 'dashboard', 'normal', 'high' );
}
add_action( 'wp_dashboard_setup', 'my_plugin_add_custom_admin_box' );

function load_font_awesome() {
    wp_enqueue_style( 'font-awesome', 'https://use.fontawesome.com/releases/v5.15.3/css/all.css', array(), '5.15.3' );
}
add_action( 'admin_enqueue_scripts', 'load_font_awesome' );

?>

<script>
    // Use setTimeout function to execute the code after 10 seconds
    setTimeout(function() {
        // Get the element by id
        var msg = document.getElementById('success_message');
        // Set the display property to none to hide the message
        if(msg) {
            msg.style.display = 'none';
        }
    }, 5000); // Time is in milliseconds (10000 ms = 10 s)
</script>
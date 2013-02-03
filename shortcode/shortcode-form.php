<?php                         
/**
* The Form for the MCE Shortcode Generator
* @uses called with a template redirect using a query var send from the mce plugin
* @see simple_links->load_outside_page();
* @see js/editor_plugin.js
* @since 8/27/12
* @author Mat Lipe <mat@lipeimagination.info>
*/

wp_head();

?> 
<title>Add Simple Links</title><?php 
 ?>

<!-- This script must be attached to move content back and forth -->
<!-- This page should be given a proper html header -->
<script type="text/javascript" src="/wp-includes/js/tinymce/tiny_mce_popup.js?v=3211"></script>
<?php 

//Bring in the JQuery
echo '<script type="text/javascript">';
	include( SIMPLE_LINKS_JS_PATH . 'shortcode-form.js' );
echo '</script>';


//The plugins functions
global $simple_links_func;

?>

<style type="text/css">
			#generate{
background-attachment: scroll;
background-clip: border-box;
background-color: #21759B;
background-image: url(/wp-admin/images/button-grad.png);
background-origin: padding-box;
background-size: auto;
border-bottom-color: #13455B;
border-bottom-left-radius: 11px;
border-bottom-right-radius: 11px;
border-bottom-style: solid;
border-bottom-width: 1px;
border-left-color: #13455B;
border-left-style: solid;
border-left-width: 1px;
border-right-color: #13455B;
border-right-style: solid;
border-right-width: 1px;
border-top-color: #13455B;
border-top-left-radius: 11px;
border-top-right-radius: 11px;
border-top-style: solid;
border-top-width: 1px;
box-sizing: content-box;
color: #EAF2FA;
cursor: pointer;
display: inline-block;
float: none;
font-family: sans-serif;
font-size: 12px;
font-weight: bold;
height: 13px;
letter-spacing: normal;
line-height: 13px;
margin-bottom: 1px;
margin-left: 1px;
margin-right: 1px;
margin-top: 1px;
min-width: 80px;
outline-color: #EAF2FA;
outline-style: none;
outline-width: 0px;
padding-bottom: 3px;
padding-left: 8px;
padding-right: 8px;
padding-top: 3px;
text-align: center;
text-decoration: none;
text-shadow: rgba(0, 0, 0, 0.296875) 0px -1px 0px;
width: 80px;

   }
   
body,input{
	font-size: 14px;
	padding: 5px;
	border-radius: 5px;
}
</style>
</head>

<body>

      <h3>This Will Generate the Shortcode to Display Simple Links</h3>
        <p><em>If no links match the options chosen, this will not display anything.</em></p>

        <p>Title (optional): <input type="text" id="title"  size="50"/></p>
        
        <fieldset>
        <p>Categories (optional): <br><br>
        	<?php 
       		 foreach( $simple_links_func->get_categories() as $cat ){
        		printf('<input class="cat" type="checkbox" value="%s"/ > %s <br>', $cat, $cat );
	        	}
	        ?>
        </p>
        </fieldset>
        
        
        <p>Number of Links: 
        	<select id="count">
        		<option value="">All</option>
        		<?php 
          		for( $i = 1; $i<30; $i++){
          			printf('<option value="%s">%s</option>', $i, $i );
          		}
        		?>
        	</select>
        </p>
        
        <p>Order By (optional): 
        	<select id="orderby">
        		<option value="">Link Order</option>
        	   	<option value="name">Name</option>
        	   	<option value="random">Random</option>
        	</select>
        </p>
        
        <p>Show Description <input type="checkbox" id="description" value="true" /></p>
        
        
        <p>Show Image <input type="checkbox" id="show_image" value="true" /></p>
        <p>
          Image Size  <select id="image-size">
          <?php 
            foreach( $simple_links_func->image_sizes() as $size ){
                printf('<option value="%s">%s</a>', $size, $size );
            }
            ?>
          </select>
        </p>
        
        <p>Include Additional Fields:<br>
            <?php 
            if( empty( $simple_links_func->additional_fields ) ){
            	echo '<em>There have been no additional fields added. </em>';
            } else {
            foreach( $simple_links_func->additional_fields as $field ){
            			printf( '<input class="additional" type="checkbox" value="%s">%s<br>', $field, $field );
            	  }
            }
        ?>
        </p>
        
        <p>Field Separator:<br> 
        <em><small><small>HTML is Allowed and Will show up Formatted in the Editor:</small><small></em><br>
        <input type="text" value="-" id="separator" size="50"/></p>
        
      <input type="button" id="generate" class="button-primary" value="Generate">


</body>




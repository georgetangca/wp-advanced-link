<?php 
	
$simple_links_classes = array( 
                'simple-links-post-type-tax',
                'simple-links.class' 
                    );

foreach( $simple_links_classes as $class ){
	require( $class . '.php' );
	
}

$simple_links_func = new simple_links();

if( is_admin() ){
	require( 'simple-links.admin.class.php' );
	$simple_links_admin_func = new simple_links_admin();
}
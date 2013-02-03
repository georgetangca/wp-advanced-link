/**
 * The js for the Settings page on the Simple Links
 * @author mat lipe <mat@lipeimagination.info>
 * @since 8/18/12
 */

jQuery(document).ready( function($) {
			// close postboxes that should be closed
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
		
			// postboxes setup  These need to be set the handlers (screens) of the post boxes
			postboxes.add_postbox_toggles('sl-settings-boxes');
			
			

			SLsettingsAjax.init();
			SLsettingsQtips.init();
			
			
			
});

/**
 * Any Ajax Requests for the Settings page
 * 
 * @since 8/19/12
 */
var SLsettingsAjax = {
		init : function(){
			//the import links ajax
			$('#sl-import-links').click( function(){
				SLsettingsAjax.importLinks();
				return false;
			});
			
		},
		
		/**
		 * Make the request to import the links
		 * @since 8/19/12
		 */
		importLinks :function(){
			
			var data = '';
			
			$.post(SLajaxURL.importLinksURL, data, function(respon){
				
				   //  alert( respon );
				    $('#import-links-success').slideDown('slow');
				});
				
		}
	
		
}



/**
 * The Toopltips
 * @since 8/19/12
 */
var SLsettingsQtips = {
		
		init : function(){
			
			//The Hide Ordering Option
			$('#SL-hide-ordering').qtip({
				'content'  : 'This will prevent editors from using the drag and drop ordering.',
				style: { 
					border: {
				         width: 1,
				         radius: 8,
				         color: '#21759B'
				      },
				      tip: 'topLeft' // Notice the corner value is identical to the previously mentioned positioning corners
				   }
			});
			
			//The show setting option
			$('#SL-show-settings').qtip({
				'content'  : 'This will allow editors access to this Settings Page.',
				style: { 
					border: {
				         width: 1,
				         radius: 8,
				         color: '#21759B'
				      },
				      tip: 'topLeft' // Notice the corner value is identical to the previously mentioned positioning corners
				   }
			});
			
			//The replace widgets option
			$('#SL-replace-widgets').qtip({
				'content'  : 'This will replace all the "Links" widgets with "Simple Links Replica" widgets to keep existing "Links" widgets in place and prevent deprecation.',
				style: { 
					border: {
				         width: 1,
				         radius: 8,
				         color: '#21759B'
				      },
				      tip: 'topLeft' // Notice the corner value is identical to the previously mentioned positioning corners
				   }
			});
			
			//The remove links option
			$('#SL-remove-links').qtip({
				'content'  : 'This will remove all traces of the Wordpres built in links except data and widgets',
				style: { 
					border: {
				         width: 1,
				         radius: 8,
				         color: '#21759B'
				      },
				      tip: 'topLeft' // Notice the corner value is identical to the previously mentioned positioning corners
				   }
			});
			
			
			//The import links
			$('#SL-import-links').qtip({
				content : 'This will import all existing Wordpress Links into the Simple Links',
				style: { 
					border: {
				         width: 1,
				         radius: 8,
				         color: '#21759B'
				      },
				      tip: 'topLeft' // Notice the corner value is identical to the previously mentioned positioning corners
				   }
			})
		}
		
		
}




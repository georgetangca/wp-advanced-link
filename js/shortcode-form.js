

                               /**
                                * The jquery required for the shortcode MCE Form
                                * @since 8/20/12
                                * @author Mat Lipe <mat@lipeimagination.info>
                                */
var output = '[simple-links';

var myObj = {
    local_ed : 'ed',  //A Var for setting a global object to use
	
	//The function with sends the new output back to the editor and closes the popup
	insert: function(){
		
		tinyMCEPopup.execCommand('mceReplaceContent', false, output);
		
		// Return
		tinyMCEPopup.close();
		
	}
	
}





//Initiate the object This is required
tinyMCEPopup.onInit.add(myObj.init, myObj);

//The Jquery which grabs the form data
jQuery(document).ready(function ($) {

	     var fields = ['count','orderby','title'];
          //Generate the Code
          $('#generate').click( function(){

              //Go through the standard fields
              for( var i = 0; i < fields.length; i++ ){
            	  //Add the standard fields to the output if they have a value
                  if( $('#'+fields[i]).val() != '' ){
                      output += ' ' + fields[i] + '="' + $('#'+fields[i]).val() + '"';
                  }
              }


              //Add the checked categories
              var cats = '';
              $('.cat:checked').each( function(){
                  if( cats == '' ){
                      cats = ' category="';
                      cats += $(this).val();
                  } else {
                  	  cats += ',' + $(this).val();
                  }
              });

              //Close the attribute and add it ot the shortcode
              if( cats != '' ){
                  cats += '"';
                  output += cats;
              }
              
              
              
              //Add the additional fields
              var addFields = '';
              $('.additional:checked').each( function(){
            	  if( addFields == ''){
            		  addFields = ' fields="';
            		  addFields += $(this).val();
            	  } else {
            		  addFields += ',' + $(this).val();
            	  }
              });
              //Close the fields
              if( addFields != ''){
            	  addFields += '"';
            	  output += addFields;
              }
              
              
              //Add the separator
              if( $('#separator').val() != '-' ){
            	  output += ' separator="' + $('#separator').val() + '"';
              }
              


              //Add the image to the shortcode
              if( $('#show_image').is(':checked') ){
                   output += ' show_image="true"';
                   if( $('#image-size').val() != '' ){
                       output += ' image_size="' + $('#image-size').val() + '"';
                   }
              }
              
              //Add the description to the shortcode
              if( $('#description').is(':checked') ){
            	  output += ' description="true"';
              }
              
              

              output += ']'; //Finish out the shortcode
              
             //Send the shortcode back to the editor
        	  myObj.insert();
          });
 
});

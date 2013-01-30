jQuery(document).ready(function() {
function set_uploader_logo() {
button = '#startup_options_logo_button';
if(jQuery(button)) {
jQuery(button).click(function() {
tb_show('', 'media-upload.php?post_id=0&type=image&amp;TB_iframe=true');
set_send_logo();
return false;
});
}
}
function set_send_logo() {
window.original_send_to_editor = window.send_to_editor;
window.send_to_editor = function(html) {
if ( jQuery(html).is("a") ) {
var imgurl = jQuery('img', html).attr('src');
} else if ( jQuery(html).is("img") ) {
var imgurl = jQuery(html).attr('src');
}
jQuery('.logo-upload-url').val(imgurl);
tb_remove();
window.send_to_editor = window.original_send_to_editor;
};
}
set_uploader_logo();
});
function clearForm(oForm) {
var elements = oForm.elements;
oForm.reset();
for(i=0; i<elements.length; i++) {
field_type = elements[i].type.toLowerCase();
switch(field_type) {
case "text":
case "textarea":
elements[i].value = "";
break;
case "radio":
case "checkbox":
if (elements[i].checked) {
elements[i].checked = false;
}
break;
case "select-one":
case "select-multi":
elements[i].selectedIndex = -1;
break;
default:
break;
}
}
}
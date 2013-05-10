var text_target = "";

jQuery(document).ready(function() {

jQuery('.upload-image-button, .upload-file').click(function() {
 text_target = jQuery(this).closest(".upload-image").find("input[type=text]");
 

formfield = text_target.attr('name');
 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
 return false;
});

window.send_to_editor = function(html) {
 imgurl = jQuery('img',html).attr('src');
    text_target.val(imgurl);
 tb_remove();
}

});


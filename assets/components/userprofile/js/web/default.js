userprofile = {
    initialize: function() {

        if(!jQuery().ajaxForm) {
            document.write('<script src="'+userprofileConfig.jsUrl+'web/lib/jquery.form.min.js"><\/script>');
        }
        if(!jQuery().jGrowl) {
            document.write('<script src="'+userprofileConfig.jsUrl+'web/lib/jquery.jgrowl.min.js"><\/script>');
        }

        $(document).ready(function() {
            $.jGrowl.defaults.closerTemplate = '<div>[ '+userprofileConfig.close_all_message+' ]</div>';
        });
    }

};

userprofile.Message = {
    success: function(message, sticky) {
        if (sticky == null) {sticky = false;}
        if (message) {
            $.jGrowl(message, {theme: 'userprofile-message-success', sticky: sticky});
        }
    }
    ,error: function(message, sticky) {
        if (sticky == null) {sticky = true;}
        if (message) {
            $.jGrowl(message, {theme: 'userprofile-message-error', sticky: sticky});
        }
    }
    ,info: function(message, sticky) {
        if (sticky == null) {sticky = false;}
        if (message) {
            $.jGrowl(message, {theme: 'userprofile-message-info', sticky: sticky});
        }
    }
    ,close: function() {
        $.jGrowl('close');
    }
};

userprofile.initialize();
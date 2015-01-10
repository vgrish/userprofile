Ext.namespace('up.combo');

up.combo.Browser = function(config) {
    config = config || {};

    if (config.length != 0 && typeof config.openTo !== "undefined") {
        if (!/^\//.test(config.openTo)) {
            config.openTo = '/' + config.openTo;
        }
        if (!/$\//.test(config.openTo)) {
            var tmp = config.openTo.split('/')
            delete tmp[tmp.length - 1];
            tmp = tmp.join('/');
            config.openTo = tmp.substr(1)
        }
    }

    Ext.applyIf(config,{
        width: 300
        ,triggerAction: 'all'
    });
    up.combo.Browser.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(up.combo.Browser,Ext.form.TriggerField,{
    browser: null

    ,onTriggerClick : function(btn){
        if (this.disabled){
            return false;
        }

        if (this.browser === null) {
            this.browser = MODx.load({
                xtype: 'modx-browser'
                ,id: Ext.id()
                ,multiple: true
                ,source: this.config.source || MODx.config.default_media_source
                ,rootVisible: this.config.rootVisible || false
                ,allowedFileTypes: this.config.allowedFileTypes || ''
                ,wctx: this.config.wctx || 'web'
                ,openTo: this.config.openTo || ''
                ,rootId: this.config.rootId || '/'
                ,hideSourceCombo: this.config.hideSourceCombo || false
                ,hideFiles: this.config.hideFiles || true
                ,listeners: {
                    'select': {fn: function(data) {
                        this.setValue(data.fullRelativeUrl);
                        this.fireEvent('select',data);
                        Ext.get('up-avatar').update('<img src="'+MODx.config.connectors_url+'system/phpthumb.php?h=230&w=294&src='+data.fullRelativeUrl+'&wctx=MODx.ctx&source=1" class="up-avatar" alt="" />');
                    },scope:this}
                }
            });
        }
        this.browser.win.buttons[0].on('disable',function(e) {this.enable()})
        this.browser.win.tree.on('click', function(n,e) {
                path = this.getPath(n);
                this.setValue(path);
            },this
        );
        this.browser.win.tree.on('dblclick', function(n,e) {
                path = this.getPath(n);
                this.setValue(path);
                this.browser.hide()
            },this
        );
        this.browser.show(btn);
        return true;
    }
    ,onDestroy: function(){
        up.combo.Browser.superclass.onDestroy.call(this);
    }
    ,getPath: function(n) {
        if (n.id == '/') {return '';}
        data = n.attributes;
        path = data.path + '/';

        return path;
    }
});
Ext.reg('up-combo-browser',up.combo.Browser);


up.combo.Autocomplete = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        name: config.name
        ,fieldLabel: _('up_tags_' + config.name)
        ,id: 'up-tags' + config.name
        ,hiddenName: config.name
        ,displayField: config.name
        ,valueField: config.name
        ,anchor: '99%'
        ,fields: [config.name]
        //,pageSize: 20
        ,forceSelection: false
        ,url: userprofile.config.connectorUrl
        ,typeAhead: true
        ,editable: true
        ,allowBlank: true
        ,baseParams: {
            action: 'mgr/tags/autocomplete'
            ,name: config.name
            ,combo:1
            ,limit: 0
        }
        ,hideTrigger: true
    });
    up.combo.Autocomplete.superclass.constructor.call(this,config);
};
Ext.extend(up.combo.Autocomplete,MODx.combo.ComboBox);
Ext.reg('up-combo-autocomplete',up.combo.Autocomplete);
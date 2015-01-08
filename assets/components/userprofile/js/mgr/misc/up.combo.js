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




/**
 * Renders an input for an image TV
 *
 * @class MODx.panel.ImageTV
 * @extends MODx.Panel
 * @param {Object} config An object of configuration properties
 * @xtype panel-tv-image
 */
MODx.panel.ImageTV = function(config) {
    config = config || {};
    config.filemanager_url = MODx.config.filemanager_url;
    Ext.applyIf(config,{
        layout: 'form'
        ,autoHeight: true
        ,border: false
        ,hideLabels: true
        ,defaults: {
            autoHeight: true
            ,border: false
        }
        ,items: [{
            xtype: 'hidden'
            ,name: 'tv'+config.tv
            ,id: 'tv'+config.tv
            ,value: config.value
        },{
            xtype: 'modx-combo-browser'
            ,browserEl: 'tvbrowser'+config.tv
            ,name: 'tvbrowser'+config.tv
            ,id: 'tvbrowser'+config.tv
            ,triggerClass: 'x-form-image-trigger'
            ,value: config.relativeValue
            ,hideFiles: true
            ,source: config.source || 1
            ,allowedFileTypes: config.allowedFileTypes || ''
            ,openTo: config.openTo || ''
            ,hideSourceCombo: true
            ,listeners: {
                'select': {fn:function(data) {
                    Ext.getCmp('tv'+this.config.tv).setValue(data.relativeUrl);
                    Ext.getCmp('tvbrowser'+this.config.tv).setValue(data.relativeUrl);
                    this.fireEvent('select',data);
                },scope:this}
                ,'change': {fn:function(cb,nv) {
                    Ext.getCmp('tv'+this.config.tv).setValue(nv);
                    this.fireEvent('select',{
                        relativeUrl: nv
                        ,url: nv
                    });
                },scope:this}
            }
        }]
    });
    MODx.panel.ImageTV.superclass.constructor.call(this,config);
    this.addEvents({select: true});
};
Ext.extend(MODx.panel.ImageTV,MODx.Panel);
Ext.reg('modx-panel-tv-image',MODx.panel.ImageTV);

MODx.panel.FileTV = function(config) {
    config = config || {};
    config.filemanager_url = MODx.config.filemanager_url;
    Ext.applyIf(config,{
        layout: 'form'
        ,autoHeight: true
        ,border: false
        ,hideLabels: true
        ,defaults: {
            autoHeight: true
            ,border: false
        }
        ,items: [{
            xtype: 'hidden'
            ,name: 'tv'+config.tv
            ,id: 'tv'+config.tv
            ,value: config.value
        },{
            xtype: 'modx-combo-browser'
            ,browserEl: 'tvbrowser'+config.tv
            ,name: 'tvbrowser'+config.tv
            ,id: 'tvbrowser'+config.tv
            ,value: config.relativeValue
            ,hideFiles: true
            ,source: config.source || 1
            ,allowedFileTypes: config.allowedFileTypes || ''
            ,wctx: config.wctx || 'web'
            ,openTo: config.openTo || ''
            ,hideSourceCombo: true
            ,listeners: {
                'select': {fn:function(data) {
                    Ext.getCmp('tv'+this.config.tv).setValue(data.relativeUrl);
                    Ext.getCmp('tvbrowser'+this.config.tv).setValue(data.relativeUrl);
                    this.fireEvent('select',data);
                },scope:this}
                ,'change': {fn:function(cb,nv) {
                    Ext.getCmp('tv'+this.config.tv).setValue(nv);
                    this.fireEvent('select',{
                        relativeUrl: nv
                        ,url: nv
                    });
                },scope:this}
            }
        }]
    });
    MODx.panel.FileTV.superclass.constructor.call(this,config);
    this.addEvents({select: true});
};
Ext.extend(MODx.panel.FileTV,MODx.Panel);
Ext.reg('modx-panel-tv-file',MODx.panel.FileTV);

MODx.checkTV = function(id) {
    var cb = Ext.get('tv'+id);
    Ext.get('tvh'+id).dom.value = cb.dom.checked ? cb.dom.value : '';
};
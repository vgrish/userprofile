userprofile.page.Settings = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'userprofile-panel-settings'
            ,renderTo: 'userprofile-panel-settings-div'
        }]
    });
    userprofile.page.Settings.superclass.constructor.call(this,config);
};
Ext.extend(userprofile.page.Settings,MODx.Component);
Ext.reg('userprofile-page-settings',userprofile.page.Settings);

userprofile.panel.Settings = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,deferredRender: true
        ,baseCls: 'modx-formpanel'
        ,items: [{
            html: '<h2>'+_('userprofile') + ' :: ' + _('up_settings')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header container'
        },{
            xtype: 'modx-tabs'
            ,bodyStyle: 'padding: 5px'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,hideMode: 'offsets'
            ,stateful: true
            ,stateId: 'userprofile-settings-tabpanel'
            ,stateEvents: ['tabchange']
            ,getState:function() {return { activeTab:this.items.indexOf(this.getActiveTab())};}
            ,items: [{
                title: _('up_extended')
                ,deferredRender: true
                ,items: [{
                    html: '<p>'+_('up_extended_intro')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                    ,bodyStyle: 'margin-bottom: 10px'
                },{
                    xtype: 'userprofile-grid-extended'
                }]
            }]
        }]
    });
    userprofile.panel.Settings.superclass.constructor.call(this,config);
};
Ext.extend(userprofile.panel.Settings,MODx.Panel);
Ext.reg('userprofile-panel-settings',userprofile.panel.Settings);
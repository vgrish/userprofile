up.page.Settings = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'up-panel-settings'
            ,renderTo: 'up-panel-settings-div'
        }]
    });
    up.page.Settings.superclass.constructor.call(this,config);
};
Ext.extend(up.page.Settings,MODx.Component);
Ext.reg('up-page-settings',up.page.Settings);

up.panel.Settings = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,deferredRender: true
        ,baseCls: 'modx-formpanel'
        ,items: [{
            html: '<h2>'+_('minishop2') + ' :: ' + _('ms2_settings')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header container'
        },{
            xtype: 'modx-tabs'
            ,bodyStyle: 'padding: 5px'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,hideMode: 'offsets'
            ,stateful: true
            ,stateId: 'up-settings-tabpanel'
            ,stateEvents: ['tabchange']
            ,getState:function() {return { activeTab:this.items.indexOf(this.getActiveTab())};}
            ,items: [{
                title: _('ms2_deliveries')
                ,deferredRender: true
                ,items: [{
                    html: '<p>'+_('ms2_deliveries_intro')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                    ,bodyStyle: 'margin-bottom: 10px'
                },{
                    xtype: 'up-grid-extended'
                }]
            }]
        }]
    });
    up.panel.Settings.superclass.constructor.call(this,config);
};
Ext.extend(up.panel.Settings,MODx.Panel);
Ext.reg('up-panel-settings',up.panel.Settings);
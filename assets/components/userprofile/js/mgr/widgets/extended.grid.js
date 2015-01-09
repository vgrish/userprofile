userprofile.grid.Extended = function(config) {
    config = config || {};

    this.exp = new Ext.grid.RowExpander({
        expandOnDblClick: false
        ,tpl : new Ext.Template('<p class="desc">{description}</p>')
        ,renderer : function(v, p, record){return record.data.description != '' && record.data.description != null ? '<div class="x-grid3-row-expander">&#160;</div>' : '&#160;';}
    });
    this.dd = function(grid) {
        this.dropTarget = new Ext.dd.DropTarget(grid.container, {
            ddGroup : 'dd',
            copy:false,
            notifyDrop : function(dd, e, data) {
                var store = grid.store.data.items;
                var target = store[dd.getDragData(e).rowIndex].id;
                var source = store[data.rowIndex].id;
                if (target != source) {
                    dd.el.mask(_('loading'),'x-mask-loading');
                    MODx.Ajax.request({
                        url: userprofile.config.connectorUrl
                        ,params: {
                            action: config.action || 'mgr/settings/extended/sort'
                            ,source: source
                            ,target: target
                        }
                        ,listeners: {
                            success: {fn:function(r) {dd.el.unmask();grid.refresh();},scope:grid}
                            ,failure: {fn:function(r) {dd.el.unmask();},scope:grid}
                        }
                    });
                }
            }
        });
    };

    Ext.applyIf(config,{
        id: 'userprofile-grid-extended'
        ,url: userprofile.config.connectorUrl
        ,baseParams: {
            action: 'mgr/settings/extended/getlist'
        }
        ,fields: ['id','name','description','rank','active','class']
        ,autoHeight: true
        ,paging: true
        ,remoteSort: true
        ,save_action: 'mgr/settings/extended/updatefromgrid'
        ,autosave: true
        ,plugins: this.exp
        ,columns: [this.exp
            ,{header: _('up_id'),dataIndex: 'id',width: 50}
            ,{header: _('up_name'),dataIndex: 'name',width: 100, editor: {xtype: 'textfield', allowBlank: false}}
            //,{header: _('up_add_cost'),dataIndex: 'price',width: 50, editor: {xtype: 'textfield'}}
            //,{header: _('up_logo'),dataIndex: 'logo',width: 75, renderer: this.renderLogo}
            ,{header: _('up_active'),dataIndex: 'active',width: 50, editor: {xtype: 'combo-boolean', renderer: 'boolean'}}
            ,{header: _('up_class'),dataIndex: 'class',width: 75, editor: {xtype: 'textfield'}}
        ]
        ,tbar: [{
            text: _('up_btn_create')
            ,handler: this.createExtended
            ,scope: this
        }]
        ,ddGroup: 'dd'
        ,enableDragDrop: true
        ,listeners: {render: {fn: this.dd, scope: this}}
    });
    userprofile.grid.Extended.superclass.constructor.call(this,config);

    this.store.on('load', function(store) {
        userprofile.PaymentsArray = [];
        var items = store.data.items;
        for (i in items) {
            if (items.hasOwnProperty(i) ) {
                userprofile.PaymentsArray.push({id: items[i].id, name: items[i].data.name})
            }
        }
    })
};
Ext.extend(userprofile.grid.Extended,MODx.grid.Grid,{
    windows: {}

    ,getMenu: function() {
        var m = [];
        m.push({
            text: _('up_menu_update')
            ,handler: this.updateExtended
        });
        m.push('-');
        m.push({
            text: _('up_menu_remove')
            ,handler: this.removeExtended
        });
        this.addContextMenuItem(m);
    }

    ,renderLogo: function(value) {
        if (/(jpg|png|gif|jpeg)$/i.test(value)) {
            if (!/^\//.test(value)) {value = '/' + value;}
            return '<img src="'+value+'" height="35" />';
        }
        else {
            return '';
        }
    }

    ,createExtended: function(btn,e) {
        if (!this.windows.createExtended) {
            this.windows.createExtended = MODx.load({
                xtype: 'userprofile-window-extended-create'
                ,fields: this.getExtendedFields('create')
                ,listeners: {
                    success: {fn:function() { this.refresh(); },scope:this}
                }
            });
        }
        this.windows.createExtended.fp.getForm().reset();
        this.windows.createExtended.show(e.target);
    }
    ,updateExtended: function(btn,e) {
        if (!this.menu.record || !this.menu.record.id) return false;
        var r = this.menu.record;

        if (!this.windows.updateExtended) {
            this.windows.updateExtended = MODx.load({
                xtype: 'userprofile-window-extended-update'
                ,record: r
                ,fields: this.getExtendedFields('update')
                ,listeners: {
                    success: {fn:function() { this.refresh(); },scope:this}
                }
            });
        }
        this.windows.updateExtended.fp.getForm().reset();
        this.windows.updateExtended.fp.getForm().setValues(r);
        this.windows.updateExtended.show(e.target);
    }

    ,removeExtended: function(btn,e) {
        if (!this.menu.record) return false;

        MODx.msg.confirm({
            title: _('up_menu_remove') + '"' + this.menu.record.name + '"'
            ,text: _('up_menu_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/settings/extended/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                success: {fn:function(r) {this.refresh();}, scope:this}
            }
        });
    }

    ,getExtendedFields: function(type) {
        return [
            {xtype: 'hidden',name: 'id', id: 'userprofile-extended-id-'+type}
            ,{xtype: 'textfield',fieldLabel: _('up_name'), name: 'name', allowBlank: false, anchor: '99%', id: 'userprofile-extended-name-'+type}
            ,{xtype: 'textfield',fieldLabel: _('up_add_cost'), name: 'price', description: _('up_add_cost_help') ,allowBlank: true, anchor: '50%', id: 'userprofile-extended-price-'+type}
            ,{xtype: 'up-combo-browser',fieldLabel: _('up_logo'), name: 'logo', anchor: '99%',  id: 'userprofile-extended-logo-'+type}
            ,{xtype: 'textarea', fieldLabel: _('up_description'), name: 'description', anchor: '99%', id: 'userprofile-extended-description-'+type}
            ,{xtype: 'textfield',fieldLabel: _('up_class'), name: 'class', anchor: '99%', id: 'userprofile-extended-class-'+type}
            ,{xtype: 'xcheckbox', fieldLabel: '', boxLabel: _('up_active'), name: 'active', id: 'userprofile-extended-active-'+type}
        ];
    }

    ,beforeDestroy: function() {
        if (this.rendered) {
            this.dropTarget.destroy();
        }
        userprofile.grid.Extended.superclass.beforeDestroy.call(this);
    }
});
Ext.reg('userprofile-grid-extended',userprofile.grid.Extended);


// userprofile.window.CreateExtended
// userprofile.window.CreateExtended

userprofile.window.CreateExtended = function(config) {
    config = config || {};
    this.ident = config.ident || 'mecitem'+Ext.id();
    Ext.applyIf(config,{
        title: _('up_menu_create')
        ,width: 600
        ,autoHeight: true
        ,labelAlign: 'left'
        ,labelWidth: 180
        ,url: userprofile.config.connectorUrl
        ,action: 'mgr/settings/extended/create'
        ,fields: [
            {xtype: 'textfield',fieldLabel: _('name'),name: 'name',id: 'userprofile-'+this.ident+'-name',width: 300}
            ,{xtype: 'textarea',fieldLabel: _('description'),name: 'description',id: 'userprofile-'+this.ident+'-description',width: 300}
        ]
        ,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit() },scope: this}]
    });
    userprofile.window.CreateExtended.superclass.constructor.call(this,config);
};
Ext.extend(userprofile.window.CreateExtended,MODx.Window);
Ext.reg('userprofile-window-extended-create',userprofile.window.CreateExtended);


// userprofile.window.UpdateExtended
// userprofile.window.UpdateExtended

userprofile.window.UpdateExtended = function(config) {
    config = config || {};
    this.ident = config.ident || 'meuitem'+Ext.id();
    Ext.applyIf(config,{
        title: _('up_menu_update')
        ,id: this.ident
        ,width: 600
        ,autoHeight: true
        ,labelAlign: 'left'
        ,labelWidth: 180
        ,url: userprofile.config.connectorUrl
        ,action: 'mgr/settings/extended/update'
        ,fields: [
            {xtype: 'hidden',name: 'id',id: 'userprofile-'+this.ident+'-id'}
            ,{xtype: 'textfield',fieldLabel: _('name'),name: 'name',id: 'userprofile-'+this.ident+'-name',width: 300}
            ,{xtype: 'textarea',fieldLabel: _('description'),name: 'description',id: 'userprofile-'+this.ident+'-description',width: 300}
        ]
        ,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit() },scope: this}]
    });
    userprofile.window.UpdateExtended.superclass.constructor.call(this,config);
};
Ext.extend(userprofile.window.UpdateExtended,MODx.Window);
Ext.reg('userprofile-window-extended-update',userprofile.window.UpdateExtended);
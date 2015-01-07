userprofile.window.CreateItem = function (config) {
	config = config || {};
	if (!config.id) {
		config.id = 'userprofile-item-window-create';
	}
	Ext.applyIf(config, {
		title: _('userprofile_item_create'),
		width: 550,
		autoHeight: true,
		url: userprofile.config.connector_url,
		action: 'mgr/item/create',
		fields: this.getFields(config),
		keys: [{
			key: Ext.EventObject.ENTER, shift: true, fn: function () {
				this.submit()
			}, scope: this
		}]
	});
	userprofile.window.CreateItem.superclass.constructor.call(this, config);
};
Ext.extend(userprofile.window.CreateItem, MODx.Window, {

	getFields: function (config) {
		return [{
			xtype: 'textfield',
			fieldLabel: _('userprofile_item_name'),
			name: 'name',
			id: config.id + '-name',
			anchor: '99%',
			allowBlank: false,
		}, {
			xtype: 'textarea',
			fieldLabel: _('userprofile_item_description'),
			name: 'description',
			id: config.id + '-description',
			height: 150,
			anchor: '99%'
		}, {
			xtype: 'xcheckbox',
			boxLabel: _('userprofile_item_active'),
			name: 'active',
			id: config.id + '-active',
			checked: true,
		}];
	}

});
Ext.reg('userprofile-item-window-create', userprofile.window.CreateItem);


userprofile.window.UpdateItem = function (config) {
	config = config || {};
	if (!config.id) {
		config.id = 'userprofile-item-window-update';
	}
	Ext.applyIf(config, {
		title: _('userprofile_item_update'),
		width: 550,
		autoHeight: true,
		url: userprofile.config.connector_url,
		action: 'mgr/item/update',
		fields: this.getFields(config),
		keys: [{
			key: Ext.EventObject.ENTER, shift: true, fn: function () {
				this.submit()
			}, scope: this
		}]
	});
	userprofile.window.UpdateItem.superclass.constructor.call(this, config);
};
Ext.extend(userprofile.window.UpdateItem, MODx.Window, {

	getFields: function (config) {
		return [{
			xtype: 'hidden',
			name: 'id',
			id: config.id + '-id',
		}, {
			xtype: 'textfield',
			fieldLabel: _('userprofile_item_name'),
			name: 'name',
			id: config.id + '-name',
			anchor: '99%',
			allowBlank: false,
		}, {
			xtype: 'textarea',
			fieldLabel: _('userprofile_item_description'),
			name: 'description',
			id: config.id + '-description',
			anchor: '99%',
			height: 150,
		}, {
			xtype: 'xcheckbox',
			boxLabel: _('userprofile_item_active'),
			name: 'active',
			id: config.id + '-active',
		}];
	}

});
Ext.reg('userprofile-item-window-update', userprofile.window.UpdateItem);
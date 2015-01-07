userprofile.panel.Home = function (config) {
	config = config || {};
	Ext.apply(config, {
		baseCls: 'modx-formpanel',
		layout: 'anchor',
		/*
		 stateful: true,
		 stateId: 'userprofile-panel-home',
		 stateEvents: ['tabchange'],
		 getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
		 */
		hideMode: 'offsets',
		items: [{
			html: '<h2>' + _('userprofile') + '</h2>',
			cls: '',
			style: {margin: '15px 0'}
		}, {
			xtype: 'modx-tabs',
			defaults: {border: false, autoHeight: true},
			border: true,
			hideMode: 'offsets',
			items: [{
				title: _('userprofile_items'),
				layout: 'anchor',
				items: [{
					html: _('userprofile_intro_msg'),
					cls: 'panel-desc',
				}, {
					xtype: 'userprofile-grid-items',
					cls: 'main-wrapper',
				}]
			}]
		}]
	});
	userprofile.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(userprofile.panel.Home, MODx.Panel);
Ext.reg('userprofile-panel-home', userprofile.panel.Home);

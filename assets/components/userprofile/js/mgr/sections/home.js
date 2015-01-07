userprofile.page.Home = function (config) {
	config = config || {};
	Ext.applyIf(config, {
		components: [{
			xtype: 'userprofile-panel-home', renderTo: 'userprofile-panel-home-div'
		}]
	});
	userprofile.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(userprofile.page.Home, MODx.Component);
Ext.reg('userprofile-page-home', userprofile.page.Home);
var userprofile = function (config) {
	config = config || {};
	userprofile.superclass.constructor.call(this, config);
};
Ext.extend(userprofile, Ext.Component, {
	page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('userprofile', userprofile);

userprofile = new userprofile();
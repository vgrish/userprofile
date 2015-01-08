upTopPanel = function(config) {
    config = config || {};

    Ext.applyIf(config, {

    });
    upTopPanel.superclass.constructor.call(this, config);
};

Ext.extend(upTopPanel, MODx.grid.Grid, {


});
Ext.reg('up-top-panel', upTopPanel);
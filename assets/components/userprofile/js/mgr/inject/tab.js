Ext.ComponentMgr.onAvailable('modx-user-tabs', function() {
    this.on('beforerender', function() {

        var listeners = {
            change:{fn:MODx.fireResourceFormChange}
            ,select:{fn:MODx.fireResourceFormChange}
            ,keydown:{fn:MODx.fireResourceFormChange}
            ,check:{fn:MODx.fireResourceFormChange}
            ,uncheck:{fn:MODx.fireResourceFormChange}
        };

        this.add({
            title: _('userprofile')
            ,hideMode: 'offsets'
            ,items: [
                {
                    border: false,
                    baseCls: 'panel-desc',
                    html: '<p>' + _('up_introtext') + '</p>'
                }, {
                    layout: 'column',
                    border: false,
                    bodyCssClass: 'tab-panel-wrapper ',
                    style: 'padding: 15px;',
                    items: []
                }
            ]
        });
    });
    Ext.apply(this, {
        stateful: true,
        stateId: "modx-user-tabs-state",
        stateEvents: ["tabchange"],
        getState: function() {return {activeTab:this.items.indexOf(this.getActiveTab())};
        }
    });
});
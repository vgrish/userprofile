Ext.ComponentMgr.onAvailable('modx-user-tabs', function () {
    this.on('beforerender', function () {




        /*        if('' != ' ') {

         var url = 'inc/101e1569548.jpg';

         var image = {
         html: '<img src="'+MODx.config.connectors_url+'system/phpthumb.php?h=150&w=150&src='+url+'&wctx=MODx.ctx&source='+this.getSource()+'" alt="" />'

         };
         }*/

        var upBottomTabs = {
            html: '<p>frfrfr</p>',
            style: 'padding: 15px 25px 15px 15px;'
        };

        var listeners = {
            change: {fn: MODx.fireResourceFormChange}, select: {fn: MODx.fireResourceFormChange}, keydown: {fn: MODx.fireResourceFormChange}, check: {fn: MODx.fireResourceFormChange}, uncheck: {fn: MODx.fireResourceFormChange}
        };

        this.add({
            title: _('userprofile'), hideMode: 'offsets', items: [
                {
                    border: false,
                    baseCls: 'panel-desc',
                    html: '<p>' + _('up_introtext') + '</p>'
                },
                {
                    layout: 'column',
                    border: false,
                    bodyCssClass: 'tab-panel-wrapper ',
                    style: 'padding: 15px;',
                    items: [
                        {

                            columnWidth: .3,
                            xtype: 'panel',
                            border: false,
                            layout: 'form',
                            labelAlign: 'top',
                            preventRender: true,
                            items: [
                                {
                                    xtype: 'fieldset',
                                    title: 'аватарка',//_('pas_subscription_fieldset'),
                                    layoutConfig: {
                                        labelAlign: 'top'
                                    },
                                    layout: 'column',
                                    items: [
                                        {



                                            xtype: 'up-combo-browser',
                                            fieldLabel: _('ms2_logo'),
                                            name: 'logo',
                                            anchor: '99%',
                                            id: 'minishop2-payment-logo-'
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            columnWidth: .7,
                            xtype: 'panel',
                            border: false,
                            layout: 'form',
                            labelAlign: 'top',
                            preventRender: true,
                            items: [
                                {
                                    xtype: 'fieldset',
                                    title: 'Информация',//_('pas_subscription_fieldset'),
                                    layoutConfig: {
                                        labelAlign: 'top'
                                    },
                                    layout: 'column',
                                    items: [
                                        {
                                            columnWidth: 1,
                                            xtype: 'panel',
                                            border: false,
                                            layout: 'form',
                                            //style: 'padding: 10px 10px 0 0',
                                            labelAlign: 'top',
                                            preventRender: true,
                                            items: [
                                                {
                                                    xtype: 'textarea',
                                                    name: 'pas[pas_description]',
                                                    value: '',
                                                    description: _('pas_pas_description_desc'),
                                                    fieldLabel: 'название',//_('pas_pas_description'),
                                                    anchor: '100%',
                                                    //height: 126,
                                                    enableKeyEvents: true,
                                                    listeners: listeners
                                                },
                                                {
                                                    xtype: 'textarea',
                                                    name: 'pas[pas_description]',
                                                    value: '',
                                                    description: _('pas_pas_description_desc'),
                                                    fieldLabel: 'название2',//_('pas_pas_description'),
                                                    anchor: '100%',
                                                    height: 126,
                                                    enableKeyEvents: true,
                                                    listeners: listeners
                                                }
                                            ]

                                        }
                                    ]

                                }
                            ]
                        }
                    ]
                },
                upBottomTabs
            ]
        });
    });
    Ext.apply(this, {
        stateful: true,
        stateId: "modx-user-tabs-state",
        stateEvents: ["tabchange"],
        getState: function () {
            return {activeTab: this.items.indexOf(this.getActiveTab())};
        }
    });
});
Ext.ComponentMgr.onAvailable('modx-user-tabs', function () {
    this.on('beforerender', function () {

        var config = userprofile.config;
        var avatarSrc = (config.profile.photo != '')
            ? MODx.config.connectors_url + 'system/phpthumb.php?h=230&w=294&src=' + config.profile.photo + '&wctx=MODx.ctx&source=1'
            : config.profile.gravatar;

        var avatar = {
            html: '<div id="up-avatar">'
                + '<img src="' + avatarSrc +'" alt="" / class="up-avatar">'
                + '<div id="up-avatar"></div>'
        };

        var getCurrentContractsFields =  function(type, fields) {

            console.log('33');

            var separator = ',';
            var tabsList = config.extSetting.tabs.split(separator);
            var tabsItemsList = [];
            Ext.each(tabsList, function(tab) {

                console.log('-'+tab+'-');


                if (tab in ['activity', 'personal', '']) {

                    console.log('d');
                    //return false;
                }
                var tabItems = [];
                for (v in fields[tab]) {
                    var tabItem = {xtype: 'textfield', fieldLabel: _('up_field_' + v), description: _('up_field_' + v + '_help'), name: v, allowBlank: true, value: fields[tab][v], anchor: '99%', id: 'up-extended-current-' + v + '-' + type};
                    tabItems.push(tabItem);
                }

                var tabContent = {
                    title: _('up_' + tab)
                    , items: tabItems
                    , id: tab
                };
                tabsItemsList.push(tabContent);
            }, this);

            return {
                xtype: 'modx-tabs',
                autoHeight: true,
                deferredRender: false,
                forceLayout: true,
                id: 'contract-tab-panel-type-', //type,
                width: '98%',
                bodyStyle: 'padding: 10px 10px 10px 10px;',
                border: true,
                defaults: {
                    border: false,
                    autoHeight: true,
                    bodyStyle: 'padding: 5px 8px 5px 5px;',
                    layout: 'form',
                    deferredRender: false,
                    forceLayout: true
                },
                items: tabsItemsList,

                //html: '<div id="userprofile-tab-extended-div">frfrfr</div>',
                style: 'padding: 15px 25px 15px 15px;'
            };

        };

        var fields = ['lastname','firstname'];//properties.fields;

        var upBottomTabs = getCurrentContractsFields('update', fields);

/*        var tabPanel = Ext.getCmp('contract-tab-panel-type-update');
        Ext.each(tabPanel.items.items, function(tab) {
            if (tab.id == 'tab_files') {
                return false;
            }

            var items = {};
            Ext.each(tab.items.items, function(input) {
                items[input.name] = input.getValue();
            });
            fieldList[tab.id] = items;
        });
        var properties = Ext.getCmp('referral-currentcontracts-properties-update').getValue();
        var propObj = Ext.decode(properties);
        propObj.fields = fieldList;
        Ext.getCmp('referral-currentcontracts-properties-update').setValue(JSON.stringify(propObj));
        return true;*/


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
                                            columnWidth: 1,
                                            xtype: 'panel',
                                            border: false,
                                            layout: 'form',
                                            labelAlign: 'top',
                                            preventRender: true,
                                            items: [
                                                {
                                                    xtype: 'up-combo-browser',
                                                    fieldLabel: 'def',//_('ms2_logo'),
                                                    name: 'up-data[logo]',
                                                    anchor: '100%',
                                                    id: 'up-combo-browser',
                                                    value: config.profile.photo || ''
                                                },
                                                avatar
                                            ]

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
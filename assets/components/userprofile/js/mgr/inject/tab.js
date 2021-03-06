Ext.ComponentMgr.onAvailable('modx-user-tabs', function () {
    this.on('beforerender', function () {

        var config = userprofile.config,
            fields = userprofile.config.extSetting.tabfields,
            tabs = userprofile.config.tabs,
            disabledTabs = userprofile.config.disabledTabs,
            requires = userprofile.config.requires,
            tabsList = tabs.split(','),
            data = userprofile.config.upExtended;

        var http =  function(uri) {
            return /^(https?|ftp)\:\/\/[a-zA-Z0-9\.\-]+\.[a-z]{2,}(\/.+)$/.test(uri);
        };

        var getSource = function(){
            return config.source || 1;
        };

        var avatarSrc = (config.profile.photo != '')
            ? config.profile.photo
            : config.profile.gravatar;

        if (!http(avatarSrc)) {
        avatarSrc = MODx.config.connectors_url + 'system/phpthumb.php?h=193&w=308&src=/' + avatarSrc + '&wctx=MODx.ctx&source='+getSource()}

        var avatar = {
            html: '<div id="up-avatar">'
                + '<img src="' + avatarSrc +'" alt="" / class="up-avatar">'
                + '<div id="up-avatar"></div>'
        };

        var prepareData =  function() {
            if (typeof fields !== 'object') {
                fields = Ext.decode(fields);
            }
            if (typeof requires !== 'object') {
                requires = Ext.decode(requires);
            }
            Ext.each(tabsList, function(tab) {
                for (v in fields[tab]) {
                    if (!!!data[tab]) data[tab] = fields[tab];
                }
            }, this);
        };

        var getCurrentContractsFields =  function(type) {
            var tabsItemsList = [];
            Ext.each(tabsList, function(tab) {
                var tabItems = [];
                for (v in fields[tab]) {
                    //console.log(data[v]);
                    var tabItem = {
                        xtype: (fields[tab][v] == '') ? 'textfield' : fields[tab][v],
                        fieldLabel: _('up_field_' + v) || 'up_field_' + v,
                        description: _('up_field_' + v + '_help'),
                        name: 'up['+tab+']['+v+']',
                        allowBlank: (!!!requires[v]) ? true : false,
                        value: data[v] || data[tab][v],
                        anchor: '99%', id: 'up-extended-current-' + v + '-' + type,
                        //style: 'margin:0px 0px 15px 10px;',
                        //labelStyle: 'margin:0px 0px 0px 0px;',
                        itemCls: 'upblock',
                        ctCls: 'updiv',
                        disabled: (tab == disabledTabs) ? 1 : 0,
                    };
                    tabItems.push(tabItem);
                }
                var tabContent = {
                    title: _('up_' + tab) || tab,
                    items: tabItems,
                    id: tab
                };
                tabsItemsList.push(tabContent);
            }, this);

            //console.log(tabsItemsList);
            return {
                xtype: 'modx-tabs',
                autoHeight: true,
                deferredRender: false,
                forceLayout: true,
                id: 'contract-tab-panel-type-'+type,
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
                style: 'padding: 15px 25px 15px 15px;'

            };

        };
        prepareData();
        var upBottomTabs = getCurrentContractsFields('update');

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
                                    title: _('up_fieldset_avatar'),
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
                                                    fieldLabel: _('up_avatar'),
                                                    name: 'photo',
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
                                    title: _('up_fieldset_info'),
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
                                                    xtype: 'hidden',
                                                    name: 'up[real][type_id]',
                                                    value: userprofile.config.extSetting.id
                                                },
                                                {
                                                    xtype: 'textarea',
                                                    name: 'up[real][description]',
                                                    value: data.description || '',
                                                    description: _('up_description_help'),
                                                    fieldLabel: _('up_description'),
                                                    anchor: '100%',
                                                    //height: 126,
                                                    enableKeyEvents: true,
                                                    //listeners: listeners
                                                },
                                                {
                                                    xtype: 'textarea',
                                                    name: 'up[real][introtext]',
                                                    value: data.introtext || '',
                                                    description: _('up_introtext_help'),
                                                    fieldLabel: _('up_introtext'),
                                                    anchor: '100%',
                                                    height: 126,
                                                    enableKeyEvents: true,
                                                    //listeners: listeners
                                                }
                                            ]

                                        }
                                    ]

                                }
                            ]
                        }
                    ]
                }
                ,upBottomTabs
            ]
        });
    });
    Ext.apply(this, {
        stateful: true,
        stateId: 'modx-user-tabs-state',
        stateEvents: ['tabchange'],
        getState: function () {
            return {activeTab: this.items.indexOf(this.getActiveTab())};
        }
    });
});
Nova.booting((Vue, router) => {
    router.addRoutes([
        {
            name: 'ItemBuilder',
            path: '/item-builder',
            component: require('./components/Tool'),
            meta:{
                moduleName:'items'
            },
            props: () => {
                return {
                    resourceName: 'items',
                }
            },
        },

        {
            name: 'ItemBuilderBrowse',
            path: '/items',
            component: require('./components/Browse'),
            meta:{
                moduleName:'items'
            },
            props: () => {
                return {
                    resourceName: 'items',
                }
            },
        },
    ])
})

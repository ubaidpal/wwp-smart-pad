Nova.booting((Vue, router) => {
    Vue.config.devtools = true;
router.addRoutes([
    {
        name: 'ProductBuilder',
        path: '/ProductBuilder/:product_id?/:brand_id?/:category_id?',
        component: require('./components/Tool'),
        meta:{
            moduleName:'products'
        },
        props: () => {
            return {
                resourceName: 'products',
            }
        },
    },
    {
        name: 'ProductBuilder',
        path: '/ProductBuilder/:brand_id?/:category_id?',
        component: require('./components/Tool'),
        meta:{
            moduleName:'products'
        },
        props: () => {
            return {
                resourceName: 'products',
            }
        },
    },
    {
        name: 'product-list',
        path: '/products/:brand_id?/:category_id?',
        component: require('./components/pages/ProductList'),
        meta:{
            moduleName:'products'
        },
        props: () => {
            return {
                resourceName: 'ProductList',
            }
        },
    },
    {
        name: 'ProductsNotInCollection',
        path: '/products-not-in-collection',
        component: require('./components/product-collection/ProductsNotInCollection'),
        meta:{
            moduleName:'products'
        },
        props: () => {
            return {
                resourceName: 'products-not-in-collection',
            }
        }
    },
    {
        name: 'ProductCollection',
        path: '/product-collections',
        component: require('./components/product-collection/ProductCollection'),
        meta:{
            moduleName:'products'
        },
        props: () => {
            return {
                resourceName: 'products-collection',
            }
        }
    },
    {
        name: 'category-list',
        path: '/categories',
        component: require('./components/pages/CategoryList'),
        meta:{
            moduleName:'config'
        },
        props: () => {
            return {
                resourceName: 'CategoryList',
            }
        },
    },
    {
        name: 'fabric-category-list',
        path: '/FabricCategoryList',
        component: require('./components/pages/FabricCategoryList'),
        meta:{
            moduleName:'config'
        },
        props: () => {
            return {
                resourceName: 'FabricCategoryList',
            }
        },
    },
    {
        name: 'brand-list',
        path: '/brands',
        component: require('./components/pages/BrandList'),
        meta:{
            moduleName:'config'
        },
        props: () => {
            return {
                resourceName: 'BrandList',
            }
        },
    },
    {
        name: 'countries-list',
        path: '/countries',
        component: require('./components/pages/CountriesList'),
        meta:{
            moduleName:'config'
        },
        props: () => {
            return {
                resourceName: 'CountriesList',
            }
        },
    },
    {
        name: 'create-dealer',
        path: '/create/dealer',
        component: require('./components/dealer/dealer'),
        meta:{
            moduleName:'dealers'
        },
        props: () => {
            return {
                resourceName: 'createDealer',
            }
        }
    },
    {
        name: 'edit-dealer',
        path: '/edit/dealer/:account_id',
        component: require('./components/dealer/dealer'),
        meta:{
            moduleName:'dealers'
        },
        props: () => {
            return {
                resourceName: 'createDealer',
            }
        }
    },
    {
        name: 'dealer-brands-access',
            path: '/edit/dealer/dealers/:id/brands',
        component: require('./components/pages/DealerBrands'),
        meta:{
        moduleName:'dealers'
    },
        props: () => {
        return {
            resourceName: 'dealerBrands',
        }
    }
    },
    {
        name: 'dealer-list',
        path: '/dealers',
        component: require('./components/pages/DealerList'),
        meta:{
            moduleName:'dealers'
        },
        props: () => {
            return {
                resourceName: 'dealerList',
            }
        }
    },
    {
        name: 'companies-list',
        path: '/companies',
        component: require('./components/pages/CompaniesList'),
        meta:{
            moduleName:'dealers'
        },
        props: () => {
            return {
                resourceName: 'companiesList',
            }
        }
    },
    {
        name: 'employees-list',
        path: '/employees',
        component: require('./components/pages/EmployeesList'),
        meta:{
            moduleName:'dealers'
        },
        props: () => {
            return {
                resourceName: 'employeeList',
            }
        }
    },
    {
        name: 'group-list',
        path: '/grouping',
        component: require('./components/pages/Grouping'),
        meta:{
            moduleName:'dealers'
        },
        props: () => {
            return {
                resourceName: 'grouping',
            }
        }
    },
    {
        name: 'dealer-employees',
        path: '/dealers/:id?/employees',
        component: require('./components/pages/EmployeesList'),
        props: () => {
            return {
                resourceName: 'employeeList',
            }
        }
    },
    {
        name: 'dashboard-setting',
        path: '/dashboard-settings',
        component: require('./components/pages/dashboardSetting'),
        meta:{
            moduleName:'config'
        },
        props: () => {
            return {
                resourceName: 'dashboardSetting',
            }
        }
    },
    {
        name: 'notification-setting',
        path: '/notification-settings',
        component: require('./components/pages/notifications'),
        meta:{
            moduleName:'config'
        },
        props: () => {
        return {
            resourceName: 'notificationSetting',
        }
    }
    },
    {
        name: 'notification-logs',
        path: '/notification-logs',
        component: require('./components/pages/notificationLogs'),
        meta:{
            moduleName:'config'
        },
        props: () => {
            return {
                resourceName: 'notificationLogs',
            }
        }
    },
    {
        name: 'help-Listing',
        path: '/helps/:help_group_id?',
        component: require('./components/pages/helpListing'),
        meta:{
            moduleName:'dealers'
        },
        props: () => {
            return {
                resourceName: 'help',
            }
        }
    },
    {
        name: 'dealer-brands',
        path: '/dealers/:id/brands',
        component: require('./components/pages/DealerBrands'),
        meta:{
            moduleName:'dealers'
        },
        props: () => {
            return {
                resourceName: 'dealerBrands',
            }
        }
    },
    {
        name: 'brand-dealers',
        path: '/brands/:id/dealer',
        component: require('./components/pages/BrandDealers'),
        meta:{
            moduleName:'dealers'
        },
        props: () => {
            return {
                resourceName: 'brandDealers',
            }
        }
    },
    {
        name: 'assign-brand',
        path: '/brands/:id/assign',
        component: require('./components/pages/AssignBrandDealers'),
        meta:{
            moduleName:'dealers'
        },
        props: () => {
            return {
                resourceName: 'brandDealers',
            }
        }
    },
    {
        name: 'user-list',
        path: '/users',
        component: require('./components/access-control/UserList'),
        meta:{
            moduleName:'access_control'
        },
        props: () => {
            return {
                resourceName: 'UserList',
            }
        }
    },
    {
        name: 'role-list',
        path: '/roles',
        component: require('./components/access-control/RoleList'),
        meta:{
            moduleName:'access_control'
        },
        props: () => {
            return {
                resourceName: 'RoleList',
            }
        }
    },
    {
        name: 'log-list',
        path: '/logs',
        component: require('./components/access-control/LogList'),
        meta:{
            moduleName:'access_control'
        },
        props: () => {
            return {
                resourceName: 'LogList',
            }
        }
    },
    {
        name: 'notification-form',
        path: '/notification-settings/new',
        component: require('./components/pages/notification-form'),
        meta:{
            moduleName:'config'
        },
        props: () => {
            return {
                resourceName: 'notification-settings',
            }
        }
    },
    {
        name: 'notification-form',
        path: '/notification-settings/edit/:id',
        component: require('./components/pages/notification-form'),
        meta:{
            moduleName:'config'
        },
        props: () => {
            return {
                resourceName: 'notification-settings',
            }
        }
    },
    {
        name: 'brands',
            path: '/brands/new',
        component: require('./components/brands/brand-form'),
        meta:{
        moduleName:'config'
    },
        props: () => {
        return {
            resourceName: 'brands',
        }
    }
    },
    {
        name: 'brands',
            path: '/brands/edit/:id',
        component: require('./components/brands/brand-form'),
        meta:{
        moduleName:'config'
    },
        props: () => {
        return {
            resourceName: 'brands',
        }
    }
    },
    {
        name: 'account-types',
        path: '/account-types',
        component: require('./components/pages/AccountTypesList'),
        meta:{
            moduleName:'dealers'
        },
        props: () => {
            return {
                resourceName: 'Account Types',
            }
        }
    },
    {
        name: 'users-country',
        path: '/users-country',
        component: require('./components/access-control/UsersCountry'),
        meta:{
            moduleName:'users_country'
        },
        props: () => {
            return {
                resourceName: 'Users Country',
            }
        }
    }

]);
    router.beforeEach((to, from, next) => {
        //console.log(to.params.resourceName);
        Nova.request().
        get(`/nova-vendor/ProductBuilder/getUserAccess`).
        then((response) => {
            let access = response.data.access;
            if(access == '*'){
                next();
            } else {

                if(to.name == '404' || to.name == 'dashboard') {
                    next();
                } else {
                    var check = false;
                    for(let data of access){
                        var module =  to.meta.moduleName;
                        /**/
                        if(to.params.resourceName == 'companies'){
                            module = 'dealers';
                        } else if(to.params.resourceName == 'categories' || to.params.resourceName == 'brands' || to.params.resourceName == 'countries' || to.params.resourceName == 'dashboard-settings' || to.params.resourceName == 'notification-settings' || to.params.resourceName == 'brand-settings'){
                            module = 'config';
                        } else if (to.params.resourceName == 'users' || to.params.resourceName == 'roles' || to.params.resourceName == 'logs'){
                            module = 'access_control';
                        }
                        /**/


                        if(data == module){
                            check = true;
                            next();
                            break;
                        }
                    }
                    if(!check){
                        next({ name: '404' });
                    }
                }
            }
        }).catch(function(thrown) {
            console.log('<<<<<<'+thrown);
        });

    });

Vue.component('product-brand', require('./components/pages/product-popup/brand'));
Vue.component('product-category', require('./components/pages/product-popup/category'));
Vue.component('dashboard-setting', require('./components/pages/dashboardSetting'));
Vue.component('notification-setting', require('./components/pages/notifications'));
Vue.component('notification-logs', require('./components/pages/notificationLogs'));
Vue.component('help-Listing', require('./components/pages/helpListing'));
Vue.component('product-collection-item', require('./components/product-collection/ProductCollectionItem'));
Vue.component('product-collection', require('./components/product-collection/ProductCollection'));
Vue.component('products-not-in-collection', require('./components/product-collection/ProductsNotInCollection'));
Vue.component('product-list', require('./components/pages/ProductList'));
Vue.component('price-grid', require('./components/price-grid/PriceGrid'));
Vue.component('product-options', require('./components/product-options/ProductOptions'));
Vue.component('options', require('./components/product-options/OptionsTable'));
Vue.component('product-sub-options', require('./components/product-options/ProductSubOptions'));
Vue.component('active-countries', require('./components/pages/Countries'));
Vue.component('size',require('./components/product-options/OptionSize'));
Vue.component('dealer-list', require('./components/pages/DealerList'));
Vue.component('group',require('./components/product-options/OptionsTable'));
Vue.component('option-price-grid',require('./components/product-options/OptionPriceGrid'));
Vue.component('toggle-button',require('vue-js-toggle-button/src/Button.vue'));
Vue.component('bread-crumbs', require('./components/pages/Breadcrumbs'));
Vue.component('companies-list', require('./components/pages/CompaniesList'));
Vue.component('employees-list', require('./components/pages/EmployeesList'));
Vue.component('tree-item', require('./components/pages/GroupingTreeItem'));
Vue.component('add-collection', require('./components/product-collection/AddCollection'));
Vue.component('constraints-editor', require('./components/product-options/Constraints'));
Vue.component('option-copy-popup', require('./components/product-options/copy-function/selectionPopup'));
Vue.component('left-navigation', require('./components/pages/navigation'));
Vue.component('account-types', require('./components/pages/AccountTypesList'));
Vue.component('dealer-status', require('./components/pages/DealerStatus'));


});

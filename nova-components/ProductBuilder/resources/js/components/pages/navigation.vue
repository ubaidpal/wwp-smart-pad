<template>
    <loading-view :loading="loading">
        <div>
            <div v-for="(menu,key) in navigation">
                <h3 class="flex items-center font-normal text-white mb-6 text-base no-underline" v-if="checkAccess(menu.whitecard)">
                    <div v-html="menu.module_icon" />
                    <span class="sidebar-label">{{menu.module_name}}</span>
                </h3>
                <ul class="list-reset mb-8" v-if="menu.sub_modules.length > 0 && checkAccess(menu.whitecard)">
                    <li v-for="(module,key) in menu.sub_modules" class="leading-tight mb-4 ml-8 text-sm">
                        <router-link :to="{
                            name: module.name,
                            params: {
                                resourceName: module.resourceName
                            }
                        }" class="text-white text-justify no-underline dim">
                            {{module.label}}
                        </router-link>
                    </li>
                </ul>
            </div>
        </div>
    </loading-view>
</template>

<script>
    export default {
        name: "navigation",
        data: () => ({
            loading: true,
            accessControl:[],
            navigation:[
                {
                    whitecard:'products',
                    module_name: 'Product Manager',
                    module_icon: '<svg class="sidebar-icon" viewBox="0 0 511 511" xmlns="http://www.w3.org/2000/svg"><path fill="#ffffff" d="m481.992188  425.070312v-259.574218c0-8.28125-6.71875-15-15-15-8.285157 0-15 6.71875-15 15v75h-75c-8.285157 0-15 6.714844-15 15 0 8.28125 6.714843 15 15 15h75v154.574218c-12.765626 4.527344-22.894532 14.65625-27.421876 27.421876h-152.574218v-107c0-8.28125-6.71875-15-15-15-8.285156 0-15 6.71875-15 15v107h-154.578125c-4.527344-12.765626-14.65625-22.894532-27.417969-27.421876v-154.574218h106.996094c8.285156 0 15-6.71875 15-15 0-8.285156-6.714844-15-15-15h-106.996094v-152.578125c12.761719-4.527344 22.890625-14.65625 27.417969-27.417969h154.578125v74.996094c0 8.285156 6.714844 15 15 15 8.28125 0 15-6.714844 15-15v-74.996094h74.996094c8.285156 0 15-6.71875 15-15 0-8.285156-6.714844-15-15-15h-259.574219c-6.191407-17.457031-22.863281-30-42.417969-30-24.8125 0-45 20.1875-45 45 0 19.554688 12.539062 36.226562 30 42.417969v337.152343c-17.460938 6.191407-30 22.867188-30 42.421876 0 24.8125 20.1875 44.996093 45 44.996093 19.554688 0 36.226562-12.539062 42.417969-29.996093h337.152343c6.191407 17.457031 22.867188 29.996093 42.421876 29.996093 24.8125 0 44.996093-20.183593 44.996093-44.996093 0-19.554688-12.539062-36.230469-29.996093-42.421876zm-436.992188-394.570312c8.269531 0 15 6.726562 15 15 0 8.269531-6.730469 15-15 15-8.273438 0-15-6.730469-15-15 0-8.273438 6.726562-15 15-15zm0 451.992188c-8.273438 0-15-6.730469-15-15 0-8.273438 6.726562-15 15-15 8.269531 0 15 6.726562 15 15 0 8.269531-6.730469 15-15 15zm421.992188 0c-8.273438 0-15-6.730469-15-15 0-8.273438 6.726562-15 15-15 8.269531 0 15 6.726562 15 15 0 8.269531-6.730469 15-15 15zm0 0" style="font-weight: bolder;border: 1px solid black;"></path> <path fill="#ffffff" d="m435.171875 13.679688-184.386719 184.386718s0 0-.003906.003906c0 0 0 .003907-.003906.003907l-28.484375 28.488281c-1.648438 1.644531-2.890625 3.652344-3.625 5.863281l-20.09375 60.277344-18.707031 18.710937c-5.859376 5.855469-5.859376 15.355469 0 21.210938 5.855468 5.855469 15.355468 5.859375 21.210937 0l18.707031-18.707031 60.28125-20.09375c2.210938-.738281 4.21875-1.976563 5.863282-3.625l212.878906-212.878907c17.585937-17.585937 17.589844-46.054687 0-63.640624-17.542969-17.542969-46.089844-17.542969-63.636719 0zm-167.953125 252.804687-31.816406 10.605469 10.605468-31.820313 15.378907-15.378906 21.214843 21.210937zm36.59375-36.59375-21.210938-21.214844 141.957032-141.957031 21.214844 21.210938zm173.785156-173.785156-10.613281 10.613281-21.210937-21.214844 10.609374-10.609375c5.851563-5.847656 15.367188-5.847656 21.214844 0 5.863282 5.863281 5.863282 15.347657 0 21.210938zm0 0"></path></svg>',
                    sub_modules:[
                        {
                            name:'product-list',
                            resourceName:'ProductList',
                            label:'Products'
                        },
                        {
                            name:'ProductCollection',
                            resourceName:'products-collection',
                            label:'Product Collections'
                        },
                        {
                            name:'ProductsNotInCollection',
                            resourceName:'products-not-in-collection',
                            label:'Products Not In Collection'
                        }
                    ]
                },
                {
                    whitecard:'items',
                    module_name: 'Item Manager',
                    module_icon: '<svg class="sidebar-icon" viewBox="0 0 511 511" xmlns="http://www.w3.org/2000/svg"><path fill="#ffffff" d="M432.544,310.636c0-9.897-3.521-18.559-10.564-25.984L217.844,80.8c-7.232-7.238-16.939-13.374-29.121-18.416 c-12.181-5.043-23.319-7.565-33.407-7.565H36.545c-9.896,0-18.464,3.619-25.694,10.848C3.616,72.9,0,81.466,0,91.365v118.771 c0,10.088,2.519,21.219,7.564,33.404c5.046,12.185,11.187,21.792,18.417,28.837L230.12,476.799 c7.043,7.043,15.608,10.564,25.694,10.564c9.898,0,18.562-3.521,25.984-10.564l140.186-140.47 C429.023,329.284,432.544,320.725,432.544,310.636z M117.204,172.02c-7.139,7.138-15.752,10.709-25.841,10.709 c-10.085,0-18.698-3.571-25.837-10.709c-7.139-7.139-10.705-15.749-10.705-25.837c0-10.089,3.566-18.702,10.705-25.837 c7.139-7.139,15.752-10.71,25.837-10.71c10.089,0,18.702,3.571,25.841,10.71c7.135,7.135,10.707,15.749,10.707,25.837 C127.91,156.271,124.339,164.881,117.204,172.02z"></path><path d="M531.612,284.655L327.473,80.804c-7.23-7.238-16.939-13.374-29.122-18.417c-12.177-5.042-23.313-7.564-33.402-7.564 h-63.953c10.088,0,21.222,2.522,33.402,7.564c12.185,5.046,21.892,11.182,29.125,18.417l204.137,203.851 c7.046,7.423,10.571,16.084,10.571,25.981c0,10.089-3.525,18.647-10.571,25.693L333.469,470.519 c5.718,5.9,10.759,10.182,15.133,12.847c4.38,2.666,9.996,3.998,16.844,3.998c9.903,0,18.565-3.521,25.98-10.564l140.186-140.47 c7.046-7.046,10.571-15.604,10.571-25.693C542.179,300.739,538.658,292.078,531.612,284.655z" fill="var(--sidebar-icon)"></path></svg>',
                    sub_modules:[
                        {
                            name:'ItemBuilderBrowse',
                            resourceName:'ItemBuilderBrowse',
                            label:'Items'

                        }
                    ]
                },
                {
                    whitecard:'dealers',
                    module_name: 'Dealer Manager',
                    module_icon: '<svg class="sidebar-icon" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill="#ffffff" d="M7,8 C9.209139,8 11,6.209139 11,4 C11,1.790861 9.209139,0 7,0 C4.790861,0 3,1.790861 3,4 C3,6.209139 4.790861,8 7,8 Z M7,9 C4.852,9 2.801,9.396 0.891,10.086 L2,16 L3.25,16 L4,20 L10,20 L10.75,16 L12,16 L13.109,10.086 C11.199,9.396 9.148,9 7,9 Z M15.315,9.171 L13.66,18 L12.41,18 L12.035,20 L16,20 L16.75,16 L18,16 L19.109,10.086 C17.899,9.648 16.627,9.346 15.315,9.171 Z M13,0 C12.532,0 12.089,0.096 11.671,0.243 C12.501,1.272 13,2.578 13,4 C13,5.422 12.501,6.728 11.671,7.757 C12.089,7.904 12.531,8 13,8 C15.209,8 17,6.209 17,4 C17,1.791 15.209,0 13,0 Z" id="Combined-Shape"></path></svg>',
                    sub_modules:[
                        {
                            name:'dealer-list',
                            resourceName:'DealerList',
                            label:'Dealers'

                        },
                        {
                            name:'create-dealer',
                            resourceName:'CreateDealer',
                            label:'Create Dealer'

                        },
                        {
                            name:'account-types',
                            resourceName:'AccountTypesList',
                            label:'Account Detail'

                        },

                        /*{
                            name:'employees-list',
                            resourceName:'EmployeesList',
                            label:'Employees'

                        },
                        {
                            name:'companies-list',
                            resourceName:'CompaniesList',
                            label:'Companies'

                        },
                        {
                            name:'group-list',
                            resourceName:'Grouping',
                            label:'Grouping'

                        }*/
                    ]
                },
                {
                    whitecard:'config',
                    module_name: 'Configuration',
                    module_icon: '<svg class="sidebar-icon" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="icon-shape"><path fill="#ffffff" d="M3.93830521,6.49683865 C3.63405147,7.02216933 3.39612833,7.5907092 3.23599205,8.19100199 L5.9747955e-16,9 L9.6487359e-16,11 L3.23599205,11.808998 C3.39612833,12.4092908 3.63405147,12.9778307 3.93830521,13.5031614 L2.22182541,16.363961 L3.63603897,17.7781746 L6.49683865,16.0616948 C7.02216933,16.3659485 7.5907092,16.6038717 8.19100199,16.7640079 L9,20 L11,20 L11.808998,16.7640079 C12.4092908,16.6038717 12.9778307,16.3659485 13.5031614,16.0616948 L16.363961,17.7781746 L17.7781746,16.363961 L16.0616948,13.5031614 C16.3659485,12.9778307 16.6038717,12.4092908 16.7640079,11.808998 L20,11 L20,9 L16.7640079,8.19100199 C16.6038717,7.5907092 16.3659485,7.02216933 16.0616948,6.49683865 L17.7781746,3.63603897 L16.363961,2.22182541 L13.5031614,3.93830521 C12.9778307,3.63405147 12.4092908,3.39612833 11.808998,3.23599205 L11,0 L9,0 L8.19100199,3.23599205 C7.5907092,3.39612833 7.02216933,3.63405147 6.49683865,3.93830521 L3.63603897,2.22182541 L2.22182541,3.63603897 L3.93830521,6.49683865 L3.93830521,6.49683865 Z M10,13 C11.6568542,13 13,11.6568542 13,10 C13,8.34314575 11.6568542,7 10,7 C8.34314575,7 7,8.34314575 7,10 C7,11.6568542 8.34314575,13 10,13 L10,13 Z" id="Combined-Shape"></path></g></g></svg>',
                    sub_modules:[
                        {
                            name:'category-list',
                            resourceName:'CategoryList',
                            label:'Categories'

                        },
                        {
                            name:'brand-list',
                            resourceName:'BrandList',
                            label:'Brands'

                        },
                        {
                            name:'countries-list',
                            resourceName:'CountriesList',
                            label:'Countries'

                        },
                        {
                            name:'dashboard-setting',
                            resourceName:'dashboard-setting',
                            label:'Dashboard Settings'

                        },
                        {
                            name:'notification-setting',
                            resourceName:'notification-setting',
                            label:'Notification Settings'

                        },
                        {
                            name:'notification-logs',
                            resourceName:'notification-logs',
                            label:'Notification Logs'

                        }
                    ]
                },
                {
                    whitecard: 'users_country',
                    module_name: 'Users Country',
                    module_icon: '<svg class="sidebar-icon" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="icon-shape"><path fill="#ffffff" d="M0,1.99079514 C0,0.891309342 0.898212381,0 1.99079514,0 L18.0092049,0 C19.1086907,0 20,0.898212381 20,1.99079514 L20,18.0092049 C20,19.1086907 19.1017876,20 18.0092049,20 L1.99079514,20 C0.891309342,20 0,19.1017876 0,18.0092049 L0,1.99079514 Z M6.99999861,6.00166547 C6.99999861,4.34389141 8.3465151,3 9.99999722,3 C11.6568507,3 12.9999958,4.33902013 12.9999958,6.00166547 L12.9999958,7.99833453 C12.9999958,9.65610859 11.6534793,11 9.99999722,11 C8.34314374,11 6.99999861,9.66097987 6.99999861,7.99833453 L6.99999861,6.00166547 Z M18.0000045,15.1405177 C15.6466165,13.7791553 12.914299,13 10,13 C7.08570101,13 4.35338349,13.7791553 1.99999555,15.1405177 L2,18 L18,18 L18,15.1405151 L18.0000045,15.1405177 Z" id="Combined-Shape"></path></g></g></svg>',
                    sub_modules: [
                        {
                            name: 'users-country',
                            resourceName: 'UserList',
                            label: 'Assign Country'

                        }
                    ]
                },
                {
                    whitecard:'access_control',
                    module_name: 'Access Control',
                    module_icon: '<svg class="sidebar-icon" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="icon-shape"><path fill="#ffffff" d="M11,14.7324356 C11.5978014,14.3866262 12,13.7402824 12,13 C12,11.8954305 11.1045695,11 10,11 C8.8954305,11 8,11.8954305 8,13 C8,13.7402824 8.40219863,14.3866262 9,14.7324356 L9,17 L11,17 L11,14.7324356 Z M13,6 C13,4.34314575 11.6568542,3 10,3 C8.34314575,3 7,4.34314575 7,6 L7,8 L13,8 L13,6 Z M4,8 L4,6 C4,2.6862915 6.6862915,0 10,0 C13.3137085,0 16,2.6862915 16,6 L16,8 L17.0049107,8 C18.1067681,8 19,8.90195036 19,10.0085302 L19,17.9914698 C19,19.1007504 18.1073772,20 17.0049107,20 L2.99508929,20 C1.8932319,20 1,19.0980496 1,17.9914698 L1,10.0085302 C1,8.8992496 1.8926228,8 2.99508929,8 L4,8 Z" id="Combined-Shape"></path></g></g></svg>',
                    sub_modules:[
                        {
                            name:'user-list',
                            resourceName:'UserList',
                            label:'Users'
                        },
                        {
                            name:'role-list',
                            resourceName:'RoleList',
                            label:'Roles'
                        },
                        {
                            name:'log-list',
                            resourceName:'LogList',
                            label:'Logs'
                        }
                    ]
                }
            ]
        }),
        methods: {
            checkAccess(value){
                if(this.accessControl == '*'){
                    return true;
                } else {
                    for(let mod of this.accessControl){
                        if(mod == value){
                            return true;
                        }
                    }
                    return false;
                }

            }
        },
        mounted() {
            this.loading = false;
        },
        async created(){
            await axios.get(`/nova-vendor/ProductBuilder/getUserAccess`).then((response) => {
                this.accessControl = response.data.access;
            });
        }
    }
</script>

<style scoped>

</style>

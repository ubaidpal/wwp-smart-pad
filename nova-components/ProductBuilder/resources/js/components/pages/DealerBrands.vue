<template>
    <loading-view :loading="loading">
        <div>
            <h1 class="mb-3 text-90 font-normal text-2xl">{{headers.heading}}</h1>
            <bread-crumbs :breadcrumbs="breadcrumbs"></bread-crumbs>
            <div class="flex" style="">
                <div class="flex items-center mb-6">
                    <div class="flex-no-shrink ml-auto">
                        &nbsp;
                    </div>
                </div>
            </div>
            <div class="card relative">
                <div class="overflow-hidden overflow-x-auto relative">
                    <div class="text-center">

                        <div class="flex border-b border-40">
                            <div class="w-1/5 py-6 px-8">
                                <label class="inline-block text-80 pt-2 leading-tight">
                                    Brand Type
                                </label></div>
                            <div class="py-6 px-8 w-1/2">
                                <!---->
                                <select @change="setBrandType()"  class="form-control form-select mb-3 w-full" v-model="brandType">
                                    <option value="fabric"  >Fabric</option>
                                    <option value="non_fabric"  >Non Fabric</option>
                                </select>
                                <!---->
                                <!---->
                                <div class="help-text help-text mt-2">

                                </div>
                            </div>
                        </div>

                        <div class="flex border-b border-40" v-if="!dealerId">
                            <div class="w-1/5 py-6 px-8">
                                <label class="inline-block text-80 pt-2 leading-tight">
                                    Dealer
                                </label></div>
                            <div class="py-6 px-8 w-1/2">
                                <!---->
                                <select @change="setActiveDealer(dealerId)" data-testid="brands-select" dusk="brands" class="form-control form-select mb-3 w-full" v-model="dealerId">
                                    <option value="" disabled="disabled" selected="selected">Choose Dealer First</option>
                                    <option v-for="dealer of dealers" :value="dealer.id">{{dealer.email}}</option>
                                </select>
                                <!---->
                                <!---->
                                <div class="help-text help-text mt-2">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <vue-good-table
                            :columns="columns"
                            :rows="rows"
                            :search-options="{
                                enabled: true,
                                placeholder: 'Search this table',
                            }"
                            :pagination-options="{
                                enabled: true,
                                perPage: 50
                            }"
                            @on-page-change="onPageChange"
                            @on-per-page-change="onPerPageChange"
                        >
                            <template slot="table-column" slot-scope="props">

                                <span v-if="props.column.field == 'brand_access'">

                                    {{props.column.label}}
                                    <toggle-button
                                        color="#8bc34a"
                                        :sync="true"
                                        :labels="true"
                                        v-model="brandAccessSelect"
                                        @change="selectAll(props.column.field, $event)"

                                    />
                                </span>
                                <span v-else-if="props.column.field == 'dealer_admin'">

                                    {{props.column.label}}
                                    <toggle-button
                                        color="#8bc34a"
                                        :sync="true"
                                        :labels="true"
                                        v-model="dealerAdminSelect"
                                        @change="selectAll(props.column.field, $event)"

                                    />
                                </span>
                                <span v-else-if="props.column.field == 'main_admin'">

                                    {{props.column.label}}
                                    <toggle-button
                                        color="#8bc34a"
                                        :sync="true"
                                        :labels="true"
                                        v-model="mainAdminSelect"
                                        @change="selectAll(props.column.field, $event)"

                                    />
                                </span>

                                <span v-else>
                                    {{props.column.label}}
                                </span>
                            </template>
                            <template slot="table-row" slot-scope="props">
                                <span v-if="props.column.field == 'brand_access'">
                                    <toggle-button
                                        @change="onChangeAccountStatus(props.row,props.column.field, props.row.brand_access)"
                                        v-model="props.row.brand_access"
                                        color="#8bc34a"
                                        :sync="true"
                                        :labels="true"/>
                                </span>
                                <span v-else-if="props.column.field == 'dealer_admin'">
                                    <toggle-button
                                        @change="onChangeAccountStatus(props.row,props.column.field, props.row.dealer_admin)"
                                        v-model="props.row.dealer_admin"
                                        color="#8bc34a"
                                        :sync="true"
                                        :labels="true"/>
                                </span>
                                <span v-else-if="props.column.field == 'main_admin'">
                                    <toggle-button
                                        @change="onChangeAccountStatus(props.row,props.column.field, props.row.main_admin)"
                                        v-model="props.row.main_admin"
                                        color="#8bc34a"
                                        :sync="true"
                                        :labels="true"/>
                                </span>
                                <span v-else>
                                    {{props.formattedRow[props.column.field]}}
                                </span>
                            </template>
                        </vue-good-table>
                    </div>
                </div>
            </div>

        </div>
    </loading-view>
</template>

<script>
    import {VueGoodTable} from 'vue-good-table';
    import 'vue-good-table/dist/vue-good-table.css';

    export default {
        name: "DealerBrands",
        data: () => ({
            loading: true,
            brandType:'non_fabric',
            headers:{
                heading:'Dealer Brands'
            },
            columns: [
            ],
            rows: [],
            dealers:[],
            tableCurrentPage:1,
            tableCurrentPerPage:50,
            mainAdminSelect:false,
            dealerAdminSelect:false,
            brandAccessSelect:false

        }),
        async created() {

            await Nova.request().get(`/nova-vendor/ProductBuilder/getDealerList`).then((response) => {
                this.dealers = response.data.accounts;
            });

            if(!this.resourceName){
                this.resourceName = 'Dealers Brand';
            }
            this.breadcrumbs=[
                {url:'/dealers',label:'Dealers'},
                {url:'',label:this.resourceName},
            ];

            this.setBrandType();
            this.resetSelects();
            this.loading = false;
        },
        methods: {

            onPageChange(params){
                this.currentPage = params.currentPage;
                this.currentPerPage = params.currentPerPage;
                this.resetSelects();

            },
            onPerPageChange(params){
                this.currentPage = params.currentPage;
                this.currentPerPage = params.currentPerPage;
                this.resetSelects();
            },
            getCurrentFirstIndex(){
                return (this.currentPage-1) * this.currentPerPage;
            },
            resetSelects(){
                this.mainAdminSelect = false;
                this.brandAccessSelect = false;
                this.dealerAdminSelect = false;

            },
            selectAll(type,event){

                let first = this.getCurrentFirstIndex();
                let last = (this.currentPerPage + first) > this.rows.length? this.rows.length: this.currentPerPage + first;
                let active = event.value;
                let brandsToAlter = [];
                for(let i = first; i < last ; i++){
                    this.rows[i][type] = event.value;
                    brandsToAlter.push(this.rows[i].id);
                    if(type == 'dealer_admin' && event.value){
                        this.rows[i].main_admin = !event.value;
                    }else if(type == 'main_admin' && event.value){
                        this.rows[i].dealer_admin = !event.value;
                    }
                }

                if(type == 'dealer_admin' && event.value){
                    this.mainAdminSelect = !event.value;
                }else if(type == 'main_admin' && event.value){
                    this.dealerAdminSelect = !event.value;
                }

                let formData = new FormData();
                formData.append('brands', brandsToAlter);
                formData.append('account_id', this.$route.params.id);
                formData.append('type', type);
                formData.append('active', (active == true || active == 'true'? 1 : 0) );
                Nova.request().post('/nova-vendor/ProductBuilder/updateAccountBrandInBulk',formData)
                    .then((response) => {
                        if(response.data.success){
                            this.$toasted.show(
                                this.__('The :resource are Updated!',{
                                    resource: 'Dealer Brands',
                                }),
                                {type: 'success'}
                            );
                        }else{
                            this.$toasted.show(
                                this.__('Unable to Update :resource!',{
                                    resource: 'Dealer Brands',
                                }),
                                {type: 'error'}
                            );
                        }
                    });


            },
            setBrandType(){
                if(this.brandType == 'fabric' || this.brandType  == 'non_fabric'){
                    this.columns = [
                        {
                            label: 'ID',
                            field: 'id'
                        },
                        {
                            label: 'Name',
                            field: 'name'
                        },
                        {
                            label: 'Brand Access',
                            field: 'brand_access',
                            sortable:false
                        }
                    ];
                    if(this.brandType == 'non_fabric'){
                        this.columns.push({
                            label: 'Product Builder Read Only',
                            field: 'dealer_admin',
                            sortable:false

                        });

                        this.columns.push({
                            label: 'Product builder Full Access',
                            field: 'main_admin',
                            sortable:false

                        });
                    }
                }
                this.refreshBrands();
                this.resetSelects();
            },
            async setActiveDealer(dealerId){
                this.rows = [];
                await Nova.request().get(`/nova-vendor/ProductBuilder/getBrandDealers/`+dealerId).then((response) => {
                    this.rows = response.data.brands;
                });
                this.resetSelects();

            },
            getBooleanStatus(status){
                if(status == 1){
                    return true;
                }else{
                    return false;
                }
            },
            /*
            * Send the change brand access status change
            * @param barnd_id = brand id
            * @param type = which access to change from brand_access, main_admin and dealer_admin
            * */
            onChangeAccountStatus(brand, type, active){
                let formData = new FormData();
                formData.append('brand_id', brand.id);
                formData.append('account_id', this.$route.params.id);
                formData.append('type', type);
                formData.append('active', (active == true || active == 'true'? 1 : 0) );
                Nova.request().post('/nova-vendor/ProductBuilder/updateAccountBrand',formData)
                    .then((response) => {
                        if(type == 'dealer_admin' && active){
                            brand.main_admin = !active;
                        }else if(type == 'main_admin' && active){
                            brand.dealer_admin = !active;
                        }

                        if(response.data.success){
                            this.$toasted.show(
                                this.__('The :resource are Updated!',{
                                    resource: 'Dealer Brands',
                                }),
                                {type: 'success'}
                            );
                        }else{
                            this.$toasted.show(
                                this.__('Unable to Update :resource!',{
                                    resource: 'Dealer Brands',
                                }),
                                {type: 'error'}
                            );
                        }
                    });

            },
            /*
            * refresh the brands list
            * */
            async refreshBrands(){
                var acount_id = this.$route.params.id;
                this.dealerId = this.$route.params.id;
                await Nova.request().get(`/nova-vendor/ProductBuilder/getBrandDealers/`+acount_id+'/'+ this.brandType).then((response) => {
                    this.rows = response.data.brands;
                });

            }
        },
        components: {
            VueGoodTable
        }
    }
</script>

<style scoped>

</style>

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
                                    Brands
                                </label></div>
                            <div class="py-6 px-8 w-1/2">
                                <select @change="setActiveBrand(brandId)"  data-testid="brands-select" dusk="brands" class="form-control form-select mb-3 w-full" v-model="brandId">
                                    <option value="" selected="selected">Choose Brand First</option>
                                    <option v-for="brand of brands" :value="brand.id">{{brand.name}}</option>
                                </select>
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

                                <span v-if="props.column.field == 'status'">

                                    {{props.column.label}}
                                    <toggle-button
                                        color="#8bc34a"
                                        :sync="true"
                                        :labels="true"
                                        v-model="brandAccessSelect"
                                        @change="selectAll('brand_access', $event)"

                                    />
                                </span>
                                <span v-else>
                                    {{props.column.label}}
                                </span>
                            </template>
                            <template slot="table-row" slot-scope="props">
                                <span v-if="props.column.field == 'status'">
                                    <toggle-button
                                        color="#8bc34a"
                                        :sync="true"
                                        :labels="true"
                                        v-model="props.row.status"
                                        @change="onChangeAccountStatus(props.row.id,$event)"

                                    />
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
        name: "BrandDealers",
        data: () => ({
            loading: true,
            headers:{
                heading:'Assign Brand Dealers'
            },
            brands:[],
            columns: [
                {
                    label: 'ID',
                    field: 'id',
                    type:'number',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Email',
                    field: 'email',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Company Name',
                    field: 'company_name',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Status',
                    field: 'status',
                    sortable:false,
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Sign Up Date',
                    field: 'created_at',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                }
            ],
            rows: [],
            activeBrand:null,
            brandId:'',
            tableCurrentPage:1,
            tableCurrentPerPage:50,
            brandAccessSelect:false
        }),
        async created() {

            this.brandId = this.$route.params.id;

            if(!this.resourceName){
                this.resourceName = 'Assign Brand Dealers';
            }
            this.breadcrumbs=[
                {url:'/brands',label:'Brands'},
                {url:'',label:this.resourceName},
            ];

            await Nova.request().get(`/nova-vendor/ProductBuilder/getAllDealers/`+this.brandId).then((response) => {
                this.rows = response.data.accounts;
            });

            await Nova.request().get(`/nova-vendor/ProductBuilder/getExclusiveBrands/1`).then((response) => {
                this.brands = response.data.brands;
            });

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
                this.brandAccessSelect = false;
            },
            selectAll(type,event){

                let first = this.getCurrentFirstIndex();
                let last = (this.currentPerPage + first) > this.rows.length? this.rows.length: this.currentPerPage + first;
                let active = event.value;
                let dealersToAlter = [];
                for(let i = first; i < last ; i++){
                    dealersToAlter.push(this.rows[i].id);
                    this.rows[i].status = event.value;
                }

                let formData = new FormData();
                formData.append('brand', this.brandId);
                formData.append('account_ids', dealersToAlter);
                formData.append('active', (active == true || active == 'true'? 1 : 0) );
                Nova.request().post('/nova-vendor/ProductBuilder/updateBrandAccountInBulk',formData)
                    .then((response) => {
                        if(response.data.success){
                            this.$toasted.show(
                                this.__('The :resource are Updated!',{
                                    resource: 'Brand Dealers',
                                }),
                                {type: 'success'}
                            );
                        }else{
                            this.$toasted.show(
                                this.__('Unable to Update :resource!',{
                                    resource: 'Brand Dealers',
                                }),
                                {type: 'error'}
                            );
                        }
                    }
                 );


            },
            async setActiveBrand(brandId){
                this.activeBrand = brandId;
                this.rows = [];
                await Nova.request().get(`/nova-vendor/ProductBuilder/getAllDealers/`+brandId).then((response) => {
                    this.rows = response.data.accounts;
                });

            },
            getBooleanStatus(status){
                if(status == 1){
                    return true;
                }else{
                    return false;
                }
            },
            onChangeAccountStatus(id, event){

                let active = event.value;
                let formData = new FormData();
                formData.append('brand_id', this.brandId);
                formData.append('account_id', id);
                formData.append('active', (active == true || active == 'true'? 1 : 0));
                Nova.request().post('/nova-vendor/ProductBuilder/updateAccountExclusiveBrand',formData)
                    .then((response) => {
                        this.$toasted.show(
                            this.__('Dealer Brands Updated!',{
                                resource: 'Dealer Brands',
                            }),
                            {type: 'success'}
                        );
                    });

            },
        },
        components: {
            VueGoodTable
        }
    }
</script>

<style scoped>

</style>

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
                                    <option value="" disabled="disabled" selected="selected">Choose Brand First</option>
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
                        >
                            <template slot="table-row" slot-scope="props">
                                <span v-if="props.column.field == 'status'">

                                    <span v-if="props.row.status == 1">Active</span>
                                    <span v-else="">In-Active</span>
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
                heading:'Brand Dealers'
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
                    label: 'Max Employees',
                    field: 'max_employees',
                    type:'number',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Max Companies',
                    field: 'max_companies',
                    type:'number',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Status',
                    field: 'status',
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
            brandId:''
        }),
        async created() {

            this.brandId = this.$route.params.id;

            if(!this.resourceName){
                this.resourceName = 'Brand Dealers';
            }
            this.breadcrumbs=[
                {url:'/brands',label:'Brands'},
                {url:'',label:this.resourceName},
            ];

            await Nova.request().get(`/nova-vendor/ProductBuilder/getBrands`).then((response) => {
                this.brands = response.data.brands;
            });

            await Nova.request().get(`/nova-vendor/ProductBuilder/getBrandAccounts/`+this.brandId).then((response) => {
                this.rows = response.data.accounts;
            });

            this.loading = false;
        },
        methods: {
            async setActiveBrand(brandId){
                this.activeBrand = brandId;
                this.rows = [];
                await Nova.request().get(`/nova-vendor/ProductBuilder/getBrandAccounts/`+brandId).then((response) => {
                    this.rows = response.data.accounts;
                });
            },
            getBooleanStatus(status){
                if(status == 1){
                    return true;
                }else{
                    return false;
                }
            }
        },
        components: {
            VueGoodTable
        }
    }
</script>

<style scoped>

</style>

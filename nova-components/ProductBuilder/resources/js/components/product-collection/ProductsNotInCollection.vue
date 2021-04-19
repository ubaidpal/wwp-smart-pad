<template>
    <div>
        <heading class="mb-3">{{__('Products Not in Collection')}}</heading>
        <bread-crumbs :breadcrumbs="breadcrumbs"></bread-crumbs>
        <div class="flex" style="">
            <div class="flex items-center mb-6">
                <div class="flex-no-shrink ml-auto">
                    <button class="btn btn-default btn-primary" @click="ShowCollection = true">Create Collection</button>
                </div>
            </div>
        </div>
        <add-collection v-if="ShowCollection" :BrandID="brandId" :ProductID="productToAddId" v-on:add="reloadProducts" v-on:close="closeAddProducttoCollectionModal" ></add-collection>
        <div class="card relative">
            <div class="overflow-hidden overflow-x-auto relative">
                <div class="text-center">
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8">
                            <label class="inline-block text-80 pt-2 leading-tight">
                                Choose Brands
                            </label></div>
                        <div class="py-6 px-8 w-1/2">
                            <!----> <select @change="setActiveBrand($event)"  data-testid="brands-select" dusk="brands" class="form-control form-select mb-3 w-full" v-model="brandId">
                            <option selected="selected" value="0">Choose Brand</option>
                            <option value="-1" >All Brands</option>
                            <option v-if="brands" v-for="brand in brands" :value="brand.id">{{brand.name}}</option>
                        </select>

                            <div class="help-text help-text mt-2">

                            </div>
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8">
                            <label class="inline-block text-80 pt-2 leading-tight">
                                Choose Category
                            </label></div>
                        <div class="py-6 px-8 w-1/2">
                            <!----> <select @change="setActiveCategory($event)"  data-testid="brands-select"dusk="category" class="form-control form-select mb-3 w-full" v-model="category_id">
                            <option value="0" selected="selected" >Choose Category</option>
                            <option value="-1" >All Categories</option>
                            <option v-if="categories" v-for="category in categories" :value="category.id">{{category.name}}</option>
                        </select>
                            <!---->
                            <!---->
                            <div class="help-text help-text mt-2">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="card relative">


        </div>
        </div>
        <div class="card relative">
            <div class="overflow-hidden overflow-x-auto relative">
                <div>
                    <vue-good-table
                        :columns="columns"
                        :rows="rows"
                        :pagination-options="{
                            enabled: true,
                            perPage: 50
                        }"
                        :search-options="{
                            enabled: true,
                            placeholder: 'Search this table',
                        }"
                    >
                        <template slot="table-row" slot-scope="props">
                            <span v-if="props.column.field == 'name'">
                                <router-link :to="{
                            name: 'ProductBuilder',
                            params: { product_id:props.row.id }
                        }" class="no-underline dim text-primary font-bold">{{props.row.name}}</router-link>
                            </span>
                            <span v-else-if="props.column.field == 'action'">
                                <button class="btn btn-default btn-primary" @click="showAddProducttoCollectionModal(props.row.id)">Add To Collection</button>
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
</template>
<script>
    import {VueGoodTable} from 'vue-good-table';
    import 'vue-good-table/dist/vue-good-table.css';
    import Multiselect from 'vue-multiselect';

    export default {
        name: "products-not-in-collection",
        data: () => ({
            breadcrumbs:[],
            ShowCollection:false,
            brands:[],
            categories:[],
            searchLoading: false,
            loading: true,
            brandId:0,
            category_id:0,
            productToAddId:0,
            columns: [
                {
                    label: 'ID',
                    field: 'id',
                    type: 'number',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Name',
                    field: 'name',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Brand',
                    field: 'brand',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Category',
                    field: 'category',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Action',
                    field: 'action',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                    sortable: false
                }
            ],
            rows: [],
        }),
        methods:{
            showAddProducttoCollectionModal(product_id){
                this.productToAddId = product_id;
                this.ShowCollection = true;
            },
            closeAddProducttoCollectionModal(){
                this.productToAddId = 0;
                this.ShowCollection = false;
            },
            setActiveBrand(event){
                this.brandId = event.target.value;
                this.getCategories(this.brandId);
                this.category_id = 0;
                this.reloadProducts();
            },
            async getCategories(brandId){
                await Nova.request().get(`/nova-vendor/ProductBuilder/brandCategories/`+brandId).then((response) =>  {
                    this.categories = response.data.categories;

                });
            },
            setActiveCategory(event){

                this.category_id = event.target.value;
                this.reloadProducts();

            },
            reloadProducts(){
                Nova.request().get(`/nova-vendor/ProductBuilder/inCollectionProducts/false/`+this.brandId+`/`+this.category_id).then((response) => {
                    this.rows = response.data.products;

                });
                this.productToAddId = 0;
            }
        },
        async created(){
            if(!this.resourceName){
                this.resourceName = 'Products Not in Collections';
            }
            this.breadcrumbs=[
                {url:'',label:this.resourceName},
            ];

            await axios.get(`/nova-vendor/ProductBuilder/getBrands`).then((response) => {
                this.brands = response.data.brands;
            });
            this.brandId = '-1';
            this.reloadProducts();
        },
        components: {
            VueGoodTable,
            Multiselect,
        },
    }

</script>
<style scoped>
    body {
        font-family: Menlo, Consolas, monospace;
        color: #444;
    }

</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

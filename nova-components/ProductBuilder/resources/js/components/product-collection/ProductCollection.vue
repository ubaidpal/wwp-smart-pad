<template>

    <div>
        <bread-crumbs :breadcrumbs="breadcrumbs"></bread-crumbs>
        <h1>Product Collection</h1>
        <br/>
        <!-- Listing of all the Brands and their collections -->
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
                            :sort-options="{
                                enabled: true,
                                initialSortBy: {field: 'name', type: 'asc'}
                              }"
                            :search-options="{
                                enabled: true,
                                placeholder: 'Search this table',
                            }"
                        >
                            <template slot="table-row" slot-scope="props">
                                <span v-if="props.column.field == 'name'">
                                    {{props.formattedRow[props.column.field]}}
                                </span>
                                <span v-else-if="props.column.field == 'tree'">
                                    <!-- Tree view for Brands > Collections > Products -->
                                        <ul>
                                            <product-collection-item
                                                :ref="`productCollectionItem_`+props.row.id"
                                                :id="props.row.id"
                                                class="item"
                                                :parent="true"
                                                :model="props.row"
                                                v-on:loadData="loadData"
                                                v-on:addCollection="showAddPopup"
                                                v-on:edit="editEvent"
                                                v-on:delete="deleteEvent"
                                                v-on:addProduct="addProduct"
                                            >
                                            </product-collection-item>
                                        </ul>
                                </span>
                                <span v-else>
                                    {{props.formattedRow[props.column.field]}}
                                </span>
                            </template>
                        </vue-good-table>
                    </div>
                </div>
            </div>
        <!-- Collection add or update Modal -->
        <modal v-if="showAddModal" @close="showAddModal = false">

            <div class="bg-white rounded-lg shadow-lg overflow-hidden"
                 style="width: 460px">
                <form v-on:submit.prevent="storeCollection(newCollection)">
                    <div class="p-8">
                        <heading :level="2" class="mb-6" v-if="!newCollection.id">Add New Collection for {{activeBrand.name }}</heading>
                        <heading :level="2" class="mb-6" v-else="">Update Collection for {{activeBrand.name }}</heading>
                    </div>
                    <input type="hidden" name='brand_id' v-model="newCollection.brand_id"/>
                    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                        <div class="mb-4">
                            <label class="block text-grey-darker text-sm font-bold mb-2" for="new_collection_name">
                                Name
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline" id="new_collection_name" type="text" placeholder="Enter Name" v-model="newCollection.name" required>
                        </div>

                    </div>

                    <div class="bg-30 px-6 py-3 flex">
                        <div class="ml-auto">
                            <button type="button" @click="showAddModal = false" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}</button>
                            <span v-show="importLoading"><loader class="mt-1 text-60"/></span>
                            <button v-show="!importLoading" class="btn btn-default btn-primary " v-if="!newCollection.id"
                            >{{__('Create')}}
                            </button>
                            <button v-show="!importLoading" class="btn btn-default btn-primary " v-else=""
                            >{{__('Update')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </modal>
        <!-- Add Product to Collection Modal -->
        <modal v-if="activeCollection" @close="activeCollection = null">

            <div class="bg-white rounded-lg shadow-lg overflow-visible"
                 style="width: 460px">
                <form @submit.prevent="addProductToCollection" >
                <div class="p-8">
                    <heading :level="2" class="mb-6">Add Products in <strong>{{activeCollection.name}} </strong></heading>
                </div>
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" >

                    <div v-if="options.length">
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-state">
                               Choose Products
                            </label>

                            <div class="relative">
                                <multiselect v-model="productToAddId"
                                             placeholder="Search or Select Product"
                                             label="name"
                                             track-by="id"
                                             :required="true"
                                             :options="options"
                                             :multiple="true"
                                             :preselect-first="false"
                                             :taggable="false"
                                             :close-on-select="false"
                                ></multiselect>
                            </div>
                        </div>
                        <div class="md:flex md:items-center">
                            <div class="md:w-1/3"></div>

                        </div>
                    </div>
                    <div v-else="">
                        <p>All Products of <strong>{{activeBrand.name}} are Taken.</strong></p>
                    </div>
                </div>

                <div class="bg-30 px-6 py-3 flex">

                    <div class="ml-auto">
                        <button type="button" @click="activeCollection = null" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Close')}}</button>
                        <input type="submit" class="btn btn-default btn-primary rounded" value="Add Product"/>
                    </div>
                </div>
                </form>
            </div>
        </modal>

    </div>

</template>
<script>
    import {VueGoodTable} from 'vue-good-table';
    import 'vue-good-table/dist/vue-good-table.css';
    import Multiselect from 'vue-multiselect';

    export default {
        name: "product-collection",
        data: () => ({
            //value: [],
            options: [],
            open: false,
            newCollection:{
                name:'',
                brand_id:'0',
                products:[]
            },
            brands:[],
            oldBrandId:0,
            showAddModal:false,
            activeBrand:null,
            activeCollection:null,
            rows:[],
            columns: [
                {
                    label: 'ID',
                    field: 'id',
                    type: 'number',
                    sortable: true,
                },
                {
                    label: 'Brand',
                    field: 'name',
                    sortable: true,
                },
                {
                    label: 'Brand > Collection(s) > Product(s)',
                    field: 'tree',
                    sortable: false,
                }

            ],
            productsToAddInDropDown:[],
            productToAddId:[],
            breadcrumbs:[]
        }),
        methods:{
            /*
            * Edit Event
            * Catch the Edit Event
            * */
            editEvent(event){
                if(event.products){ // If the Edit function is for Collection Edit
                    this.activeBrand = this.brands.find(x => x.id == event.brand_id);
                    this.newCollection = event;
                    this.showAddModal = true;
                }
            },
            /*
            * Delete Event
            * Catch the Delete Event
            * */
            deleteEvent(event){
                if(event.products && event.products.length == 0){ // Check if the event is for Collection Delete or Product Delete from a Collection
                    this.deleteCollection(event);
                }else if(!event.collections && !event.products){
                    this.deleteProductFromCollection(event);
                }
            },
            /*
            * Add Product to Collection Event
            * */
            addProduct(event){
              if(event.products){ // Make sure that the Event has the data of Collection
                  this.activeCollection = event;
                  this.activeBrand = this.brands.find(x => x.id == event.brand_id);
                  this.PopulateProductDropdown();
              }
            },
            async loadData(data)
            {
                this.brands[data.id] = data;
                // let brandIndex = this.brands.findIndex(elem => elem.id == data.id);
                // this.brands[brandIndex].collections = data.collections;
            },
            /*
            * It will show up the modal for adding a collection
            * @param Brand object
            * */
            showAddPopup(brand){
                this.activeBrand = brand;
                this.newCollection = {
                    name:'',
                    brand_id:brand.id,
                    products:[]
                };
                this.showAddModal = true;
            },
            /*
            * It will add a new Collection or edit the existing one
            * */
            async storeCollection(newCollection){
                const response = await Nova.request().post('/nova-vendor/ProductBuilder/storeCollection',
                    newCollection
                );

                if (response.data.success == true) {
                    let activeBrandIndex = response.data.collection.brand_id;
                    if(!newCollection.id) { // It is for the new Collection
                        // let activeBrandIndex = this.brands.findIndex(x => x.id == newCollection.brand_id);
                        this.$refs['productCollectionItem_'+activeBrandIndex].get(activeBrandIndex);
                        this.$toasted.show('Collection added Successfully!', { type: 'success' })
                    }else{  // For the Existing One
                        this.$refs['productCollectionItem_'+activeBrandIndex].get(activeBrandIndex);
                        // let activeBrandIndex = this.brands.findIndex(x => x.id == newCollection.brand_id);
                        // let collectionIndex = this.brands[activeBrandIndex].collections.findIndex(x => x.id == this.newCollection.id);
                        // if (collectionIndex != -1) {
                        //     this.brands[activeBrandIndex].collections[collectionIndex].name = response.data.collection.name;
                        // }
                        this.$toasted.show('Collection Updated Successfully!', { type: 'success' })
                    }
                    this.newCollection = {
                        name:'',
                        brand_id:'0',
                        brand:[],
                        products:[]
                    };
                    this.showAddModal = false;
                }else{
                    this.$toasted.show('Operation Failed!', { type: 'error' })
                }
            },
            /*
            * Show modal for Update Collection
            * */
            showUpdateModal(collection){
                this.showAddModal=true;
                this.newCollection=collection;
            },
            /*
            * Delete a collection
            * */
            async deleteCollection(collection){

                this.activeBrand = this.brands.find(x => x.id == collection.brand_id);
                let formData = new FormData();
                formData.append('collection_id', collection.id);
                const response = await Nova.request().post('/nova-vendor/ProductBuilder/deleteCollection',
                    formData
                );
                if(response.data.success){
                    // this.activeBrand.collections = this.activeBrand.collections.filter(function(value, index, arr){
                    //     return value.id != collection.id;
                    // });
                    this.$refs['productCollectionItem_'+this.activeBrand.id].get(this.activeBrand.id);
                    this.$toasted.show('Collection Deleted Successfully!', { type: 'success' })
                }else{
                    this.$toasted.show('Collection Deletion Failed!', { type: 'error' })
                }
            },
            /*
            * It will add up a product to a collection
            */
            async addProductToCollection(){
                let activeBrandIndex = this.activeCollection.brand_id;
                let activeCollectionIndex = this.brands[activeBrandIndex].collections.findIndex(x => x.id == this.activeCollection.id);
                if(activeCollectionIndex!= -1 && this.productToAddId != 0) {
                    let formData = new FormData();
                    var ProductIds = [];
                    for(let i=0; i< this.productToAddId.length; i++){
                        if(this.productToAddId[i].id > 0){
                            ProductIds.push(this.productToAddId[i].id)
                        }
                    }
                    formData.append('brand_id', activeBrandIndex);
                    formData.append('product_id', ProductIds);
                    formData.append('collection_id', this.brands[activeBrandIndex].collections[activeCollectionIndex].id);
                    const response = await Nova.request().post('/nova-vendor/ProductBuilder/addProductToCollection',
                        formData
                    );
                    if(response.data.success){
                        this.brands[activeBrandIndex].collections[activeCollectionIndex].products = [];
                        for(let i=0; i< response.data.product.length; i++){
                            this.brands[activeBrandIndex].collections[activeCollectionIndex].products.push(response.data.product[i]);
                        }
                        this.$toasted.show('Product addition to Collection Successfully!', { type: 'success' })
                    }else{
                        this.$toasted.show('Product addition to Collection Failed!', { type: 'error' })
                    }
                    this.activeCollection = null;
                    this.productToAddId = 0;
                }
            },
            /*
            * It will populate the dropdown in the add product to collection with the remaining products of that brand
            * */
            async PopulateProductDropdown(){
                if(this.activeCollection) {
                    this.productToAddId = [];
                    this.options = [];
                    await Nova.request().get(`/nova-vendor/ProductBuilder/productsToAdd?brand_id=`+this.activeCollection.brand_id).then((response) => {
                        for(let i=0; i< response.data.length; i++){
                            this.options.push({'name':response.data[i].name,'id':response.data[i].id});
                        }
                    });
                }
            },
            /*
            * It will delete the Product from a particular Collection
            * @param Product to add in Collection
            * */
            async deleteProductFromCollection(product){
                this.activeBrand = null;
                this.activeBrand = this.brands[product.brand_id];
                let collection = this.activeBrand.collections.find(x => x.id == product.collection_id);

                if(collection && product.id != 0) {
                    let formData = new FormData();
                    formData.append('product_id', product.id);
                    const response = await Nova.request().post('/nova-vendor/ProductBuilder/deleteProductFromCollection',
                        formData
                    );
                    if(response.data.success){
                        this.$refs['productCollectionItem_'+this.activeBrand.id].get(this.activeBrand.id);
                        // collection.products = collection.products.filter(element => element.id != product.id);
                        // let editedCollectionIndex = this.activeBrand.collections.findIndex(x => x.id == collection.id);
                        // this.activeBrand.collections[editedCollectionIndex] = collection;
                        this.$toasted.show('Product Deleted Collection Successfully!', { type: 'success' })
                    }

                }else{
                    this.$toasted.show('Product Deletion Failed!', { type: 'error' })
                }
            },
        },
        async created(){
            // Get all the brands with their collections and products
            await Nova.request().get(`/nova-vendor/ProductBuilder/brands`).then((response) => {
                this.rows = response.data;
                this.brands = response.data;
            });

            if(!this.resourceName){
                this.resourceName = 'Product Collections';
            }
            this.breadcrumbs=[
                {url:'',label:this.resourceName},
            ];
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
    .item {
        cursor: pointer;
    }
    .bold {
        font-weight: bold;
    }
    ul {
        padding-left: 1em;
        line-height: 1.5em;
        list-style-type: dot;
    }
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

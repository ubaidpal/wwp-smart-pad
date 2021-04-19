<template>
    <div class="vld-parent">
        <loading :active.sync="isLoading"
                 :can-cancel="false"
                 :on-cancel="onCancel"
                 :is-full-page="fullPage"></loading>
        <h1 class="mb-3 text-90 font-normal text-2xl">{{headers.heading}}</h1>
        <bread-crumbs :breadcrumbs="breadcrumbs"></bread-crumbs>
        <div class="flex" style="">
            <div class="flex items-center mb-6">
            <div class="flex-no-shrink ml-auto">
                <router-link :to="{ name: 'ProductBuilder'}" class="btn btn-default btn-primary">Create Product</router-link>
                <button class="btn btn-default btn-primary" @click="ShowCollection = true">Create Product Collection</button>
            </div>
        </div></div>

        <div class="card relative">
            <div class="overflow-hidden overflow-x-auto relative">
                <div class="text-center">
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8">
                            <label class="inline-block text-80 pt-2 leading-tight">
                                Brands
                            </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <select @change="getActiveBrand($event)"  data-testid="brands-select" dusk="brands" class="form-control form-select mb-3 w-full" v-model="brand_id">
                                <option value="0" selected="selected">Choose Brand First</option>
                                <option value="-1">All Brands</option>
                                <option v-if="brands" v-for="brand in brands" :value="brand.id">{{brand.name}}</option>
                            </select>
                        </div>
                        <div>
                            <div class="w-1/5 py-6 px-8">
                                 <span v-show="loading">
                                    <loader class="mt-1 text-60"/>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8">
                            <label class="inline-block text-80 pt-2 leading-tight">
                                Category
                            </label></div>
                        <div class="py-6 px-8 w-1/2">
                            <!----> <select @change="getActiveCategory($event)"  data-testid="brands-select"dusk="category" class="form-control form-select mb-3 w-full" v-model="category_id">
                                        <option value="0" selected="selected">Choose Category First</option>
                                        <option value="-1" >All Categories</option>
                                        <option v-if="categories" v-for="category in categories" :value="category.id">{{category.name}}</option>
                                    </select>

                            <div class="help-text help-text mt-2">

                            </div>
                        </div>
                        <div>
                            <div class="w-1/5 py-6 px-8">
                                 <span v-show="loading">
                                    <loader class="mt-1 text-60"/>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8">
                            <label class="inline-block text-80 pt-2 leading-tight">
                                Search
                            </label></div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="text" @change="onSearch()" v-model="search" placeholder="Search this table" class="w-full form-control form-input form-input-bordered">

                            <div class="help-text help-text mt-2">

                            </div>
                        </div>
                        <div>
                            <div class="w-1/5 py-6 px-8">
                                 <span v-show="loading">
                                    <loader class="mt-1 text-60"/>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div>

                    <vue-good-table
                        ref="my-table"
                        mode="remote"
                        :pagination-options="{enabled: true,perPage: 50}"
                        :totalRows="totalRecords"
                        @on-page-change="onPageChange"
                        @on-sort-change="onSortChange"
                        @on-column-filter="onColumnFilter"
                        @on-per-page-change="onPerPageChange"
                        :columns="columns"
                        :rows="rows"
                        :selectOptions="{
                            enabled: true,
                            selectOnCheckboxOnly: true,
                            selectionInfoClass: 'gt-checkbox-col',
                            selectionText: 'Product selected',
                            clearSelectionText: 'Clear Selection',
                          }"
                        styleClass="vgt-table bordered">
                            <template slot="table-row" slot-scope="props" style="text-align: center">
                                <span v-if="props.column.field == 'name'">
                                    <router-link :to="{
                                    name: 'ProductBuilder',
                                    params: { product_id:props.row.id,brand_id:brand_id,category_id:category_id }
                                }" class="no-underline dim text-primary font-bold">{{props.row.name}}</router-link>
                                </span>
                                <span v-else-if="props.column.field == 'brand'">
                                    {{props.row.brand}}
                                </span>
                                <span v-else-if="props.column.field == 'category'">
                                    {{props.row.category}}
                                </span>
                                <span v-else-if="props.column.field == 'is_active'">
                                    <toggle-button @change="onChangeEventHandler(props.row.id, props.row.is_active)"
                                               :value="props.row.is_active"
                                               v-model="props.row.is_active"
                                               color="#8bc34a"
                                               :sync="true"
                                               :labels="true"/>
                                </span>
                                <span v-else-if="props.column.field == 'action'">
                                    <span><router-link :to="{
                                name: 'ProductBuilder',
                                params: { product_id:props.row.id,brand_id:brand_id,category_id:category_id }
                            }" title="Edit Product" class="cursor-pointer text-70 hover:text-primary mr-3 spp-save"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current"><path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path></svg></router-link></span>

                                    <button
                                    title="Delete Product"
                                    :data-testid="`${props.row.id}-delete-button`"
                                    :dusk="`${props.row.id}-delete-button`"
                                    class="appearance-none cursor-pointer text-70 hover:text-primary mr-3 spp-delete"
                                    @click.prevent="openDeleteModal(props.row.id)"
                                    >
                                                    <icon />
                                                </button>
                                    <button v-on:click="cloneProduct(props.row)"  data-testid=""  title="Clone Product" class="appearance-none cursor-pointer text-70 hover:text-primary mr-3 spp-copy-clone"><svg class="fill-current text-teal inline-block" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path d="M6 6V2c0-1.1.9-2 2-2h10a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-4v4a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8c0-1.1.9-2 2-2h4zm2 0h4a2 2 0 0 1 2 2v4h4V2H8v4zM2 8v10h10V8H2zm4 4v-2h2v2h2v2H8v2H6v-2H4v-2h2z"/>
                                    </svg></button>
                                                
                                    <button v-if='!props.row.collection_id' @click="showAddProducttoCollectionModal(props.row.id)"  data-testid="" title="Add Product to Collection" class="appearance-none cursor-pointer text-70 hover:text-primary mr-3 spp-copy-clone"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" class="fill-current" ><path d="M15 9h-3v2h3v3h2v-3h3V9h-3V6h-2v3zM0 3h10v2H0V3zm0 8h10v2H0v-2zm0-4h10v2H0V7zm0 8h10v2H0v-2z"/></svg>
                                    </button>

                                </span>
                                <span v-else>
                                    {{props.formattedRow[props.column.field]}}
                                </span>
                            </template>
                    </vue-good-table>

                </div>
                <portal to="modals">
                    <transition name="fade">
                        <delete-resource-modal
                            v-if="deleteModalOpen"
                            @confirm="confirmDelete"
                            @close="closeDeleteModal"
                            :mode="viaManyToMany ? 'detach' : 'delete'"
                        >
                            <div slot-scope="{ uppercaseMode, mode }" class="p-8">
                                <heading :level="2" class="mb-6">{{ __(uppercaseMode+' Resource') }}</heading>
                                <p class="text-80 leading-normal">{{__('Are you sure you want to '+mode+' this resource?')}}</p>
                            </div>
                        </delete-resource-modal>
                    </transition>
                </portal>
            </div>
        </div>
        <product-category v-if="ShowCategory" v-on:close="ShowCategory = false" isFabric="0" is_active="1" v-on:categoryAdded="pushNewCategory"></product-category>
        <add-collection v-if="ShowCollection" :BrandID="brand_id" :ProductID="productToAddId" v-on:add="reloadProducts" v-on:close="closeAddProducttoCollectionModal" ></add-collection>

    </div>
</template>

<script>
  import {VueGoodTable} from 'vue-good-table';
  import 'vue-good-table/dist/vue-good-table.css';
  import Loading from 'vue-loading-overlay';
  import 'vue-loading-overlay/dist/vue-loading.css';
  const uuidv1 = require('uuid/v1');
  export default {
        name: "product-list",
          resourceName: {
              type: String,
          },
        data: () => ({
            ShowCollection:false,
            products:[],
            brands:[],
            categories:[],
            searchLoading: false,
            relationResponse: null,
            ShowBrand:false,
            ShowCategory:false,
            loading: false,
            isLoading:false,
            currentComponent:null,
            fields: [],
            brand_id:0,
            category_id:0,
            search: '',
            productToAddId:0,
            keyMap:[],
            newPriceGrids:[],
            newCalculations:[],
            parserOption:[],
            gridOption:[],
            grid: {
                data: [],
                colHeaders: [],
                columns: [],
                rowHeaders: true,
                manualColumnResize: true,
                height: 100, //this.grid.data.length * 26,
                stretchH: "all",
                readOnly: true,
            },
            headers:{
                heading:'Product Listing'
            },
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
                    sortable: false
                },
                {
                    label: 'Category',
                    field: 'category',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                    sortable: false
                },
                {
                    label: 'Active',
                    field: 'is_active',
                    type: 'number',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                    sortable: false
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
            deleteModalOpen: false,
            deleteResource:0,
            breadcrumbs:[],
            totalRecords: 0,
            serverParams: {
              columnFilters: {
              },
              sort: {
                field: 'name', 
                type: 'asc',
              },
              page: 1, 
              perPage: 50
            }
        }),
        components: {
          VueGoodTable,
            Loading,
            'foo': {
                template: '<product-brand @close="close"></product-brand>'
            },
        },
        methods: {
            updateParams(newProps) {
              this.serverParams = Object.assign({}, this.serverParams, newProps);
            },
            
            onPageChange(params) {
              this.updateParams({page: params.currentPage});
              this.loadItems();
            },

            onPerPageChange(params) {
              this.updateParams({perPage: params.currentPerPage});
              this.loadItems();
            },

            onSearch() {
              this.loadItems();
            },

            onSortChange(params) {
              this.updateParams({
                sort: {
                  type: params[0].type,
                  field: params[0].field
                },
              });
              this.loadItems();
            },
            
            onColumnFilter(params) {
              this.updateParams(params);
              this.loadItems();
            },

            // load items is what brings back the rows from server
            loadItems() {
                var params = 'page='+this.serverParams.page+'&perPage='+this.serverParams.perPage+'&sort_field='+this.serverParams.sort.field+'&sort_type='+this.serverParams.sort.type+'&search='+this.search;

                Nova.request().get(`/nova-vendor/ProductBuilder/products/`+this.brand_id+`/`+this.category_id+`?`+params).then((response) => {
                    this.totalRecords = response.data.products.totalRecords;
                    this.rows = response.data.products.data;
                });
            },

            showAddProducttoCollectionModal(product_id){
                this.productToAddId = product_id;
                this.ShowCollection = true;
            },
            closeAddProducttoCollectionModal(){
                this.productToAddId = 0;
                this.ShowCollection = false;
                this.loadItems();

            },
            openDeleteModal(id) {
                this.deleteResource = id;
                this.deleteModalOpen = true;
            },
            confirmDelete() { // confirm delete model
                let formData = new FormData();
                formData.append('resourceId', this.deleteResource);
                Nova.request().post('/nova-vendor/ProductBuilder/destroyProduct',formData);
                let deletedProduct = this.deleteResource;
                this.rows =  this.rows.filter(function(value, index, arr){
                    return value.id != deletedProduct;
                });
                this.closeDeleteModal();
            },

            closeDeleteModal() {
                this.deleteResource = 0;
                this.deleteModalOpen = false
            },
            getActiveBrand(event){ // Active Brand
                this.brand_id = event.target.value;
                this.loadItems();
            },
            getActiveCategory(event){ // ActiveCategory
                this.brand_id = this.brand_id,
                this.category_id = event.target.value;
                this.loadItems();

            },
            cloneProduct($obj){
                  this.isLoading = true;
                  Nova.request().get(`/nova-vendor/ProductBuilder/getCloneProduct/`+$obj.id).then((response) => {
                    
                    if(response.data.success){
                        this.gridOption = response.data.product;// save product in grid
                        this.parserOption = this.parseCloneData(response.data.product);// return parser of Option
                        let formData = new FormData();

                        formData.append('product_id', $obj.id);
                        formData.append('name', $obj.name + ' - Clone');
                        formData.append('brand_id', $obj.brand_id);
                        formData.append('category_id', $obj.category_id);
                        formData.append('country_id', $obj.country_id);
                        formData.append('is_active', $obj.is_active);

                        if(this.gridOption.price_grid){
                            formData.append('price_structure', this.gridOption.price_structure);
                            formData.append('gridOption', this.gridOption.price_grid);
                        }

                        formData.append('options', JSON.stringify(this.parserOption));


                        Nova.request().post('/nova-vendor/ProductBuilder/cloneProduct',formData).then((response) => {
                            if(response.data.success){

                                this.rows.push(
                                    {
                                        'id':response.data.productClone[0].id,
                                        'name':response.data.productClone[0].name,
                                        'brand':response.data.productClone[0].brand,
                                        'category':response.data.productClone[0].category ,
                                        'brand_id':response.data.productClone[0].brand_id ,
                                        'category_id':response.data.productClone[0].category_id ,
                                        'country_id':response.data.productClone[0].country_id,
                                        'is_active':response.data.productClone[0].is_active

                                    });
                                this.isLoading = false;
                                this.$toasted.show(
                                    this.__('The :Product Clone Successfully!',{
                                        resource: 'Product',
                                    }),
                                    {type: 'success'}
                                );
                            }

                        });
                    }
                });



            },
            // parser for clone options
            parseCloneData(obj){
                let productOption = JSON.parse(obj.options);
               if(productOption){
                   let newOptions = [],
                   newConstraints = [];
                   productOption.forEach((option)=>{
                       if(option.id){
                           let newOption = _.cloneDeep(option);
                           let new_id = this.getRandomId();
                           this.keyMap.push({
                               'old':option.id,
                               'new':new_id
                           });
                           newOption.id = new_id;

                           if(newOption.sub_options){

                               newOption.sub_options = this.cloneSubOptions(newOption ,new_id ,new_id);
                           }
                           
                          if(newOption.constraints){
                              newConstraints = [];
                              newOption.constraints.forEach(function (value ,key) {
                                  value.id = this.getRandomId();

                                  if(value.conditions && value.conditions.length){
                                      for(let index = 0; index < value.conditions.length; index++){
                                          value.conditions[index].id = this.getRandomId();
                                      }
                                      newConstraints.push(value);
                                  }

                              }.bind(this));
                              newOption.constraints = newConstraints;
                          }

                           newOptions.push(newOption);
                       }
                   });
                   newOptions = this.saperatePriceGrids(newOptions);
                   newOptions = this.saperateCalculations(newOptions);
                   newOptions = this.replaceOldWithNew(newOptions);
                   newOptions = this.injectPriceGrids(newOptions);
                   newOptions = this.injectCalculation(newOptions);
                   return newOptions;
               }
            },
            cloneSubOptions(option ,topParentId ,parentId){
               if(option.sub_options){
                   let subs = [];
                   option.sub_options.forEach((subOption)=>{
                       if (subOption.id) {
                           let sub_option_id = this.getRandomId();
                           let newSubOption = _.cloneDeep(subOption);
                           this.keyMap.push({
                               'old': subOption.id,
                               'new': sub_option_id
                           });
                           newSubOption.id = sub_option_id;
                           newSubOption.parent_id = parentId;
                           newSubOption.top_parent_id = topParentId;
                           if (newSubOption.sub_options) {
                               newSubOption.sub_options = this.cloneSubOptions(newSubOption, topParentId, sub_option_id);
                           }
                           subs.push(newSubOption);

                       }
                   });
                   return subs;

               }
            },
            /*
            * Saperate price grid from options
            * */
            saperatePriceGrids(newOptions){
                for(let i = 0; i< newOptions.length; i++){
                    if(newOptions[i].sub_options){
                        newOptions[i].sub_options = this.saperateSubPriceGrids(newOptions[i].sub_options);
                    }
                }
                return newOptions;
            },
            /*
            * Saperate Calculations from options
            * */
            saperateCalculations(newOptions){
                for(let i = 0; i< newOptions.length; i++){
                    if(!newOptions[i].calculation){
                        newOptions.calculation = "";
                    }
                    this.newCalculations.push({
                        option:newOptions[i].id,
                        calculation:newOptions[i].calculation
                    });
                    newOptions.calculation = "";
                }
                return newOptions;
            },
            saperateSubPriceGrids(subOptions){
                for(let j = 0; j < subOptions.length; j++){
                    this.newPriceGrids.push({
                        option:subOptions[j].id,
                        price_grid:subOptions[j].price_grid
                    });
                    subOptions[j].price_grid = "";
                    if(subOptions[j].sub_options){
                        subOptions[j].sub_options = this.saperatePriceGrids(subOptions[j].sub_options);
                    }
                }
                return subOptions;
            },
            /*
            * Inject Price Grid
            * */

            injectPriceGrids(newOptions){
                for(let i = 0; i< newOptions.length; i++){
                    if(newOptions[i].sub_options){
                        newOptions[i].sub_options = this.injectSubPriceGrids(newOptions[i].sub_options);
                    }
                }
                return newOptions;
            },
            injectSubPriceGrids(subOptions){
                for(let j = 0; j < subOptions.length; j++){
                    let newPriceGrid = this.newPriceGrids.find(elem => elem.option == subOptions[j].id);
                    if(newPriceGrid){
                        subOptions[j].price_grid = newPriceGrid.price_grid;
                    }
                    if(subOptions[j].sub_options){
                        subOptions[j].sub_options = this.injectSubPriceGrids(subOptions[j].sub_options);
                    }
                }
                return subOptions;
            },
            /*
            * Inject Price Grid
            * */

            injectCalculation(newOptions){
                for(let i = 0; i< newOptions.length; i++){
                    let newCalculation = this.newCalculations.find(elem => elem.option == newOptions[i].id);
                    if(newCalculation){
                        newOptions[i].calculation = newCalculation.calculation;
                    }
                }
                return newOptions;
            },
            /*
           * It will replace all the options with the new the new generated ids
           * */
            replaceOldWithNew(newOptions){
                let newOptionsString = JSON.stringify(newOptions);
                this.keyMap.forEach(function(value,key){
                    let newReplacedOptionsString = this.replaceAll(newOptionsString,value.old, value.new);
                    newOptionsString = newReplacedOptionsString;
                }.bind(this));
                return JSON.parse(newOptionsString);
            },
            replaceAll(string, search, replacement) {
                var re = new RegExp("'*\"*"+search+"'*\"*" , "g");
                return string.replace(re, '"'+replacement+'"');
                //return string.split(search).join(replacement);
            },
            //replace all the sub ids
            setLinkedSubIds(newOptions){
                for(let i = 0 ; i < newOptions.length; i++){
                    if(newOptions[i].type != 'group'){
                        let linkedKey = this.keyMap.find(elem => elem.new == newOptions[i].id);
                        if(linkedKey){
                            let linked = newOptions.filter(elems => elems.linked_sub_id == linkedKey['old']);
                            for(let sub_index = 0 ; sub_index < linked.length; sub_index++){
                                let op = newOptions.findIndex(elem => elem.id == linked[sub_index].id);
                                if(op > -1){
                                    newOptions[op].linked_sub_id = newOptions[i].id;
                                }
                            }
                        }
                    }
                }
                return newOptions;
            },

            replaceByValue(field, oldValue, newValue ,json) {
                for (var k = 0; k < json.length; ++k) {
                    if (oldValue == json[k][field]) {
                        json[k][field] = newValue;
                    }
                }
                return json;
            },

            getRandomId() {

                let length = 7;
                let chars = 'abcdefghijklmnopqrstuvwxyz';
                var result = '';
                for (var i = length; i > 0; --i) result += chars[Math.floor(Math.random() * chars.length)];
                return result;
            },
             chr4()
              {
                  return Math.random().toString(16).slice(-4);
              },
            /*showBrandComponent: function(component){
                this.currentComponent = component;
            },*/
            onChangeEventHandler(id,active){
                let formData = new FormData();
                formData.append('resourceId', id);
                formData.append('current_status', active? 1 : 0);
                Nova.request().post('/nova-vendor/ProductBuilder/updateProductStatus',formData);

                this.$toasted.show(
                    this.__('The :resource are Updated!',{
                        resource: 'Product',
                    }),
                    {type: 'success'}
                );
            },
            changeStatus(action){
                let doAction = true;
                if(action == 'delete'){
                    if(!confirm('Are you sure?')){
                        doAction = false;
                    }
                }
                if(doAction) {
                    let ids = [];
                    let selectedRows = this.$refs['my-table'].selectedRows;
                    selectedRows.forEach((value) => {
                        ids.push(value.id);
                    });
                    let formData = new FormData();
                    formData.append('action', action);
                    formData.append('ids', ids);
                    Nova.request().post('/nova-vendor/ProductBuilder/bulkStatus', formData).then((response) => {
                        if (response.data.success) {
                            this.$toasted.show(
                                this.__('Action Applied Successfully!', {
                                    resource: 'Product',
                                }),
                                {type: 'success'}
                            );
                        } else {
                            this.$toasted.show(
                                this.__('Action Failed!', {
                                    resource: 'Product',
                                }),
                                {type: 'error'}
                            );
                        }
                        this.loadItems();
                    });
                }
            },
            pushNewCategory(category){
                this.categories.push(category);
            },
        },
        async created(){
        /*  await axios.get(`/nova-vendor/ProductBuilder/products`).then((response) => {
              this.rows = response.data.products;
          });*/
            // Get Brand List
            Nova.request().get(`/nova-vendor/ProductBuilder/getBrandList`).then((response) => {
                this.brands = response.data.brand;
            });
            // Get Brand Category List
            Nova.request().get(`/nova-vendor/ProductBuilder/brandCategory`).then((response) => {
                this.categories = response.data.categories;
            });

            if(!this.resourceName){
                this.resourceName = 'products';
            }
            this.breadcrumbs=[
                {url:'',label:this.resourceName},
            ];

            if(this.$route.params.brand_id){
                this.brand_id = this.$route.params.brand_id;
                if(this.$route.params.category_id){
                    this.category_id = this.$route.params.category_id;
                }
                this.loadItems();
            }

        }
    }
</script>

<style scoped>

    table th input[type='checkbox']{
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 22px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
</style>

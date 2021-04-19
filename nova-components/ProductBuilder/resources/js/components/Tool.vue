<template>
        <div class="vld-parent">
            <loading :active.sync="isLoading"
                     :can-cancel="false"
                     :on-cancel="onCancel"
                     :is-full-page="fullPage"></loading>
            <bread-crumbs :breadcrumbs="breadcrumbs"></bread-crumbs>
            <heading class="mb-6">{{headers.heading}}</heading>


            <card class="overflow-hidden">
                <!--<form v-if="fields"  autocomplete="off" @submit.prevent="createProductResource">-->
                <div id="tabs" class="container">

                    <div class="tabs-component">

                        <ul role="tablist" class="tabs-component-tabs">
                            <li role="presentation" class="tabs-component-tab" v-on:click="changeTab(1)" v-bind:class="[ activetab === 1 ? 'is-active' : '' ]"><a aria-controls="#product-information" aria-selected="true" href="#product-information" role="tab" class="tabs-component-tab-a">Product Information</a></li>
                            <li role="presentation" class="tabs-component-tab" v-on:click="changeTab(2)" v-bind:class="[ activetab === 2 ? 'is-active' : '' ]"><a aria-controls="#price-grid" aria-selected="true" href="#price-grid" role="tab" class="tabs-component-tab-a">Price Grid</a></li>
                            <li role="presentation" class="tabs-component-tab" v-on:click="changeTab(3)" v-bind:class="[ activetab === 3 ? 'is-active' : '' ]"><a aria-controls="#product-order" aria-selected="true" href="#product-order" role="tab" class="tabs-component-tab-a">Product Order Options</a></li>
                        </ul>

                        <div class="tabs-component-panels">
                        <span class="">
                            <section id="product-information" role="tabpanel" class="tabs-component-panel" v-bind:style="[ activetab === 1 ? '' : {'display' : 'none'}]">

                                <form v-if="fields"  autocomplete="off" @submit.prevent="createProductResource">
                                    <!-- Validation Errors -->
                                    <validation-errors :errors="validationErrors"/>
                                   <div>
                                      <div class="flex border-b border-40">
                                         <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                                            Name
                                            </label>
                                         </div>
                                         <div class="py-6 px-8 w-1/2">
                                            <input id="name" v-model="activeProduct.name" type="text" placeholder="Name" class="w-full form-control form-input form-input-bordered"> <!---->
                                            <div class="help-text help-text mt-2">
                                            </div>
                                         </div>
                                      </div>
                                   </div>
                                   <div>
                                      <div class="flex border-b border-40">
                                         <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                                            Brand
                                            </label>
                                         </div>
                                         <div class="py-6 px-8 w-1/2">
                                            <!---->
                                               <model-list-select :list="brands"
                                                                  @input="getComment(activeProduct.brand_id)"
                                                                  v-model="activeProduct.brand_id"
                                                                  option-value="id"
                                                                  option-text="name"
                                                                  placeholder="Select a Brand">
                                                </model-list-select>


                                             <!----> <!---->
                                            <div class="help-text help-text mt-2">

                                            </div>
                                         </div>
                                         <div class="py-8 px-8 w-1/5">
<button type="button" @click="ShowBrand = true" class="btn btn-default btn-primary">{{__('Create Brand')}}</button>
                                         </div>

                                      </div>
                                   </div>
                                   <div>
                                      <div class="flex border-b border-40">
                                         <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                                            Category
                                            </label>
                                         </div>
                                         <div class="py-6 px-8 w-1/2">
                                            <!---->
                                              <model-list-select :list="categories"
                                                                 @input="overRiderBreadCrumbs()"
                                                                 v-model="activeProduct.category_id"
                                                                 option-value="id"
                                                                 option-text="name"
                                                                 placeholder="Select a Category">
                                             </model-list-select>

                                             <!----> <!---->
                                            <div class="help-text help-text mt-2">
                                            </div>
                                         </div>
                                          <div class="py-8 px-8 w-1/5">
<button type="button" @click="ShowCategory = true" class="btn btn-default btn-primary">{{__('Create Category')}}</button>
                                         </div>
                                      </div>
                                   </div>
                                   <div>
                                      <div class="flex border-b border-40">
                                         <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                                            Country
                                            </label>
                                         </div>
                                         <div class="py-6 px-8 w-1/2">
                                            <!---->
                                            <model-list-select :list="countries"
                                              v-model="activeProduct.country_id"
                                              option-value="id"
                                              option-text="name"
                                              placeholder="Select a Country"
                                              isDisabled="true"
                                            >
                                            </model-list-select>

                                             <!----> <!---->
                                            <div class="help-text help-text mt-2">
                                            </div>
                                         </div>
                                      </div>
                                   </div>
                                   <!--<div>-->
                                      <!--<div class="flex border-b border-40">-->
                                         <!--<div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">-->
                                            <!--Is Workroom-->
                                            <!--</label>-->
                                         <!--</div>-->
                                         <!--<div class="py-6 px-8 w-1/2">-->
                                            <!--<div class="flex items-center py-2" id="is_workroom" name="Is Workroom">-->
                                               <!--<div tabindex="0" role="checkbox" class="checkbox select-none rounded" aria-checked="true">-->
                                                  <!--<input v-model="activeProduct.is_workroom" type="checkbox" class="checkbox">-->

                                               <!--</div>-->
                                            <!--</div>-->
                                             <!--&lt;!&ndash;&ndash;&gt;-->
                                            <!--<div class="help-text help-text mt-2">-->
                                            <!--</div>-->
                                         <!--</div>-->
                                      <!--</div>-->
                                   <!--</div>-->
                                    <div>
                                        <div class="flex border-b border-40" via-resource="" via-resource-id="" via-relationship="">
                                            <div class="w-1/5 py-6 px-8">
                                                <label class="inline-block text-80 pt-2 leading-tight" for="Comments">Brand Comments</label>
                                            </div>
                                            <div class="py-6 px-8 w-4/5">
                                                <span v-show="searchLoading">
                                                    <loader class="mt-1 text-60"/>
                                                </span>
                                                <textarea v-show="!searchLoading" id="brand_comment" v-model="activeProduct.brand_comment" rows="5" class="w-full form-control form-input form-input-bordered py-3 h-auto">
                                                    {{activeProduct.brand_comment}}
                                                </textarea>
                                                <!---->
                                                <div class="help-text help-text mt-2">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex border-b border-40" via-resource="" via-resource-id="" via-relationship="">
                                            <div class="w-1/5 py-6 px-8">
                                                <label class="inline-block text-80 pt-2 leading-tight" for="product_notes">Product Notes</label>
                                            </div>
                                            <div class="py-6 px-8 w-4/5">
                                                <textarea id="product_notes" v-model="activeProduct.notes" rows="5" class="w-full form-control form-input form-input-bordered py-3 h-auto">
                                                    {{activeProduct.notes}}
                                                </textarea>
                                                <!---->
                                                <div class="help-text help-text mt-2">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                         <help-text class="text-center error-text mt-2 text-danger"
                                                    v-if="validationErrors.hasOwnProperty('errors') &&
                                                        validationErrors.errors.hasOwnProperty('row')">
                                                        {{validationErrors.errors.row[0]}}
                                         </help-text>
                                    </div>

                                    <div class="float-right" style="padding-top: 30px; padding-bottom: 10px;">
                                        <button @click="changePanel = true;" dusk="create-button" class="btn btn-default btn-primary" :disabled="isLoading">
                                            {{__('Price Grid >>')}}
                                        </button>
                                        <button @click="changePanel = false;" dusk="create-button" class="btn btn-default btn-primary bg-green" :disabled="isLoading">
                                            {{__('Save')}}
                                        </button>
                                    </div>

                                </form>

                            </section>
                        </span>
                            <span>
                            <section id="price-grid" role="tabpanel" class="tabs-component-panel" v-bind:style="[ activetab === 2 ? '' : {'display' : 'none'} ]">
                                <price-grid :productID="productID"></price-grid>
                            </section>
                        </span>
                            <span>
                            <section id="product-order" role="tabpanel" class="#" v-bind:style="[ activetab === 3 ? '' : {'display' : 'none'} ]">
                                <product-options :productID="productID"></product-options>
                            </section>
                        </span>
                        </div>
                    </div>
                </div>
                <!-- Validation Errors -->
                <validation-errors :errors="validationErrors"/>

                <div>
                    <help-text class="text-center error-text mt-2 text-danger"
                               v-if="validationErrors.hasOwnProperty('errors') &&
                               validationErrors.errors.hasOwnProperty('row')">
                        {{validationErrors.errors.row[0]}}
                    </help-text>

                </div>
            <!--</form>-->
        </card>
        <product-brand v-if="ShowBrand" v-on:close="ShowBrand = false" active="1" :country="activeCountry" isFabric="0" v-on:brandAdded="pushNewBrand"></product-brand>
        <product-category v-if="ShowCategory" v-on:close="ShowCategory = false" isFabric="0" status="1" v-on:categoryAdded="pushNewCategory"></product-category>
    </div>
    </loading-view>
</template>

<script>
    import {Errors, InteractsWithResourceInformation} from 'laravel-nova'
    import { ModelListSelect } from 'vue-search-select'
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';
    export default {
        mixins: [InteractsWithResourceInformation],

        props: {
            resourceName: {
                type: String,
                required: true,
            },
        },
        beforeRouteLeave (to, from, next) {
            const answer = window.confirm('Have You finished your product? Make sure to save your data before leaving.');
            if (answer) {
                next()
            } else {
                next(false)
            }
        },
        beforeRouteEnter(to,from,next){
            next();
        },
        data: () => ({
            loading: true,
            isLoading:true,
            searchLoading:false,
            relationResponse: null,
            changePanel: true,
            fields: [],
            validationErrors: new Errors(),
            activetab: 1,
            activeCountry:null,
            price_rows:0,
            price_cols:0,
            formOptions:[],
            productID:0,
            activeProduct:{
                id:0,
                name:'',
                brand_id:0,
                category_id:0,
                country_id:0,
                // is_workroom:0,
                brand_comment:'',
                notes:''
            },
            expanded: {},
            brands:[],
            categories:[],
            countries:[],
            headers:{
                heading:'Product Builder'
            },
            breadcrumbs:[],
            ShowBrand:false,
            ShowCategory:false,

        }),
        async created() {
            // If this create is via a relation index, then let's grab the field
            // and use the label for that as the one we use for the title and buttons
            this.breadcrumbs=[
                {url:'/products/'+this.$route.params.brand_id+'/'+this.$route.params.category_id,label:'products'},
            ];
            if(this.$route.params.product_id){
                this.productID = this.$route.params.product_id;
                Nova.request().get(`/nova-vendor/ProductBuilder/getProductInfo/`+this.productID).then((response) => {
                    this.headers.heading = response.data.product.name;
                    this.activeProduct = response.data.product;
                    this.activeProduct.brand_comment = response.data.product.brands.comment;
                    this.isLoading = false;
                });
                this.getProduct();
                this.breadcrumbs.push({url:'',label:'Edit Product'});
            } else {
                this.getFields();
                this.isLoading = false;
                this.breadcrumbs.push({url:'',label:'New Product'});

            }
            this.setBrands();
            this.setCategories();
            this.setCountries();
            this.loading = false;
        },
        methods: {
            pushNewBrand(brand){
                this.brands.push(brand);
            },
            pushNewCategory(category){
                this.categories.push(category);
            },
            /**
             * Get the available comments of selected brand.
             */
            async getComment(brand_id){

                this.searchLoading = true;
                Nova.request().get(`/nova-vendor/ProductBuilder/getBrandComment/`+brand_id).then((response) => {
                    this.activeProduct.brand_comment = response.data.comment;
                    this.searchLoading = false;
                    this.overRiderBreadCrumbs();
                });
            },
            /**
             * Get the available fields for the resource.
             */
            async getFields() {
                this.fields = [];

                const {data: fields} = await Nova.request().get(
                    `/nova-api/${this.resourceName}/creation-fields`
                );
                this.fields = fields
            },
            /**
             * Create a new resource instance using the provided data.
             */
            async createProductResource() {
                try {

                        this.isLoading = true;
                        const response = await this.createProductRequest();
                        this.productID = response.data.product_id;
                        if(response.data.requestdata)
                        this.headers.heading = response.data.requestdata.name;
                        else if(response.data.product){
                            this.headers.heading = response.data.product.name;
                        }else{
                            this.headers.heading = 'Product Builder';
                        }
                        this.$toasted.show(
                            this.__('The :resource are created!', {
                                resource: this.resourceInformation.singularLabel.toLowerCase(),
                            }),
                            {type: 'success'}
                        );
                    if(response.data.product){
                        this.isLoading = false;
                        this.$router.push('ProductBuilder/'+ response.data.product.id);
                    }

                    if(this.changePanel){
                        this.activetab = 2;
                    }
                    this.isLoading = false;

                } catch (error) {
                    if (error.response.status == 422) {
                        this.validationErrors = new Errors(error.response.data.errors)
                    }
                }
            },
            /**
             * Send a create request for this resource
             */
            createProductRequest() {

                if(this.$route.params.product_id){
                    return Nova.request().post(`/nova-vendor/ProductBuilder/updateProduct/`+this.$route.params.product_id,this.createResourceProductData())
                } else {
                    return Nova.request().post(`/nova-vendor/ProductBuilder`,this.createResourceProductData())
                }

            },
            /**
             * Create the form data for creating the resource.
             */
            createResourceProductData() {
                let fd = new FormData();
                fd.append('name',this.activeProduct.name);
                fd.append('brands',this.activeProduct.brand_id);
                fd.append('countries',this.activeProduct.country_id);
                fd.append('category',this.activeProduct.category_id);
                // fd.append('is_workroom',this.activeProduct.is_workroom?1:0);
                fd.append('brand_comment',this.activeProduct.brand_comment);
                fd.append('notes',this.activeProduct.notes);

                return fd;
            },
            async setBrands(){
                await axios.get(`/nova-vendor/ProductBuilder/getBrands`).then((response) => {
                    this.brands = response.data.brands;
                });

            },

            setCategories(){
                Nova.request().get(`/nova-vendor/ProductBuilder/activeCategories`).then((response) => {
                        this.categories = response.data.categories;
                        this.overRiderBreadCrumbs();
                    });

            },
            setCountries(){
                Nova.request().get(`/nova-vendor/ProductBuilder/countries`).then((response) => {
                    this.countries = response.data.countries;
                    this.activeCountry = this.getSelectedCountry();
                    this.activeProduct.country_id = this.activeCountry.id;
                });

            },
            /**/
            overRiderBreadCrumbs(){
                this.breadcrumbs=[
                    {url:'/products/'+this.activeProduct.brand_id+'/'+this.activeProduct.category_id,label:'products'},
                ];
            },
            changeTab: function(id) {
                this.activetab = id;
            },
            addOptionsRows: function(){
                this.formOptions.push({});
            },

            /*
             * Get the products from products_id.
             */
            async getProduct() {
                this.fields = [];

                let product_id = this.$route.params.product_id;
                const {data: fields} = await Nova.request().get(
                    `/nova-api/${this.resourceName}/`+product_id+`/update-fields`
                );
                this.fields = fields;
            },
            getSelectedCountry(){
                return this.countries.find(x => x.selected == 1 );
            }

        },

        mounted() {
            //
            Nova.$on('changeTabPanel', ({value}) => {
                this.activetab = value;
            })
        },
        components: {
            ModelListSelect,
            Loading
        }
    }
</script>

<style>
    .tabs-component-panels {
        padding: unset;
    }
</style>

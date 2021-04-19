<template>
    <loading-view :loading="loading">
        <bread-crumbs :breadcrumbs="breadcrumbs"></bread-crumbs>
        <heading class="mb-3">{{__('Item Builder')}}</heading>

        <card class="overflow-visible">
            <form v-if="fields" @submit.prevent="createResource" autocomplete="off">
                <!-- Validation Errors -->
                <validation-errors :errors="validationErrors"/>

                <div>
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            Brand (*)
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2" style="z-index:9999;">
                            <!---->
                            <model-list-select :list="brands"
                                               @input="getComment(brand_id)"
                                               v-model="brand_id"
                                               option-value="id"
                                               option-text="name"
                                               placeholder="Select a Brand">
                            </model-list-select>


                            <!----> <!---->
                            <div class="help-text help-text mt-2">
                                <help-text class="text-center error-text mt-2 text-danger"
                                           v-if="validationErrors.hasOwnProperty('errors') &&
                               validationErrors.errors.hasOwnProperty('brand_id')">
                                    {{validationErrors.errors.brand_id[0]}}
                                </help-text>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            Item Category (*)
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2" style="z-index:999;">
                            <!---->
                            <model-list-select
                                @input="setGrid()"
                                :list="categories"
                                               v-model="category_id"
                                               option-value="id"
                                               option-text="name"
                                               placeholder="Select a Category">
                            </model-list-select>

                            <!----> <!---->
                            <div class="help-text help-text mt-2">
                                <help-text class="text-center error-text mt-2 text-danger"
                                           v-if="validationErrors.hasOwnProperty('errors') &&
                               validationErrors.errors.hasOwnProperty('category_id')">
                                    {{validationErrors.errors.category_id[0]}}
                                </help-text>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="flex border-b border-40" via-resource="" via-resource-id="" via-relationship="">
                        <div class="w-1/5 py-6 px-8">
                            <label class="inline-block text-80 pt-2 leading-tight" for="Comments">Brand Version (*)</label>
                        </div>
                        <div class="py-6 px-8 w-4/5">
                                                <span v-show="searchLoading">
                                                    <loader class="mt-1 text-60"/>
                                                </span>
                            <input type="text" v-show="!searchLoading" v-model="brand_version" placeholder="Brand Version" rows="5" class="w-full form-control form-input form-input-bordered">
                            <!---->
                            <div class="help-text help-text mt-2">
                                <help-text class="text-center error-text mt-2 text-danger"
                                           v-if="validationErrors.hasOwnProperty('errors') &&
                               validationErrors.errors.hasOwnProperty('brand_version')">
                                    {{validationErrors.errors.brand_version[0]}}
                                </help-text>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex border-b border-40" via-resource="" via-resource-id="" via-relationship="">
                        <div class="w-1/5 py-6 px-8">
                            <label class="inline-block text-80 pt-2 leading-tight" for="Comments">Brand Comments</label>
                        </div>
                        <div class="py-6 px-8 w-4/5">
                                                <span v-show="searchLoading">
                                                    <loader class="mt-1 text-60"/>
                                                </span>
                            <textarea v-show="!searchLoading" id="brand_comment" v-model="brand_comment" rows="5" class="w-full form-control form-input form-input-bordered py-3 h-auto">
                                                    {{brand_comment}}
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




                    <div class="text-right mr-1" style="padding: 10px;">
                        <a href="javascript:void(0)" class="btn btn-default btn-primary" @click="showModal = true">Import
                            XLS</a>
                    </div>

                    <HotTable ref="wrapper" class="text-center" :settings="grid"></HotTable>
                </div>

                <!-- Create Button -->
                <div class="px-8 py-4 float-right">
                    <button v-show="!postingData" dusk="create-button" class="btn btn-default btn-primary">
                        {{__('Create')}}
                    </button>
                    <span v-show="postingData">
                        <loader class="mt-1 text-60"/>
                    </span>
                </div>
            </form>

            <modal v-if="showModal" @close="showModal = false" style="z-index: 9999">

                <div class="bg-white rounded-lg shadow-lg overflow-hidden"
                         style="width: 460px">
                        <div class="p-8">
                            <heading :level="2" class="mb-6">{{__('Import XLS File')}}</heading>
                        </div>
                        <div class="flex border-b border-40">
                            <div class="w-1/5 py-6 px-8">
                                <label class="inline-block text-80 pt-2 leading-tight" for="file">Attachment</label>
                            </div>
                            <div class="py-6 px-8 w-1/2">
                                <span class="form-file mr-4">
                                    <input type="file" id="file" name="file" @change="onFileChanged">
                                    <!--<label for="file" class="form-file-btn btn btn-default btn-primary">Choose File</label>-->
                                </span> <!---->
                            </div>
                        </div>
                        <div class="flex border-b border-40">
                            <div class="w-1/5 py-6 px-8">
                                <label class="inline-block text-80 pt-2 leading-tight" for="Notes">Notes</label>
                            </div>
                            <div class="py-6 px-8 w-4/5">
                                <textarea id="notes" dusk="notes" rows="5" class="w-full form-control form-input form-input-bordered py-3 h-auto"></textarea> <!---->
                            </div>
                        </div>

                        <div class="bg-30 px-6 py-3 flex">
                            <div class="ml-auto">
                                <button type="button" @click="showModal = false" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}
                                </button>

                                <button v-show="!importLoading" class="btn btn-default btn-primary"
                                        v-on:click="submitFile()">{{__('Done')}}
                                </button>
                                <span v-show="importLoading">
                                    <loader class="mt-1 text-60"/>
                                </span>
                            </div>
                        </div>
                    </div>
            </modal>

        </card>
    </loading-view>
</template>


<script>
    import {Errors, InteractsWithResourceInformation} from 'laravel-nova'
    import {HotTable} from '@handsontable/vue'
    import {ModelListSelect} from 'vue-search-select'

    let initRows = [];
    for (let i = 0; i < 15; i++) {
        initRows.push(['', '', '']);
    }

    export default {
        mixins: [InteractsWithResourceInformation],

        props: {
            resourceName: {
                type: String,
                required: true,
            }
        },

        data: () => ({
            showModal: false,
            importLoading: false,
            relationResponse: null,
            loading: true,
            fields: [],
            validationErrors: new Errors(),
            grid: {
                data: initRows,
                colHeaders: [
                    'Fabric Name',
                    'Price',
                    'Active'
                ],
                columns: [
                    {},
                    {
                        validator: 'numeric'
                    },
                    {
                        type: 'dropdown',
                        source: ['Active', 'In-active']
                    },
                ],
                rowHeaders: true,
                contextMenu: ['row_above', 'row_below', 'remove_row'],
                manualColumnResize: true,
                // colWidths: [200,200,200]
                height: 400,
                stretchH: "all"
            },
            showGrid: true,
            breadcrumbs:[],
            brands:[],
            categories:[
                {
                    id:1,
                    name:'Fabric'
                },
                {
                    id:2,
                    name:'Trim'
                },
                {
                    id:3,
                    name:'Accessories'
                }
            ],
            brand_id:'',
            category_id:'',
            searchLoading:false,
            brand_comment:'',
            postingData:false,
            brand_version:'',
        }),


        async created() {
            // If this create is via a relation index, then let's grab the field
            // and use the label for that as the one we use for the title and buttons

            this.getFields();


            this.breadcrumbs=[
                {url:'/items',label:'Items'},
                {url:'',label: 'Item Builder'},
            ];

            this.initSet();
        },

        methods: {
            /**
             * Get the available fields for the resource.
             */
            async getFields() {
                this.fields = [];

                const {data: fields} = await Nova.request().get(
                    `/nova-api/${this.resourceName}/creation-fields`
                );

                this.fields = fields;
                this.loading = false;
                this.setBrands();
                //this.setCategories();
            },

            /**
             * Create a new resource instance using the provided data.
             */
            async createResource() {
                try {
                    this.postingData = true;
                    const response = await this.createRequest();
                        this.$toasted.show(
                            this.__('The :resource are created!', {
                                resource: this.resourceInformation.singularLabel.toLowerCase(),
                            }),
                            {type: 'success'}
                        );
                        this.grid.data = initRows;
                        this.postingData = false;
                        this.$router.push({
                            name: 'ItemBuilderBrowse'
                        })
                    

                } catch (error) {
                    if (error.response.status == 422) {
                        this.validationErrors = new Errors(error.response.data.errors)
                        console.log(this.validationErrors);
                    }
                }
            },

            /**
             * Send a create request for this resource
             */
            createRequest() {

                return Nova.request().post(
                    `/nova-vendor/ItemBuilder`,
                    this.createResourceFormData()
                )
            },

            /**
             * Create the form data for creating the resource.
             */
            createResourceFormData() {
                const gridData = [];

                for (let dataItem of this.grid.data) {

                    //
                    let dataObj = {};
                    if(this.category_id == 1){
                        _.merge(dataObj, {Fabric_Name: '', Fabric_Width: '', Fabric_Repeat: '', Price: '', Active: ''});
                    } else {
                        _.merge(dataObj, {Fabric_Name: '', Price: '', Active: ''});
                    }

                    let empty = true;

                    let i = 0;
                    for (let attribute in dataObj) {
                        if (dataObj.hasOwnProperty(attribute)) {

                            if (dataItem[i] !== 'undefined' && dataItem[i] !== undefined && dataItem[i] !== "") {
                                dataObj[attribute] = dataItem[i];
                                empty = false;
                            }
                            i++;
                        }
                    }

                    if (!empty) {
                        dataObj.Active = dataObj.Active == 'Active' ? 1 : 0;
                        gridData.push(dataObj);
                    }
                }

                let formData = new FormData();
                formData.append('row', JSON.stringify(gridData));
                formData.append('brand_id', this.brand_id);
                formData.append('category_id', this.category_id);
                formData.append('brand_comment', this.brand_comment);
                formData.append('brand_version', this.brand_version);

                return formData;
            },
            onFileChanged (event) {
                this.selectedFile = event.target.files[0]
            },

            async submitFile() {

                try {

                    this.importLoading = true;

                    let formData = new FormData();
                    formData.append('file', this.selectedFile);

                    const response = await Nova.request().post('/nova-vendor/ItemBuilder/excelToJson',
                        formData, {headers: {'Content-Type': 'multipart/form-data'}}
                    );

                    if (response.data.data.length > 0) {

                        this.grid.data = [];
                        for (let dataItem of response.data.data) {

                            if(this.category_id == 1){
                                this.grid.data.push([dataItem['FABRIC NAME'] ? dataItem['FABRIC NAME'] : dataItem['FABRIC'],dataItem['FABRIC WIDTH'] ? dataItem['FABRIC WIDTH'] : dataItem['WIDTH'],dataItem['REPEAT PATTERN'] ? dataItem['REPEAT PATTERN'] : dataItem['PATTERN REPEAT'], dataItem['PRICE'], 'Active']);
                            } else {
                                this.grid.data.push([dataItem['Name'], dataItem['PRICE'],'Active']);
                            }
                        }

                        this.grid.height = 400;
                        this.importLoading = false;
                        this.showGrid = true;
                        this.showModal = false;
                        this.$toasted.show(
                            this.__(':resource are loaded!', {
                                resource: 'Items',
                            }),
                            {type: 'success'}
                        )
                    } else {
                        this.$toasted.show(
                            this.__('No :resource were found', {
                                resource: 'Items',
                            }),
                            {type: 'success'}
                        )
                    }

                } catch (error) {
                    console.log('>>>>>' + error);
                }
                
            },
            /**
             * Get the available brands of fabric.
             */
            async setBrands(){
                await Nova.request().get(`/nova-vendor/ItemBuilder/getBrands`).then((response) => {
                    this.brands = response.data.brands;
                });

            },
            /**
             * Get the available categories.
             */
            // setCategories(){
            //     Nova.request().get(`/nova-vendor/ItemBuilder/activeCategories`).then((response) => {
            //         this.categories = response.data.categories;
            //     });
            //
            // },
            /**
             * Get the available comments of selected brand.
             */
            async getComment(brand_id){

                this.searchLoading = true;
                Nova.request().get(`/nova-vendor/ItemBuilder/getBrandComment/`+brand_id).then((response) => {
                    this.brand_comment = response.data.comment;
                    this.searchLoading = false;
                });
            },
            setGrid(){

                this.initSet();

                if(this.category_id == 1){
                    this.showGrid = false;
                    this.grid.colHeaders = [];
                    this.grid.colHeaders.push('Fabric Name','Fabric Width','Fabric Repeat', 'Price', 'Active');
                    this.grid.columns = [];
                    this.grid.columns.push({}, {validator: 'numeric'}, {validator: 'numeric'}, {}, {type: 'dropdown', source: ['Active', 'In-active']},);
                    this.showGrid = true;
                } else {
                    this.showGrid = false;
                    this.grid.colHeaders = [];
                    this.grid.colHeaders.push('Fabric Name', 'Price', 'Active');
                    this.grid.columns = [];
                    this.grid.columns.push({}, {validator: 'numeric'}, {type: 'dropdown', source: ['Active', 'In-active']},);
                    this.showGrid = true;
                }
                //console.log(this.category_id);

            },
            initSet(){
                let initSet = [];
                for (let i = 0; i < 15; i++) {
                    initSet.push(['', '', '']);
                }

                this.grid.data = initSet;
                this.grid.height = 15 * 26;
            }
        },

        computed: {
            singularName() {
                if (this.relationResponse) {
                    return this.relationResponse.singularLabel
                }

                return this.resourceInformation.singularLabel
            }
        },
        components: {
            HotTable,
            ModelListSelect
        },

    }
</script>
<style src="../../../node_modules/handsontable/dist/handsontable.full.css"></style>

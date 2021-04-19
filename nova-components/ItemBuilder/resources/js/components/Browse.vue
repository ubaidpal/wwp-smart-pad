<template>
    <loading-view :loading="loading">
        <heading class="mb-3">{{__('Item Browser')}}</heading>
        <bread-crumbs :breadcrumbs="breadcrumbs"></bread-crumbs>
        <div class="flex" style="">
            <div class="flex items-center mb-6">
                <div class="flex-no-shrink ml-auto">
                    <router-link :to="{
                            name: 'ItemBuilder',
                            params: {
                                resourceName: 'ItemBuilder'
                            }
                        }" class="btn btn-default btn-primary">Create Item</router-link>
                </div>
            </div>
        </div>

        <card class="overflow-visible">
            <form v-if="fields" @submit.prevent="updateResource" autocomplete="off">
                <!-- Validation Errors -->
                <validation-errors :errors="validationErrors"/>

                <div>
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            Brand (*)
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
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
                            Brand Versions (*)
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <!---->
                            <model-list-select :list="versions"
                                               v-model="version_id"
                                               option-value="id"
                                               option-text="version"
                                               placeholder="Select a Versions">
                            </model-list-select>


                            <!----> <!---->
                            <div class="help-text help-text mt-2">
                                <help-text class="text-center error-text mt-2 text-danger"
                                           v-if="validationErrors.hasOwnProperty('errors') &&
                               validationErrors.errors.hasOwnProperty('version_id')">
                                    {{validationErrors.errors.version_id[0]}}
                                </help-text>
                            </div>
                        </div>
                        <div class="py-8 px-8 w-1/5">
                            <button type="button" v-show="version_id > 0" @click="showEditModal = true" class="btn btn-default btn-primary">Edit</button>
                            <button type="button" v-show="version_id > 0" @click="showDeleteModal = true" class="btn btn-default btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            Item Category (*)
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <!----> 
                            <model-list-select
                                @input="search()"
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

                <div v-show="grid.readOnly">
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8">
                            <label class="inline-block text-80 pt-2 leading-tight">Item Search</label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input id="name" v-model="keyword" type="text" placeholder="Name" class="w-full form-control form-input form-input-bordered">
                            <div class="help-text help-text mt-2"></div>
                        </div>
                        <div class="py-8 px-8 w-1/5">
                            <button type="button" @click="searchContent()" class="btn btn-default btn-primary">Search</button>
                            <button type="button" @click="resetFilter()" class="btn btn-default btn-primary">Reset</button>
                        </div>
                    </div>
                </div>

                <div v-show="!grid.readOnly">
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

                <!-- Create Button -->
                <div v-show="showGrid" class="text-right">

                    <label class="mr-4">
                        <input checked v-on:click="toggleReadOnly()" ref="readOnlyCheckbox" class="mr-2 leading-tight" type="checkbox">
                        <span class="text-sm">Read Only</span>
                    </label>
                </div>


                <div v-show="showGrid">
                    <HotTable ref="grid" class="text-center" :settings="grid"></HotTable>
                </div>

                <!-- Update Button -->
                <div class="flex" style="">
                    <div class="w-full flex items-center mb-6"><div class="flex w-full justify-end items-center mx-3"></div>
                        <div class="flex-no-shrink ml-auto">
                             <span v-show="postingData">
                                    <loader class="mt-1 text-60"/>
                                </span>
                            <button type="button" v-show="!grid.readOnly" @click="showModal = true" dusk="update-button" class="btn btn-default btn-primary">
                                {{__('Import XLS')}}
                            </button>

                            <button v-show="!grid.readOnly" dusk="update-button" class="btn btn-default btn-primary">
                                {{__('Update')}}
                            </button>


                            <!--<a v-on:click="search()" href="javascript:void(0)" dusk="search-button" style="margin-right: 15px; margin-top: 15px;"-->
                               <!--class="btn btn-default btn-primary">-->
                                <!--<span v-show="!searchLoading">{{__('Search')}}</span>-->
                                <!---->
                            <!--</a>-->
                            <span v-show="searchLoading">
                                    <loader class="mt-1 text-60"/>
                                </span>
                        </div>
                    </div>
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

                    <div class="bg-30 px-6 py-3 flex float-right">
                        <div class="ml-auto">
                            <button type="button" @click="showModal = false" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}
                            </button>
                            <button v-show="!importLoading" class="btn btn-default btn-primary" v-on:click="submitFile()">{{__('Done')}}</button>
                            <span v-show="importLoading">
                                    <loader class="mt-1 text-60"/>
                                </span>
                        </div>
                    </div>
                </div>
            </modal>

            <modal v-if="showEditModal" @close="showEditModal = false">

                <div class="bg-white rounded-lg shadow-lg overflow-visible" style="width: 460px">
                    <div class="p-8">
                        <heading :level="2" class="mb-6">{{__('Update Brand Versions')}}</heading>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8">
                            <label class="inline-block text-80 pt-2 leading-tight" for="file">Name</label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <div>
                                <input id="version_name" v-model="version_name" type="text" placeholder="Name" class="w-full form-control form-input form-input-bordered">
                                <div class="help-text help-text mt-2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-30 px-6 py-3 flex">
                        <div class="ml-auto">
                            <button type="button" @click="showEditModal = false" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}
                            </button>
                            <button v-show="!importLoading" class="btn btn-default btn-primary" v-on:click="updateBrandVersion()">{{__('Update')}}</button>
                            <span v-show="importLoading">
                                    <loader class="mt-1 text-60"/>
                                </span>
                        </div>
                    </div>
                </div>
            </modal>

            <modal v-if="showDeleteModal" @close="showDeleteModal = false">

                <div class="bg-white rounded-lg shadow-lg overflow-visible" style="width: 460px">
                    <div class="p-8">
                        <heading :level="2" class="mb-6">{{__('Delete Brand Version')}}</heading>
                        <p class="text-80 leading-normal">Are you sure you want to delete this resource?</p>
                    </div>
                    <div class="bg-30 px-6 py-3 flex">
                        <div class="ml-auto">
                            <button type="button" @click="showDeleteModal = false" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}
                            </button>
                            <button v-show="!searchLoading" class="btn btn-default btn-danger" v-on:click="deleteBrandVersion()">{{__('Delete')}}</button>
                            <span v-show="searchLoading">
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


    let totalRecords = [];
    const idsToDelete = [];
    const appendIdsToDelete = (indexes) => {
        for (const index of indexes) {
            idsToDelete.push(totalRecords[index]);
        }
        for (const index of indexes) {
            totalRecords.splice(index, 1);
        }
    };

    const mergeInTotalRecords = (index) => {
        totalRecords.splice(index, 0, -1);
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
            showEditModal:false,
            showDeleteModal:false,
            showModal: false,
            searchLoading: false,
            relationResponse: null,
            loading: true,
            fields: [],
            validationErrors: new Errors(),
            idsToDelete: [],
            version_name:'',
            grid: {
                data: [],
                colHeaders: [
                    'ID',
                    'Fabric',
                    'Price',
                    'Active'
                ],
                columns: [
                    {readOnly: true},
                    {
                        required: true
                    },
                    {
                        required: true,
                        validator: 'numeric'
                    },
                    {
                        required: true,
                        type: 'dropdown',
                        source: ['Active', 'In-active']
                    },
                ],
                rowHeaders: true,
                manualColumnResize: true,
                // colWidths: [200,200,200]
                height: 100, //this.grid.data.length * 26,
                stretchH: "all",
                readOnly: true,
                beforeRemoveRow: (index, amount, visualRows) => {
                    appendIdsToDelete(visualRows);
                },
                beforeCreateRow: (index, amount, source) => {
                    mergeInTotalRecords(index);
                }
            },
            showGrid: false,
            breadcrumbs:[],
            brands:[],
            versions:[],
            version_id:0,
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
                },
            ],
            brand_id:'',
            category_id:'',
            searchLoading:false,
            brand_comment:'',
            postingData:false,
            keyword:'',
            beforeFilterData:[],
        }),


        async created() {
            this.getFields();
            if(!this.resourceName){
                this.resourceName = 'items';
            }
            this.breadcrumbs=[
                {url:'',label:this.resourceName},
            ];
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

            toggleReadOnly() {
                if (this.grid.readOnly) {
                    this.grid.readOnly = false;
                    this.grid.contextMenu = ['row_above', 'row_below', 'remove_row'];
                    this.$refs.readOnlyCheckbox.checked = false;
                } else {
                    this.grid.readOnly = true;
                    this.grid.contextMenu = [];
                    this.$refs.readOnlyCheckbox.checked = true
                }

                this.$refs.grid.hotInstance.updateSettings(this.grid);
            },


            /**
             * Update items using the provided data.
             */
            async updateResource() {
                try {
                    this.postingData = true;
                    const response = await this.updateRequest();

                    this.$toasted.show(
                        this.__('The :resource are updated!', {
                            resource: 'Items',
                        }),
                        {type: 'success'}
                    );
                    this.postingData = false;
                    this.toggleReadOnly();


                } catch (error) {
                    if (error.response.status == 422) {

                        this.validationErrors = new Errors(error.response.data.errors);
                        let errorMessage = '';
                        for (let err in this.validationErrors.errors) {
                            if (this.validationErrors.errors.hasOwnProperty(err)) {
                                errorMessage = this.validationErrors.errors[err][0];
                                break;
                            }
                        }

                        this.$toasted.show(
                            errorMessage,
                            {type: 'error'}
                        );

                    }
                }
            },


            /**
             * Send a update request for this resource
             */
            updateRequest() {

                return Nova.request().post(
                    `/nova-vendor/ItemBuilder/update`,
                    this.updateResourceFormData()
                )
            },

            /**
             * Create the form data for updating the resource.
             */
            updateResourceFormData() {
                const gridData = [];


                for (const [index, dataItem] of this.grid.data.entries()) {
                    //
                    let dataObj = {};
                    if(this.category_id == 1){
                        _.merge(dataObj, {'ID':'',Fabric_Name: '', Fabric_Width: '', Fabric_Repeat: '', Price: '', Active: ''});
                    } else {
                        _.merge(dataObj, {'ID':'',Fabric_Name: '', Price: '', Active: ''});
                    }

                    let empty = true;

                    let i = 0;
                    for (let attribute in dataObj) {
                        if (dataObj.hasOwnProperty(attribute)) {
                            if (dataItem[i] !== 'undefined' && dataItem[i] !== undefined && dataItem[i] !== '') {
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
                formData.append('version_id', this.version_id);
                formData.append('category_id', this.category_id);
                formData.append('brand_comment', this.brand_comment);
                formData.append('idsToDelete', JSON.stringify(idsToDelete));
                return formData;

            },


            searchResourceFromData() {
                let formData = new FormData();
                formData.append('brand_id', this.brand_id);
                formData.append('category_id', this.category_id);
                formData.append('version_id', this.version_id);

                return formData;
            },
            async search() {

                this.beforeFilterData = [];
                this.keyword = '';
                this.searchLoading = true;
                try {
                    const response = await Nova.request().post(
                        `/nova-vendor/ItemBuilder/browse`,
                        this.searchResourceFromData()
                    );

                    this.setGrid();
                    this.grid.data = [];
                    this.searchLoading = false;

                    if (response.data.body.data.length > 0) {
                        this.grid.data = [];
                        totalRecords = [];
                        for (const item of response.data.body.data) {

                            if(this.category_id == 1){
                                this.grid.data.push([item.id, item.name,item.fabric_width,item.fabric_repeat	, item.price, item.active == 1 ? 'Active' : 'In-active']);
                            } else {
                                this.grid.data.push([item.id, item.name, item.price, item.active == 1 ? 'Active' : 'In-active']);
                            }

                            totalRecords.push(item.id);
                        }

                        this.grid.height = 400;

                        this.showGrid = true;
                        this.$toasted.show(
                            this.__(':resource are loaded!', {
                                resource: 'Items',
                            }),
                            {type: 'success'}
                        )
                    } else {
                        this.grid.height = 40;
                        this.$toasted.show(
                            this.__('No :resource were found', {
                                resource: 'Items',
                            }),
                            {type: 'success'}
                        )
                    }


                } catch (error) {
                    if (error.response.status == 422) {
                        this.validationErrors = new Errors(error.response.data.errors);

                        console.log(this.validationErrors);
                    }
                }

            },
            onFileChanged (event) {
                this.selectedFile = event.target.files[0]
            },
            async submitFile(){

                try {

                    this.importLoading = true;

                    let formData = new FormData();
                    formData.append('file', this.selectedFile);

                    const response = await Nova.request().post('/nova-vendor/ItemBuilder/excelToJson',
                        formData, { headers: {'Content-Type': 'multipart/form-data'} }
                    );
                   
                    if (response.data.data.length > 0) {

                        for (let dataItem of response.data.data) {

                            if(this.category_id == 1){
                                this.grid.data.push(['',dataItem['FABRIC'],dataItem['WIDTH'],dataItem['REPEAT PATTERN'] ? dataItem['REPEAT PATTERN'] : dataItem['PATTERN REPEAT'], dataItem['PRICE'], 'Active']);
                            } else {
                                this.grid.data.push(['',dataItem['Name'], dataItem['PRICE'],'Active']);
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
                    console.log('>>>>>'+error);
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
            async getVersion(brand_id){

                await Nova.request().get(`/nova-vendor/ItemBuilder/getVersion/`+brand_id).then((response) => {
                    this.versions = response.data.versions;
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
                this.version_id =0;
                this.getVersion(brand_id);
                this.searchLoading = true;
                Nova.request().get(`/nova-vendor/ItemBuilder/getBrandComment/`+brand_id).then((response) => {
                    this.brand_comment = response.data.comment;
                    this.searchLoading = false;
                });
            },
            setGrid(){

                if(this.category_id == 1){
                    this.showGrid = false;
                    this.grid.colHeaders = [];
                    this.grid.colHeaders.push('ID','Fabric Name','Fabric Width','Fabric Repeat', 'Price', 'Active');
                    this.grid.columns = [];
                    this.grid.columns.push({validator: 'numeric'},{}, {validator: 'numeric'}, {}, {validator: 'numeric'}, {type: 'dropdown', source: ['Active', 'In-active']},);
                    this.showGrid = true;
                } else {
                    this.showGrid = false;
                    this.grid.colHeaders = [];
                    this.grid.colHeaders.push('ID','Fabric Name', 'Price', 'Active');
                    this.grid.columns = [];
                    this.grid.columns.push({validator: 'numeric'},{}, {validator: 'numeric'}, {type: 'dropdown', source: ['Active', 'In-active']},);
                    this.showGrid = true;
                }

            },
            searchContent(){
                let text = this.keyword;
                if(text != null){
                    let data;
                    if(this.beforeFilterData.length > 0){
                        data = this.beforeFilterData;
                    } else {
                        this.beforeFilterData = this.grid.data;
                        data = this.grid.data;
                    }
                    this.grid.data = data.filter(function (item) {
                        return item['1'].toLowerCase().match(text.toLowerCase());
                    });
                }
            },
            resetFilter(){
                this.keyword = '';
                this.grid.data = this.beforeFilterData;
                this.beforeFilterData = [];
            },
            async updateBrandVersion(){

                try {

                    this.searchLoading = true;
                    const response = await Nova.request().post(
                        `/nova-vendor/ItemBuilder/updateVersion`,
                        this.versionFromData()
                    );
                    this.getVersion(this.brand_id);
                    this.searchLoading = false;
                    this.showEditModal = false;
                    this.$toasted.show(
                        this.__('Updated Brand Version Name', {
                            resource: 'Items',
                        }),
                        {type: 'success'}
                    )



                } catch (error) {
                    if (error.response.status == 422) {
                        this.validationErrors = new Errors(error.response.data.errors);
                        console.log(this.validationErrors);
                    }
                }

            },
            async deleteBrandVersion(){

                try {

                    this.searchLoading = true;

                    let formData = new FormData();
                    formData.append('brand_id', this.brand_id);
                    formData.append('version_id', this.version_id);

                    const response = await Nova.request().post(`/nova-vendor/ItemBuilder/deleteVersion`, formData);
                    this.getVersion(this.brand_id);
                    this.searchLoading = false;
                    this.showDeleteModal = false;
                    this.setGrid();
                    this.grid.data = [];
                    this.category_id = '';
                    this.$toasted.show(
                        this.__('Brand Version Deleted', {
                            resource: 'Items',
                        }),
                        {type: 'success'}
                    )



                } catch (error) {
                    if (error.response.status == 422) {
                        this.validationErrors = new Errors(error.response.data.errors);
                        console.log(this.validationErrors);
                    }
                }

            },
            versionFromData() {
                let formData = new FormData();
                formData.append('brand_id', this.brand_id);
                formData.append('version_name', this.version_name);
                formData.append('version_id', this.version_id);

                return formData;
            },
        },
        components: {
            HotTable,
            ModelListSelect
        },

    }
</script>
<style src="../../../node_modules/handsontable/dist/handsontable.full.css"></style>

<template>
    <loading-view :loading="loading">
        <div>
            <heading class="mb-6"></heading>
            <h1 class="mb-3 text-90 font-normal text-2xl">{{headers.heading}}</h1>
            <bread-crumbs :breadcrumbs="breadcrumbs"></bread-crumbs>
            <div class="flex" style="">
                <div class="flex items-center mb-6">
                    <div class="flex-no-shrink ml-auto">
                        &nbsp;
                    </div>
                </div>
            </div>

            <card class="overflow-hidden">
                <form v-if="fields"  autocomplete="off" @submit.prevent="createBrandResource">
                    <!-- Fields -->
                    <div>
                        <div class="help-text help-text mt-2">
                            <help-text class="text-center error-text mt-2 text-danger"
                                       v-if="nameError">
                                {{nameError[0]}}
                            </help-text>
                        </div>
                        <div v-for="field in fields">
                            <component
                                :is="'form-' + field.component"
                                :resource-name="resourceName"
                                :field="field"
                            />
                        </div>

                    </div>


                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            Type
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <div class="help-text help-text mt-2">

                            </div>
                            <select v-model="activeBrand.type" @change="setDealerList" class="w-full form-control form-input form-input-bordered">
                                <option value="" >Choose Type</option>
                                <option v-for="(type,index) in types" :value="index" >{{type}}</option>
                            </select>


                        </div>
                    </div>
                     <div class="flex border-b border-40" v-if="activeBrand.type == '1'">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            Dealers
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <div class="help-text help-text mt-2">
                            </div>
                            <multiselect :options="dealers"
                                         v-model="activeBrand.dealers"
                                         track-by="id"
                                         label="name"
                                         placeholder="Select Dealers"
                                         :option-height="40"
                                         :searchable="true"
                                         :allow-empty="true"
                                         :multiple="true"
                            >
                            </multiselect>

                        </div>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            Company Email
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <div class="help-text help-text mt-2">
                                <help-text class="text-center error-text mt-2 text-danger"
                                           v-if="nameEmail">
                                    {{nameEmail[0]}}
                                </help-text>
                            </div>
                            <input type="email"   v-model="activeBrand.email" placeholder="Company Email" class="w-full form-control form-input form-input-bordered"> <!---->

                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            Company Address
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <vue-google-autocomplete
                                v-model="activeBrand.address"
                                ref="address"
                                id="map"
                                classname="w-full form-control form-input form-input-bordered"
                                placeholder="Please type your address"
                                v-on:placechanged="getAddressData"
                            >
                            </vue-google-autocomplete>
                            <div class="help-text help-text mt-2">
                            </div>
                        </div>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            Company Address (line 2)
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="text" v-model="activeBrand.company_address" placeholder="Company Address" class="w-full form-control form-input form-input-bordered"> <!---->
                            <div class="help-text help-text mt-2">
                            </div>
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            City
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="text" v-model="activeBrand.city" placeholder="City" class="w-full form-control form-input form-input-bordered"> <!---->
                            <div class="help-text help-text mt-2">
                            </div>
                        </div>
                    </div>


                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            State
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="text" v-model="activeBrand.state" placeholder="State" class="w-full form-control form-input form-input-bordered">
                            <div class="help-text help-text mt-2">
                            </div>
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            Zip / Postal Code
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="text" v-model="activeBrand.postal_code" placeholder="Zip / Postal Code" class="w-full form-control form-input form-input-bordered"> <!---->
                            <div class="help-text help-text mt-2">
                            </div>
                        </div>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            Company Phone
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="text" v-model="activeBrand.company_phone" placeholder="Company Phone" class="w-full form-control form-input form-input-bordered"> <!---->
                            <div class="help-text help-text mt-2">
                            </div>
                        </div>
                    </div>


                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            Company Fax
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="text" v-model="activeBrand.company_fax" placeholder="Company Fax" class="w-full form-control form-input form-input-bordered"> <!---->
                            <div class="help-text help-text mt-2">
                            </div>
                        </div>
                    </div>

                        <div class="bg-30 flex px-8 py-4">
                             <span v-show="postLoading">
                            <loader class="mt-1 text-60"/>
                        </span>
                         <!--<button v-show="!postLoading" v-if="!this.$route.params.id"  dusk="create-and-add-another-button" type="button" class="ml-auto btn btn-default btn-primary mr-3">-->
                                <!--{{__('Save &amp; Create Another')}}-->
                            <!--</button>-->
                        <button v-show="!postLoading"  dusk="create-button" class="btn btn-default btn-primary">
                            {{__('Save Brand')}}
                        </button>
                        </div>

                </form>
            </card>
        </div>
    </loading-view>
</template>

<script>
    import {Errors, InteractsWithResourceInformation} from 'laravel-nova';
    import {ModelListSelect} from 'vue-search-select';
    import Multiselect from 'vue-multiselect';
    import VueGoogleAutocomplete from 'vue-google-autocomplete'

    export default {
        name: "brand-form",
        components: {Multiselect, ModelListSelect ,  VueGoogleAutocomplete },
        mixins: [InteractsWithResourceInformation],

        props: {
            resourceName: {
                type: String,
                required: true,
            }
        },
        data: () => ({
            headers:{
                heading:'Brand Settings'
            },
            types:[
                'Non Exclusive',
                'Exclusive',
            ],
            brandType:'',
            relationResponse: null,
            loading: true,
            fields: [],
            validationErrors: new Errors(),
            countries:[],
            dealers:[],
            dealer:'',
            postLoading:false,
            heading:'Create Dealer',
            editDealer:false,
            dealerStatus:[],
            accountstype:[],
            nameError:'',
            nameEmail:'',
            activeCountry:null,
            address:'',
            dealers:[],
            activeBrand:{
                company_name:'',
                address:'',
                company_address:'',
                company_phone:'',
                email:'',
                city:'',
                state:'',
                postal_code:'',
                company_fax:'',
                type:'',
                dealers:[]
            },
        }),
        async created() {
            // If this create is via a relation index, then let's grab the field
            // and use the label for that as the one we use for the title and buttons

            this.getFields();
            this.getDealersList();
            this.breadcrumbs=[
                {url:'/brands',label:'Brands'},
                {url:'',label:this.resourceName},
            ];
            this.loading = false;
        },
        methods: {
            /**
             * Get the available fields for the resource.
             */

            getAddressData: function (addressData, placeResultData, id) {
              //console.log(addressData);
              //console.log(placeResultData);
              if(placeResultData.address_components){
                  this.activeBrand.city = placeResultData.address_components[ this.findAddressIndex(placeResultData.address_components, 'locality') ].short_name;
                  this.activeBrand.state = placeResultData.address_components[this.findAddressIndex(placeResultData.address_components, 'administrative_area_level_1') ].long_name;
                  this.activeBrand.postal_code = placeResultData.address_components[this.findAddressIndex(placeResultData.address_components, 'postal_code')].short_name;
                  this.activeBrand.address = addressData.street_number+' '+addressData.route;
              }

            },
            findAddressIndex(obj, needle){
                let index = obj.findIndex(elem => elem.types.indexOf(needle)>=0);
                return index;
            },
            async getFields() {

                this.fields = [];

                if(this.$route.params.id){

                    let brand_id = this.$route.params.id;
                    const {data: fields} = await Nova.request().get(
                        `/nova-api/${this.resourceName}/`+brand_id+`/update-fields`
                    );
                    this.fields = fields;

                        Nova.request().get(`/nova-vendor/ProductBuilder/getBrand/`+brand_id).then((response) => {

                            this.activeBrand = response.data.brand[0];
                            if(!this.activeBrand.dealers){
                                this.activeBrand.dealers = [];
                            }
                            if(this.dealers){
                                let activeDealers = [];
                                this.activeBrand.dealers.forEach(function(dealer){
                                    let fullDealer = this.dealers.find(elem => elem.id == dealer);
                                    if(fullDealer){
                                        activeDealers.push(fullDealer);
                                    }
                                }.bind(this));
                                console.log(activeDealers);
                                this.activeBrand.dealers = activeDealers;
                            }
                        });


                } else {

                    const {data: fields} = await Nova.request().get(
                        `/nova-api/${this.resourceName}/creation-fields`
                    );
                    this.fields = fields;

                }

            },
            /*
            * Get Dealers List for Exclusive Brand Type
            * */
            async getDealersList(){
                await axios.get(`/nova-vendor/ProductBuilder/getDealers`).then((response) => {
                    this.dealers = response.data.accounts;
                });
            },
            setDealerList(){
              this.activeBrand.dealers = [];

            },
            /**
             * Create the form data for creating the resource.
             */
            createResourceData() {

                this.postLoading = true;
                let formData = new FormData();
                this.fields.forEach(field => {
                    field.fill(formData);
                });
                let dealerIds = [];
                for(let i = 0 ; i < this.activeBrand.dealers.length; i++){
                    dealerIds.push(this.activeBrand.dealers[i].id);
                }
                this.activeBrand.dealers = dealerIds;
                console.log(dealerIds);
                formData.append('activeBrand',JSON.stringify(this.activeBrand));
                if(this.$route.params.id){
                    formData.append('id',this.$route.params.id);
                }
                return formData;

            },

            async createBrandResource() {

                try {
                  await this.createBrandRequest();
                } catch (error) {
                    console.log(error);

                }

            },
            /**
             * Send a create request for this resource
             */
            createBrandRequest() {
                    axios.post(`/nova-vendor/ProductBuilder/addNewBrand`,this.createResourceData()
                    ).then(response => {
                        this.postLoading = false;
                        if(response.data.errors == true){
                            this.nameError = response.data.message.name;
                            this.nameEmail = response.data.message.email;

                        }else{
                            if(response.data.success == true){

                                if(this.$route.params.id){
                                    this.$toasted.show(
                                        this.__('The :resource is updated!', {
                                            resource: 'Brand Settings',
                                        }),
                                        {type: 'success'}
                                    );
                                } else {
                                    this.$toasted.show(
                                        this.__('The :resource is created!', {
                                            resource: 'Brand Settings',
                                        }),
                                        {type: 'success'}
                                    );
                                }
                                this.$router.push({path:'/brands'});
                            }


                        }

                    }).catch(error => {
                        console.log(error);
                    });
            },
        },

        mounted() {
            //
        },
    }
</script>

<style>
    /* Scoped Styles */
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

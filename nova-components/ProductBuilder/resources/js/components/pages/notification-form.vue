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
                <form v-if="fields"  autocomplete="off" @submit.prevent="createNotifyResource">
                    <!-- Validation Errors -->
                    <validation-errors :errors="validationErrors"/>

                    <!-- Fields -->
                    <div v-for="field in fields">
                        <component
                            :is="'form-' + field.component"
                            :errors="validationErrors"
                            :resource-name="resourceName"
                            :field="field"
                        />
                    </div>

                    <div>
                        <div class="flex border-b border-40">
                            <div class="w-1/5 py-6 px-8">
                                <label class="inline-block text-80 pt-2 leading-tight">Choose to notify</label>
                            </div>
                            <div class="py-6 px-8 w-1/2">
                                <select id="notify_type" v-model="notify_type" class="w-full form-control form-select">
                                    <option value="" selected="selected" disabled="disabled">Choose an option</option>
                                    <option value="all_dealers">All Dealers</option>
                                    <option value="specific_dealers">Specific Dealers</option>
                                    <option value="specific_country">Dealers for  specific country</option>
                                </select>
                                <!---->
                                <div class="help-text help-text mt-2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex border-b border-40" v-if="this.notify_type == 'specific_country'">
                        <div class="w-1/5 py-6 px-8">
                            <label class="inline-block text-80 pt-2 leading-tight">Country</label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <model-list-select :list="countries"
                                               v-model="country_id"
                                               option-value="id"
                                               option-text="name"
                                               placeholder="Select a Country"
                            ></model-list-select>
                            <div class="help-text help-text mt-2"></div>
                        </div>
                    </div>

                    <div class="flex border-b border-40" v-if="this.notify_type == 'specific_dealers'">
                        <div class="w-1/5 py-6 px-8">
                            <label class="inline-block text-80 pt-2 leading-tight">Dealers</label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <multiselect v-model="dealer"
                                             placeholder="Search or Select Hide"
                                             label="name"
                                             track-by="id"
                                             :required="true"
                                             :options="dealers"
                                             :multiple="true"
                                             open-direction="top"></multiselect>
                            <div class="help-text help-text mt-2"></div>
                        </div>
                    </div>

                    <div>
                        <help-text class="text-center error-text mt-2 text-danger"
                                   v-if="validationErrors.hasOwnProperty('errors') &&
                               validationErrors.errors.hasOwnProperty('row')">
                            {{validationErrors.errors.row[0]}}
                        </help-text>
                    </div>

                    <div class="float-right" style="padding-top: 2%; padding-bottom: 2%; margin-right: 50px;">
                        <span v-show="postLoading">
                            <loader class="mt-1 text-60"/>
                        </span>
                        <button v-show="!postLoading" dusk="create-button" class="btn btn-default bg-green">
                            {{__('Save')}}
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

    export default {
        name: "notification-form",
        components: {Multiselect, ModelListSelect},
        mixins: [InteractsWithResourceInformation],

        props: {
            resourceName: {
                type: String,
                required: true,
            }
        },
        data: () => ({
            headers:{
                heading:'Notification Setting'
            },
            relationResponse: null,
            loading: true,
            fields: [],
            validationErrors: new Errors(),
            countries:[],
            dealers:[],
            country_id:14,
            dealer:'',
            notify_type:'',
            specific_country:false,
            specific_dealers:false,
            postLoading:false,
        }),
        async created() {
            // If this create is via a relation index, then let's grab the field
            // and use the label for that as the one we use for the title and buttons

            this.getFields();

            this.breadcrumbs=[
                {url:'/notification-settings',label:'Notifications'},
                {url:'',label:this.resourceName},
            ];
            this.setCountries();
            this.loading = false;
        },
        methods: {
            /**
             * Get the available fields for the resource.
             */
            async getFields() {

                this.fields = [];

                if(this.$route.params.id){

                    let notify_id = this.$route.params.id;
                    const {data: fields} = await Nova.request().get(
                        `/nova-api/${this.resourceName}/`+notify_id+`/update-fields`
                    );
                    this.fields = fields;

                } else {

                    const {data: fields} = await Nova.request().get(
                        `/nova-api/${this.resourceName}/creation-fields`
                    );
                    this.fields = fields;

                }

            },
            /**/
            setCountries(){
                Nova.request().get(`/nova-vendor/ProductBuilder/countries`).then((response) => {
                    this.countries = response.data.countries;
                });
                Nova.request().get(`/nova-vendor/ProductBuilder/getDealers`).then((response) => {
                    this.dealers = response.data.accounts;
                });
                /**/
                if(this.$route.params.id){
                    let notify_id = this.$route.params.id;
                    Nova.request().get(`/nova-vendor/ProductBuilder/getNotifyData/`+notify_id).then((response) => {
                        this.notify_type = response.data.notification.notify_type;
                        if(this.notify_type == 'specific_dealers'){
                            this.dealer = JSON.parse(response.data.notification.dealers);
                        }
                        if(this.notify_type == 'specific_country'){
                            this.country_id = response.data.notification.country_id;
                        }
                    });
                }
                /**/
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
                formData.append('notify_type',this.notify_type);
                formData.append('country_id',this.specific_country);
                formData.append('dealers',JSON.stringify(this.dealer));
                if(this.$route.params.id){
                    formData.append('id',this.$route.params.id);
                }
                return formData;

            },

            async createNotifyResource() {

                try {

                    const response = await this.createNotifyRequest();
                    this.postLoading = false;
                    if(this.$route.params.id){
                        this.$toasted.show(
                            this.__('The :resource is updated!', {
                                resource: 'Notification Setting',
                            }),
                            {type: 'success'}
                        );
                    } else {
                        this.$toasted.show(
                            this.__('The :resource is created!', {
                                resource: 'Notification Setting',
                            }),
                            {type: 'success'}
                        );
                    }

                    this.$router.push({
                        name: 'notification-setting'
                    })


                } catch (error) {
                    this.postLoading = false;
                    if (error.response.status == 422) {
                        this.validationErrors = new Errors(error.response.data.errors)
                    }
                }

            },
            /**
             * Send a create request for this resource
             */
            createNotifyRequest() {
                return Nova.request().post(`/nova-vendor/ProductBuilder/addNotification`,this.createResourceData())
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

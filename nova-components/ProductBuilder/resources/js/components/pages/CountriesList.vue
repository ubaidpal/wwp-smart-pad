<template>
    <div>
        <h1 class="mb-3 text-90 font-normal text-2xl">{{headers.heading}}</h1>
        <bread-crumbs :breadcrumbs="breadcrumbs"></bread-crumbs>
        <div class="flex" style="">
            <div class="flex items-center mb-6">
            <div class="flex-no-shrink ml-auto">
                <router-link :to="{
                            name: 'create',
                            params: {
                                resourceName: 'countries'
                            }
                        }" class="btn btn-default btn-primary">Create Country</router-link>
            </div>
        </div></div>

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
                            <span v-if="props.column.field == 'active'">
                                <toggle-button @change="onChangeEventHandler(props.row.id,props.column.field,props.row.active)"
                                               :value="props.row.active"
                                               color="#8bc34a"
                                               :sync="true"
                                               :labels="true"/>
                                <!--<div class="text-center text-center" via-resource="" via-resource-id="">-->
                                    <!--<span v-if="props.row.active == 1" class="inline-block rounded-full w-2 h-2 bg-success"></span>-->
                                    <!--<span v-else class="inline-block rounded-full w-2 h-2 bg-danger"></span>-->
                                <!--</div>-->
                            </span>
                            <span v-else-if="props.column.field == 'iso2_code'">
                                <div style="float:left"><img v-bind:src="props.row.flag" class="rounded-full w-8 h-8 mr-1" style="object-fit: cover;"/></div>
                                <div style="margin-top: 5px;">{{props.row.iso2_code}}</div>
                            </span>
                            <span v-else-if="props.column.field == 'action'">
                                <span>
                                    <router-link title="Edit Country" :to="{name: 'edit',params: {resourceName:'countries',resourceId:props.row.id}}" class="cursor-pointer text-70 hover:text-primary mr-3 spp-save" >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current"><path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path></svg>
                                    </router-link>
                                </span>
                                <span>
                                    <button
                                        title="Delete Country"
                                        :data-testid="`${props.row.id}-delete-button`"
                                        :dusk="`${props.row.id}-delete-button`"
                                        class="appearance-none cursor-pointer text-70 hover:text-primary mr-3 spp-delete"
                                        @click.prevent="openDeleteModal(props.row.id)"
                                    >
                <icon />
            </button>

                                </span>
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
    </div>
</template>

<script>
  import {VueGoodTable} from 'vue-good-table';
  import 'vue-good-table/dist/vue-good-table.css'

  export default {
        name: "countries-list",
          resourceName: {
              type: String,
          },
        data: () => ({
            deleteModalOpen: false,
            deleteResource:0,
            headers:{
                heading:'Countries Listing'
            },
            columns: [
                {
                    label: 'Name',
                    field: 'name',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Code',
                    field: 'iso2_code',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Currency',
                    field: 'currency',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Measure System',
                    field: 'measure_system',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Currency Symbol',
                    field: 'currency_symbol',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Date Format',
                    field: 'date_format',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Tax',
                    field: 'tax',
                    type:'number',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Active',
                    field: 'active',
                    type: 'number',
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
            breadcrumbs:[]
        }),
        components: {
          VueGoodTable,
        },
        methods: {

            onChangeEventHandler(id,type,active){
                let formData = new FormData();
                formData.append('resourceId', id);
                formData.append('current_status', active);
                Nova.request().post('/nova-vendor/ProductBuilder/updateCountryStatus',formData);

                this.$toasted.show(
                    this.__('The :resource is Updated!',{
                        resource: 'Country',
                    }),
                    {type: 'success'}
                );
            },

            openDeleteModal(id) {
                this.deleteResource = id;
                this.deleteModalOpen = true
            },

            confirmDelete() {
                //console.log(this.deleteResource);
                let formData = new FormData();
                formData.append('resourceId', this.deleteResource);
                Nova.request().post('/nova-vendor/ProductBuilder/destroyCountry',formData);
                this.deleteResource = 0;
                this.closeDeleteModal();
                this.getBrands();
            },

            closeDeleteModal() {
                this.deleteResource = 0;
                this.deleteModalOpen = false
            },

            getBrands(){
                Nova.request().get(`/nova-vendor/ProductBuilder/getCountries`).then((response) => {
                    this.rows = response.data.countries;
                });
            }

        },
        async created(){
          await axios.get(`/nova-vendor/ProductBuilder/getCountries`).then((response) => {
              this.rows = response.data.countries;
          });

            if(!this.resourceName){
                this.resourceName = 'countries';
            }
            this.breadcrumbs=[
                {url:'',label:this.resourceName},
            ];
        }
    }
</script>

<style scoped>

</style>

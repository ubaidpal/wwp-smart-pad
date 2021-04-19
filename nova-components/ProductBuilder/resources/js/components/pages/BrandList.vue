<template>
    <loading-view :loading="loading">
        <div>
            <bread-crumbs :breadcrumbs="breadcrumbs"></bread-crumbs>
            <h1 class="mb-3 text-90 font-normal text-2xl">{{headers.heading}}</h1>

            <div class="flex" style="">
                <div class="flex items-center mb-6">
                    <div class="flex-no-shrink ml-auto">
                        <router-link :to="{
                           path:'/brands/new'
                        }" class="btn btn-default btn-primary">Create Brand</router-link>
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
                            :sort-options="{
                            enabled: true,
                            placeholder: 'Search this table',
                            initialSortBy: {field: 'name', type: 'asc'}
                        }"
                            :search-options="{
                            enabled: true,
                            placeholder: 'Search this table',
                            initialSortBy: {field: 'name', type: 'asc'}
                        }"
                        >
                            <template slot="table-row" slot-scope="props">
                            <span v-if="props.column.field == 'is_active'">
                                <toggle-button
                                    @change="onChangeHandler(props.row.id,props.column.field,props.row.is_active)"
                                    :value="props.row.is_active"
                                    color="#8bc34a"
                                    :sync="true"
                                    :labels="true"/>
                            </span>
                                <span v-else-if="props.column.field == 'countries'">
                                <div style="float:left"><img v-bind:src="props.row.flag" class="rounded-full w-8 h-8 mr-1" style="object-fit: cover;"/></div>
                                <div style="margin-top: 5px;">{{props.row.iso2_code}}</div>
                            </span>
                                <span v-else-if="props.column.field == 'type'">
                                <toggle-button @change="onChangeType(props.row.id,props.column.field,props.row.type)"
                                               :value="props.row.type"
                                               color="#8bc34a"
                                               :sync="true"
                                               :labels="true"/>
                            </span>
                                <span v-else-if="props.column.field == 'is_fabric'">
                                <toggle-button @change="onChangeHandler(props.row.id,props.column.field,props.row.is_fabric)"
                                               :value="props.row.is_fabric"
                                               color="#8bc34a"
                                               :sync="true"
                                               :labels="true"/>
                            </span>
                                <span v-else-if="props.column.field == 'price_type'">

                                <span v-if="props.row.tax_amount">
                                    {{props.row.price_type}} ({{props.row.tax_amount+'%'}})
                                </span>
                            </span>
                                <span v-else-if="props.column.field == 'dealers'">

                                <div>
                                    <router-link :to="{ path: 'brands/'+props.row.id+'/dealer' }" class="cursor-pointer text-70 hover:text-primary mr- spp-warning" :title="'Assign Brands'">
                                        <svg  class="fill-current text-teal inline-block" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path d="M9.26 13a2 2 0 0 1 .01-2.01A3 3 0 0 0 9 5H5a3 3 0 0 0 0 6h.08a6.06 6.06 0 0 0 0 2H5A5 5 0 0 1 5 3h4a5 5 0 0 1 .26 10zm1.48-6a2 2 0 0 1-.01 2.01A3 3 0 0 0 11 15h4a3 3 0 0 0 0-6h-.08a6.06 6.06 0 0 0 0-2H15a5 5 0 0 1 0 10h-4a5 5 0 0 1-.26-10z"></path>
                    </svg></router-link> &nbsp;

                                </div>
                            </span>
                                <span v-else-if="props.column.field == 'action'">
                                <span>
                                    <router-link title="Edit Brand" :to="{path:'/brands/edit/'+props.row.id}" class="cursor-pointer text-70 hover:text-primary mr-3 spp-save" >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current"><path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path></svg>
                                    </router-link>
                                </span>
                                <span>
                                    <button
                                        title="Delete Brand"
                                        v-if="props.row.count == 0"
                                        :data-testid="`${props.row.id}-delete-button`"
                                        :dusk="`${props.row.id}-delete-button`"
                                        class="appearance-none cursor-pointer text-70 hover:text-primary mr-3 spp-delete"
                                        @click.prevent="openDeleteModal(props.row.id)"
                                    >
                                    <icon />
                                </button>

                                </span>
                                <span v-if="props.row.type == 1">
                                    <router-link title="Edit Brand" :to="{ path: 'brands/'+props.row.id+'/assign' }" class="cursor-pointer text-70 hover:text-primary mr-3" >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" class="fill-current"><path d="M5.08261143,12.1593397 C4.54304902,12.6798471 3.80891237,13 3,13 C1.34314575,13 0,11.6568542 0,10 C0,8.34314575 1.34314575,7 3,7 C3.80891237,7 4.54304902,7.32015293 5.08261143,7.84066029 L14.0226687,3.37063167 C14.0077053,3.24918566 14,3.12549267 14,3 C14,1.34314575 15.3431458,0 17,0 C18.6568542,0 20,1.34314575 20,3 C20,4.65685425 18.6568542,6 17,6 C16.1910876,6 15.456951,5.67984707 14.9173886,5.15933971 L5.97733131,9.62936833 C5.99229467,9.75081434 6,9.87450733 6,10 C6,10.1254927 5.99229467,10.2491857 5.97733131,10.3706317 L14.9173886,14.8406603 C15.456951,14.3201529 16.1910876,14 17,14 C18.6568542,14 20,15.3431458 20,17 C20,18.6568542 18.6568542,20 17,20 C15.3431458,20 14,18.6568542 14,17 C14,16.8745073 14.0077053,16.7508143 14.0226687,16.6293683 L5.08261143,12.1593397 L5.08261143,12.1593397 Z" id="Combined-Shape"></path></svg>
                                    </router-link>
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
    </loading-view>
</template>

<script>
  import {VueGoodTable} from 'vue-good-table';
  import 'vue-good-table/dist/vue-good-table.css';

  export default {
        name: "brand-list",
          resourceName: {
              type: String,
          },
        data: () => ({
            loading:true,
            deleteModalOpen: false,
            deleteResource:0,
            headers:{
                heading:'Brand Listing'
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
                    label: 'Countries',
                    field: 'countries',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',

                },
                {
                    label: 'Product Count',
                    field: 'count',
                    type: 'number',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',

                },
                {
                    label: 'Exclusive',
                    field: 'type',
                    type: 'number',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',

                },
                {
                    label: 'Fabric',
                    field: 'is_fabric',
                    type: 'number',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',

                },
                {
                    label: 'Active',
                    field: 'is_active',
                    type: 'number',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',

                },
                {
                    label: 'Price List Type (Tax %)',
                    field: 'price_type',
                    type: 'number',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',

                },
                {
                    label: 'Dealers',
                    field: 'dealers',
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
            breadcrumbs:[]
        }),
        components: {
          VueGoodTable,
        },
        methods: {

            onChangeHandler(id,type,active){
                let formData = new FormData();
                formData.append('resourceId', id);
                formData.append('type', type);
                formData.append('current_status', active);
                Nova.request().post('/nova-vendor/ProductBuilder/updateBrandStatus',formData);

                this.$toasted.show(
                    this.__('The :resource are Updated!',{
                        resource: 'Brand',
                    }),
                    {type: 'success'}
                );
                this.getBrands();

            },
            onChangeType(id,type,active){
                let formData = new FormData();
                formData.append('resourceId', id);
                formData.append('type', type);
                formData.append('exclusive_status', active);
                Nova.request().post('/nova-vendor/ProductBuilder/updateExclusiveStatus',formData).then((response) => {
                    this.$toasted.show(
                        this.__('The :resource are Updated!',{
                            resource: 'Brand',
                        }),
                        {type: 'success'}
                    );
                    this.getBrands();
                });



            },

            openDeleteModal(id) {
                this.deleteResource = id;
                this.deleteModalOpen = true
            },

            confirmDelete() {
                let formData = new FormData();
                formData.append('resourceId', this.deleteResource);
                Nova.request().post('/nova-vendor/ProductBuilder/destroyBrand',formData);
                this.deleteResource = 0;
                this.closeDeleteModal();
                this.getBrands();
            },

            closeDeleteModal() {
                this.deleteResource = 0;
                this.deleteModalOpen = false
            },

            getBrands(){
                Nova.request().get(`/nova-vendor/ProductBuilder/getBrands`).then((response) => {
                    this.rows = response.data.brands;
                });
            }

        },
        async created(){
          await axios.get(`/nova-vendor/ProductBuilder/getBrands`).then((response) => {
              this.rows = response.data.brands;
          });

            if(!this.resourceName){
                this.resourceName = 'brands';
            }
            this.breadcrumbs=[
                {url:'',label:this.resourceName},
            ];
            this.loading = false;

        }
    }
</script>

<style scoped>
    .vue-js-switch#changed-font {
        font-size: 16px;
    }

</style>

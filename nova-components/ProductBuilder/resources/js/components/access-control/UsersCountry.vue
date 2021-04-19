<template>
    <div>
        <bread-crumbs :breadcrumbs="breadcrumbs"></bread-crumbs>
        <h1 class="mb-3 text-90 font-normal text-2xl">{{headers.heading}}</h1>

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
                            <span v-if="props.column.field == 'role'">
                                <span v-if="props.row.roles">
                                    {{props.row.roles['name']}}
                                </span>
                            </span>
                            <span v-else-if="props.column.field == 'country_id'">
                                <span data-v-809cbbe4="" v-if="props.row.is_access_limited > 0">
                                    <a data-v-809cbbe4="" class="dropdown-trigger h-9 flex items-center cursor-pointer select-none h-9 flex items-center">
                                        <img data-v-809cbbe4="" v-bind:src="getCountryFlag(props.row.country_id)" class="rounded-full w-8 h-8 mr-3" style="object-fit: cover;">
                                        <span data-v-809cbbe4="" class="text-90">{{getCountryName(props.row.country_id)}}</span>
                                    </a>
                                </span>
                                <span v-else>
                                    All Countries
                                </span>
                            </span>
                            <span v-else-if="props.column.field == 'action'">
                                 <span @click="editUserModel(props.row)" class="spp-save" v-if="props.row.id > 1">
                                     <svg data-v-a2b828c2="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current"><path data-v-a2b828c2="" d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path></svg>
                                </span>
                            </span>
                            <span v-else>
                                {{props.formattedRow[props.column.field]}}
                            </span>
                        </template>
                    </vue-good-table>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <modal v-if="showEditModal" @close="showEditModal = false">

            <div class="bg-white rounded-lg shadow-lg overflow-hidden" style="width: 460px">
                <div class="p-8">
                    <heading :level="2" class="mb-6" >Grant Access</heading>
                </div>

                <div>
                    <div class="flex border-b border-40" via-resource="" via-resource-id="" via-relationship="">
                        <div class="w-1/5 py-6 px-8">
                            <label class="inline-block text-80 pt-2 leading-tight mb-2" for="Limited Access">Limited Access</label>
                        </div>
                        <div class="py-6 px-8 w-4/5">
                            <div class="flex items-center py-2" id="is_access_limited" name="Limited Access">
                                <input type="checkbox" v-model="is_access_limited" @click="checkboxEvent($event)">
                            </div> <!---->
                            <div class="help-text help-text mt-2">All Countries will be accessable if Not Checked</div>
                        </div>
                    </div>
                </div>

                <div v-if="showCounties">

                    <div class="flex border-b border-40" via-resource="" via-resource-id="" via-relationship="">
                        <div class="w-1/5 py-6 px-8">
                            <label class="inline-block text-80 pt-2 leading-tight mb-2">Country</label>
                        </div>
                        <div class="py-6 px-8 w-4/5">
                            <div class="flex items-center py-2" name="Limited Access">
                                <multiselect :options="this.activeCountries"
                                             v-model="activeObj"
                                             track-by="id"
                                             label="name"
                                             placeholder="Select Country"
                                             :option-height="40"
                                             :searchable="false"
                                             :allow-empty="false"
                                >
                                    <template slot="singleLabel" slot-scope="props">
                                        <span contenteditable="false">
                                            <img style="object-fit: cover;" class="rounded-full w-8 h-8 mr-3" v-bind:src="props.option.flag" />
                                        </span> &nbsp;
                                        <span style="position: absolute; top: 7px;" class="option__title text-90">{{ props.option.name }}</span>
                                    </template>
                                    <template slot="option" slot-scope="props">
                                        <span contenteditable="false">
                                            <img style="object-fit: cover;" class="rounded-full w-8 h-8 mr-3" v-bind:src="props.option.flag" />
                                        </span>
                                        <span class="option__title text-90">{{ props.option.name }}</span>
                                    </template>
                                </multiselect>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <button type="button" @click="closeModel()" class="btn btn-primary btn-default">{{__('Cancel')}}</button>
                        <button type="button" @click="setCountry()" class="btn btn-default bg-green">{{__('Save')}}</button>
                    </div>
                </div>
            </div>

        </modal>

    </div>
</template>

<script>
  import {VueGoodTable} from 'vue-good-table';
  import 'vue-good-table/dist/vue-good-table.css';
  import Multiselect from 'vue-multiselect';

  export default {
        name: "users-country",
          resourceName: {
              type: String,
          },
        data: () => ({
            deleteModalOpen: false,
            deleteResource:0,
            headers:{
                heading:'Users Country'
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
                    label: 'Username / Email',
                    field: 'email',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',

                },
                {
                    label: 'Country Access',
                    field: 'country_id',
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
            breadcrumbs:[],
            activeCountries:[],
            showEditModal:false,
            showCounties:false,
            is_access_limited:false,
            activeObj:{},
            resourceId:0
        }),
        components: {
          VueGoodTable,
          Multiselect
        },
        methods: {

            checkboxEvent(e){
                console.log(e.target.checked);
                this.showCounties = e.target.checked;
            },
            editUserModel(obj){
                this.showEditModal = true;
                this.getCountryObj(obj.country_id);
                this.resourceId = obj.id;
                this.is_access_limited = obj.is_access_limited;
                if(obj.is_access_limited){
                    this.showCounties = true;
                }

            },
            closeModel(){
                this.showCounties = false;
                this.showEditModal = false;
                this.is_access_limited = false;
                this.resourceId = 0;
            },
            getUsers(){
                Nova.request().get(`/nova-vendor/ProductBuilder/getUsers`).then((response) => {
                    this.rows = response.data.users;
                });
            },
            getCountryObj(status){
                let obj = this.activeCountries.find(elem => elem.id == status);
                if(obj){
                    this.activeObj = obj;
                    return obj;
                }
                else{
                    return 0;
                }
            },
            getCountryFlag(status){
                let obj = this.activeCountries.find(elem => elem.id == status);
                if(obj){
                    return obj.flag;
                }
                else{
                    return 0;
                }
            },
            getCountryName(status){
                let obj = this.activeCountries.find(elem => elem.id == status);
                if(obj){
                    return obj.name;
                }
                else{
                    return 0;
                }
            },
            async setCountry(){
                let formData = new FormData();
                formData.append('resourceId', this.resourceId);
                formData.append('is_access_limited', this.is_access_limited);
                formData.append('country_id', this.activeObj.id);
                await Nova.request().post(`/nova-vendor/ProductBuilder/updateCountryUser`,formData).then((response) => {
                    if(response.data.success){
                        this.$toasted.show(
                            this.__('The :status Country is Updated!',{
                                resource: 'User',
                            }),
                            {type: 'success'}
                        );
                        this.getUsers();
                        this.closeModel();
                    }
                });
            }

        },
      async created(){
          await axios.get(`/nova-vendor/ProductBuilder/getUsers`).then((response) => {
              this.rows = response.data.users;
          });

          await axios.get(`/nova-vendor/ProductBuilder/getactivecountries`).then((response) => {
              this.activeCountries = response.data.countries;
          });

          if(!this.resourceName){
              this.resourceName = 'Users Country';
          }
          this.breadcrumbs=[
              {url:'',label:this.resourceName},
          ];

      }
    }
</script>

<style scoped>
    .vue-js-switch#changed-font {
        font-size: 16px;
    }

</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

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
                                resourceName: 'dashboard-settings'
                            }
                        }" class="btn btn-default btn-primary">Create</router-link>
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
                             <span v-if="props.column.field == 'id'">

                                <div style=";">{{props.row.id}}</div>
                            </span>
                            <span v-else-if="props.column.field == 'name'">

                                <div style="margin-top: 5px;">{{props.row.name}}</div>
                            </span>
                            <span v-else-if="props.column.field == 'order'">

                                <div style="margin-top: 5px;">{{props.row.order}}</div>
                            </span>
                            <span v-else-if="props.column.field == 'created_at'">

                                <div style="margin-top: 5px;">{{props.row.created_at}}</div>
                            </span>
                            <span v-else-if="props.column.field == 'action'">
                                <span>
                                    <router-link :to="{name: 'help-Listing',params: {resourceName:'help',help_group_id:props.row.id}}" class="cursor-pointer text-70 hover:text-primary mr-3" >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current"><path d="M.2 10a11 11 0 0 1 19.6 0A11 11 0 0 1 .2 10zm9.8 4a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm0-2a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg>
                                    </router-link>
                                </span>
                                <span>
                                    <router-link :to="{name: 'edit',params: {resourceName:'dashboard-settings',resourceId:props.row.id}}" class="cursor-pointer text-70 hover:text-primary mr-3" >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current"><path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path></svg>
                                    </router-link>
                                </span>
                                <span>
                                    <button
                                            :data-testid="`${props.row.id}-delete-button`"
                                            :dusk="`${props.row.id}-delete-button`"
                                            class="appearance-none cursor-pointer text-70 hover:text-primary mr-3"
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
                heading:'Help Group Setting'
            },
            columns: [
                {
                    label: 'ID',
                    field: 'id',
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
                    label: 'Order',
                    field: 'order',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Created Date',
                    field: 'created_at',
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

            openDeleteModal(id) {
                this.deleteResource = id;
                this.deleteModalOpen = true
            },

            confirmDelete() {
                console.log(this.deleteResource);
                let formData = new FormData();
                formData.append('resourceId', this.deleteResource);
                Nova.request().post('/nova-vendor/ProductBuilder/destroyGroup',formData);
                this.deleteResource = 0;
                this.closeDeleteModal();
                this.getHelpsGroups();
            },

            closeDeleteModal() {
                this.deleteResource = 0;
                this.deleteModalOpen = false
            },

            getHelpsGroups(){
                Nova.request().get(`/nova-vendor/ProductBuilder/getGroupHelp`).then((response) => {
                    this.rows = response.data.groups;
                });

            }

        },
        async created(){
            await axios.get(`/nova-vendor/ProductBuilder/getGroupHelp`).then((response) => {
                this.rows = response.data.groups;
            });

            if(!this.resourceName){
                this.resourceName = 'groups';
            }
            this.breadcrumbs=[
                {url:'',label:this.resourceName},
            ];
        }
    }
</script>

<style scoped>

</style>

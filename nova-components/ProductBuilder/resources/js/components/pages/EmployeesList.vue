<template>
    <div>
        <h1 class="mb-3 text-90 font-normal text-2xl">{{headers.heading}}</h1>
        <bread-crumbs :breadcrumbs="breadcrumbs"></bread-crumbs>
        <div class="flex" style="">
            <div class="flex items-center mb-6">
                <div class="flex-no-shrink ml-auto">
                    <!--<router-link :to="{-->
                            <!--name: 'create',-->
                            <!--params: {-->
                                <!--resourceName: 'employees'-->
                            <!--}-->
                        <!--}" class="btn btn-default btn-primary">Create Employee</router-link>-->

                    <button type="button" @click="showAddEmployeeModal(activeDealer)" class="btn btn-default btn-primary">{{__('Create Employee')}}
                    </button>
                </div>
            </div>
        </div>

        <div class="card relative">

            <div class="overflow-hidden overflow-x-auto relative">
                <div class="text-center">
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8">
                            <label class="inline-block text-80 pt-2 leading-tight">
                                Dealer
                            </label></div>
                            <div class="py-6 px-8 w-1/2">
                                <!----> <select @change="setActiveDealer($event)" data-testid="brands-select" dusk="brands" class="form-control form-select mb-3 w-full" v-model="dealerId">
                                <option value="" disabled="disabled" selected="selected">Choose Dealer First</option>
                                <option v-for="dealer of dealers" :value="dealer.id">{{dealer.email}}</option>
                            </select>
                            <!---->
                            <!---->
                            <div class="help-text help-text mt-2">

                            </div>
                        </div>
                    </div>
                </div>
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
                                <div class="text-center text-center" via-resource="" via-resource-id="">
                                    <span v-if="props.row.active == 1" class="inline-block rounded-full w-2 h-2 bg-success"></span>
                                    <span v-else class="inline-block rounded-full w-2 h-2 bg-danger"></span>
                                </div>
                            </span>
                            <span v-else-if="props.column.field == 'iso2_code'">
                                <div style="float:left"><img v-bind:src="props.row.flag" class="rounded-full w-8 h-8 mr-1" style="object-fit: cover;"/></div>
                                <div style="margin-top: 5px;">{{props.row.iso2_code}}</div>
                            </span>
                            <span v-else-if="props.column.field == 'status'">
                                <toggle-button @change="onChangeAccountStatus(props.row.id,props.row.status)"
                                               :value="getBooleanStatus(props.row.status)"          color="#8bc34a"
                                               :sync="true"
                                               :labels="true"/>

                            </span>
                            <span v-else-if="props.column.field == 'action'">
                                <!--<span>-->
                                    <!--<router-link :to="{name: 'edit',params: {resourceName:'employees',resourceId:props.row.id}}" class="cursor-pointer text-70 hover:text-primary mr-3 spp-save" >-->
                                        <!--<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current"><path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path></svg>-->
                                    <!--</router-link>-->
                                <!--</span>-->
                                <span><a title="Edit Employee" href="javascript(0)"
                                         class="cursor-pointer text-70 hover:text-primary mr-3 spp-save"
                                         @click.prevent="editEmployee(props.row)"
                                >
                                       <svg data-v-a2b828c2="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current"><path data-v-a2b828c2="" d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path></svg>


                                </a>
                                </span>
                                <span>
                                    <button
                                        title="Delete Employee"
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

        <!-- Add Employee Modal -->
        <modal v-if="addEmployeeModal" @close="addEmployeeModal = null">

            <div class="bg-white rounded-lg shadow-lg overflow-hidden"
                 style="width: 980px">
                <!-- @submit="checkEmployeeForm" //for validation-->
                <form
                    @submit.prevent="addEmployeeAccounts"
                    method="post"
                    novalidate="true"
                >
                    <input type="hidden" v-model="newEmployee.account_id" name="account_id" />
                    <div class="p-8">
                        <heading :level="2" class="mb-6" v-if="!newEmployee.id">Add Employee </heading>
                        <heading :level="2" class="mb-6" v-else="">Update Employee</heading>
                    </div>

                    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                        <div class="mb-4">

                            <p>
                                <label class="block text-grey-darker text-sm font-bold mb-2" for="email">
                                    First Name
                                </label>

                                <input
                                    id="first_name"
                                    v-model="newEmployee.first_name"
                                    type="text"
                                    name="first_name"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
                                    placeholder="Enter First Name"
                                >
                            </p>

                            <p>
                                <label class="block text-grey-darker text-sm font-bold mb-2" for="email">
                                    Last Name
                                </label>

                                <input
                                    id="last_name"
                                    v-model="newEmployee.last_name"
                                    type="text"
                                    name="first_name"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
                                    placeholder="Enter Last Name"
                                >
                            </p>
                            <p>
                                <label class="block text-grey-darker text-sm font-bold mb-2" for="email">
                                    Email
                                </label>

                                <input
                                    id="employee_email"
                                    v-model="newEmployee.email"
                                    type="email"
                                    :disabled="newEmployee.id > 0"
                                    name="email"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
                                    placeholder="Enter Email"
                                    required>
                                <span v-if="errors.email" class="error">{{errors.email[0]}}</span>
                            </p>

                            <p>
                                <label class="block text-grey-darker text-sm font-bold mb-2" for="email">
                                    Username
                                </label>

                                <input

                                    v-model="newEmployee.username"
                                    type="text"
                                    name="username"
                                    :disabled="newEmployee.id > 0"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
                                    placeholder="Enter Username"
                                    required>
                                <span v-if="errors.username" class="error">{{errors.username[0]}}</span>
                            </p>
                            <p v-if="!newEmployee.id">
                                <label class="block text-grey-darker text-sm font-bold mb-2" for="email">
                                    Password
                                </label>

                                <input
                                    id="password"
                                    v-model="newEmployee.password"
                                    type="text"
                                    name="password"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
                                    placeholder="Enter Password Name"
                                    required>
                                <input type="button" class="button" value="Generate" @click="generateEmployeePassword" tabindex="2">
                                <span v-if="errors.password" class="error">{{errors.password[0]}}</span>
                            </p>

                            <p v-if="!newEmployee.id">
                                <label class="block text-grey-darker text-sm font-bold mb-2" for="confirm_password">
                                    Confirmed Password
                                </label>

                                <input
                                    id="confirmed_password"
                                    v-model="newEmployee.confirmed_password"
                                    type="password"
                                    name="confirmed_password"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
                                    placeholder="Enter Confirmed Password Name"
                                >
                            </p>

                            <p>
                                <label class="block text-grey-darker text-sm font-bold mb-2" for="movie">
                                    Status
                                </label>
                                <input
                                    type="checkbox"
                                    v-model="newEmployee.status"
                                    true-value="1"
                                    false-value="0"

                                >

                            </p>

                            <p>
                                <label class="block text-grey-darker text-sm font-bold mb-2" for="company_id">
                                    Select Company
                                </label>
                                <select
                                    id="company_id"
                                    v-model="newEmployee.company_id"
                                    name="company_id"
                                    class="block appearance-none w-full bg-grey-lighter border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-grey"
                                >
                                    <option value="">Choose Company</option>
                                    <option v-for="company in companies" :value="company.id">{{company.name}}</option>
                                </select>
                            </p>

                        </div>

                    </div>

                    <div class="bg-30 px-6 py-3 flex">
                        <div class="ml-auto">
                            <button type="button" @click="addEmployeeModal = false" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}</button>
                            <span v-show="importLoading"><loader class="mt-1 text-60"/></span>

                            <button v-if="!newEmployee.id" v-show="!importLoading" class="btn btn-default btn-primary "
                            >{{__('Create Employee Account')}}
                            </button>
                            <button v-if="newEmployee.id" v-show="!importLoading" class="btn btn-default btn-primary "
                            >{{__('Update Employee Account')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </modal>

    </div>
</template>

<script>
    import {VueGoodTable} from 'vue-good-table';
    import 'vue-good-table/dist/vue-good-table.css'

    export default {
        name: "employees-list",
        resourceName: {
            type: String,
        },
        data: () => ({
            deleteModalOpen: false,
            deleteResource:0,
            headers:{
                heading:'Employees Listing'
            },
            dealerId:0,
            columns: [
                {
                    label: 'ID',
                    field: 'id',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'First Name',
                    field: 'first_name',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Last Name',
                    field: 'last_name',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Email',
                    field: 'email',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Status',
                    field: 'status',
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
            dealers:[],
            rows: [],
            breadcrumbs:[],
            activeDealer:null,
            addEmployeeModal:null,
            errors: [],
            employee_errors: [],
            newEmployee:{},
            companies:[],
            importLoading:false,
        }),
        components: {
            VueGoodTable,
        },
        methods: {
            getBooleanStatus(status){
                if(status == 1){
                    return true;
                }else{
                    return false;
                }
            },
            openDeleteModal(id) {
                this.deleteResource = id;
                this.deleteModalOpen = true
            },

            confirmDelete() {
                let formData = new FormData();
                formData.append('resourceId', this.deleteResource);
                Nova.request().post('/nova-vendor/ProductBuilder/destroyEmployee',formData);
                this.getEmployee(this.deleteResource);// filter delete
                this.deleteResource = 0;
                this.closeDeleteModal();
                this.getCompanies();
            },

            closeDeleteModal() {
                this.deleteResource = 0;
                this.deleteModalOpen = false
            },
            getEmployee(resource_id){
                this.rows = this.rows.filter(element => element.id != resource_id);
            },
            setActiveDealer(event){

               this.activeDealer = this.dealers.find(x => x.id == event.target.value);
                this.rows = this.activeDealer.employees;
            },
            showAddEmployeeModal(dealer = null){
                if(dealer != null){
                    this.activeDealer = dealer;
                }
                this.confirmed_password ='';

                this.newEmployee = {
                    'first_name': '',
                    'last_name': '',
                    'password': '',
                    'status': '',
                    'email': '',
                    'countries_name': '',
                    'account_id': this.activeDealer.id,
                    'company_id': ''
                };
                this.getCompanies();
                this.errors = [];
                this.addEmployeeModal = true;

            },
            getCompanies(){
                /*Get Companies of this account*/
                Nova.request().get(`/nova-vendor/ProductBuilder/getCompanies/`+this.activeDealer.id).then((response) => {
                    this.companies = response.data.companies;
                });
                /**/
            },
            editEmployee(obj){
                this.newEmployee = obj;
                this.addEmployeeModal = true;
                this.getCompanies();
            },

            // add Employee Accounts
             addEmployeeAccounts(){
                this.errors = [];
                axios.post('/nova-vendor/ProductBuilder/addEmployeeAccounts',
                    this.newEmployee
                ).then(response => {
                    if(response.data.success){
                        this.$toasted.show(response.data.message, { type: 'success' });
                        this.newEmployee = {
                            'first_name': '',
                            'last_name': '',
                            'password': '',
                            'status': '',
                            'email': '',
                            'account_id': this.activeDealer.id,
                            'company_id': ''
                        };
                        if(!this.activeDealer.employees.id){
                            this.activeDealer.employees.push(response.data.employee);
                        }

                        this.employee_errors = [];
                        this.addEmployeeModal = false;
                    }else if(response.data.error != 0){
                        this.$toasted.show(response.data.message, { type: 'error' });
                        this.addEmployeeModal = true;
                    }
                    else{
                        this.$toasted.show(response.data.error, { type: 'error' });
                        this.addEmployeeModal = true;
                    }
                }).catch(error => {
                    if(error.response.status === 422){
                        this.errors = error.response.data.errors;
                    }
                });


            },
            generateEmployeePassword() {
                this.newEmployee.password = '';
                this.newEmployee.password = this.randomPassword(10);
            },
            randomPassword(length) {
                var chars = "abcdefghijklmnopqrstuvwxyz!@#$%^&*()-+<>ABCDEFGHIJKLMNOP1234567890";
                var pass = "";
                for (var x = 0; x < length; x++) {
                    var i = Math.floor(Math.random() * chars.length);
                    pass += chars.charAt(i);
                }
                return pass;
            },
            onChangeAccountStatus(id,active){
                let formData = new FormData();
                formData.append('resourceId', id);
                formData.append('current_status', active);
                Nova.request().post('/nova-vendor/ProductBuilder/updateEmployeeStatus',formData);

                this.$toasted.show(
                    this.__('The :resource are Updated!',{
                        resource: 'Employee',
                    }),
                    {type: 'success'}
                );
            },

        },
        async created(){

            await axios.get(`/nova-vendor/ProductBuilder/getDealerList`).then((response) => {
                this.dealers = response.data.accounts;
            });

            if(this.$route.params.id){
                this.dealerId = this.$route.params.id;
                this.activeDealer = this.dealers.find(x => x.id == this.dealerId);
                this.rows = this.activeDealer.employees;
            }
            if(!this.resourceName){
                this.resourceName = 'Employees';
            }
            this.breadcrumbs=[
                {url:'/dealers',label:'Dealers'},
                {url:'',label:this.resourceName},
            ];
        }
    }
</script>

<style scoped>
    .error{
        color: #f5573b;
    }
</style>

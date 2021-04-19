<template>
    <loading-view :loading="loading">
        <div>
            <h1 class="mb-3 text-90 font-normal text-2xl">{{headers.heading}}</h1>
            <bread-crumbs :breadcrumbs="breadcrumbs"></bread-crumbs>
            <div class="flex" style="">
                <div class="flex items-center mb-6">
                    <div class="flex-no-shrink ml-auto">
                        <router-link :to="{
                            name: 'create-dealer',
                            resourceName:'CreateDealer',
                            label:'Create Dealer'

                        }" class="btn btn-default btn-primary">Create Dealer</router-link>
                        <!--<button type="button" @click="showAddAccountsModel" class="btn btn-default btn-primary">{{__('')}}</button>-->
                        <button type="button" @click="showAddAccountsModel" class="btn btn-default btn-primary">{{__('Manage Folders')}}</button>
                    </div>
                </div>
            </div>

            <div class="card relative">
                <div class="overflow-hidden overflow-x-auto relative">
                    <div class="text-center">
                        <!-- ------------------------------------------------------- -->
                        <div class="flex border-b border-40" v-for="(dropdown,index) in dropdowns" v-if="dropdown.options.length">
                            <div class="w-1/5 py-6 px-8">
                                <label class="inline-block text-80 pt-2 leading-tight">
                                    {{dropdown.label}}
                                </label>
                            </div>
                            <div class="py-6 px-8 w-1/2">
                                <select @change="getFolderData($event,index)"  class="form-control form-select mb-3 w-full">
                                    <option :value="dropdown.default_value">{{dropdown.default_label}}</option>
                                    <option v-if="dropdown.options" v-for="folder in dropdown.options" :value="folder.id">{{folder.name}}</option>
                                </select>
                            </div>
                            <div>
                                <div class="w-1/5 py-6 px-8">
                                 <span v-show="folderDataLoading">
                                    <loader class="mt-1 text-60"/>
                                </span>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div>
                        <vue-good-table
                            ref="my-table"
                            :columns="columns"
                            :rows="rows"
                            :groupOptions="{
  	                        enabled: true,
  	                        headerPosition: 'top'
                        }"
                            :pagination-options="{
                            enabled: true,
                            perPage: 50
                        }"
                            :search-options="{
                            enabled: true,
                            placeholder: 'Search this table',
                        }"
                            :selectOptions="{
                            enabled: true,
                            selectOnCheckboxOnly: true,
                            selectionInfoClass: 'gt-checkbox-col',
                            selectionText: 'Dealer selected',
                            clearSelectionText: 'Clear Selection',
                        }"
                        >

                            <div slot="selected-row-actions">
                                <button class="btn btn-default btn-primary" @click="addToFolder('1')">Create Folder</button>
                                <button class="btn btn-default btn-primary" @click="addToFolder('2')">Update Folder</button>
                            </div>

                            <template slot="table-header-row" slot-scope="props" >
                            <span :data-id="props.row.folder_id" :data-parent-id="props.row.parent_folder" class="folder" v-if="props.row">
                                {{props.row.label}}
                                <button class="fancy-btn btn btn-default btn-primary float-right" v-on:click="toggleButton($event)">Toggle</button>
                            </span>
                            </template>

                            <template slot="table-row" slot-scope="props">
                            <span v-if="props.column.field == 'status'">
                                <span v-if="props.row.status">
                                    <dealer-status
                                        :dealerStatus="dealerStatus"
                                        :dealer="props.row"
                                        :status="getStatusObject(props.row.status)"
                                    />
                                </span>

                                <!--<select @change="setDealerStatus(props.row.id ,props.row.status )" data-testid="brands-select" dusk="brands" class="form-control form-select mb-3 w-full" v-model="props.row.status">
                                <option  v-if="dealerStatus" v-for="(status , index) in dealerStatus" :key="index" :value="index">{{status.label}}</option>
                            </select>-->
                            </span>
                                <span v-else-if="props.column.field == 'login'">
                                    <button
                                        @click="getRefferalToken(props.row.id)"
                                        class="btn btn-default btn-primary">
                                        Login
                                    </button>
                                </span>
                                <span v-else-if="props.column.field == 'brand_access'">
                                <router-link :to="{ path: 'dealers/'+props.row.id+'/brands' }" class="cursor-pointer text-70 hover:text-primary mr- spp-warning" :title="'Assign Brands'">
                                <svg  class="fill-current text-teal inline-block" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path d="M9.26 13a2 2 0 0 1 .01-2.01A3 3 0 0 0 9 5H5a3 3 0 0 0 0 6h.08a6.06 6.06 0 0 0 0 2H5A5 5 0 0 1 5 3h4a5 5 0 0 1 .26 10zm1.48-6a2 2 0 0 1-.01 2.01A3 3 0 0 0 11 15h4a3 3 0 0 0 0-6h-.08a6.06 6.06 0 0 0 0-2H15a5 5 0 0 1 0 10h-4a5 5 0 0 1-.26-10z"></path>
                                </svg>
                                </router-link>
                            </span>
                                <span v-else-if="props.column.field == 'email'">
                                <div style=";">{{props.row.email}}</div>
                            </span>
                                <span v-else-if="props.column.field == 'iso2_code'">
                                <div style="float:left"><img v-bind:src="props.row.flag" class="rounded-full w-8 h-8 mr-1" style="object-fit: cover;"/></div>
                                <div style="margin-top: 5px;">{{props.row.iso2_code}}</div>
                            </span>
                                <span v-else-if="props.column.field == 'action'">
                                 <span>
                                      <router-link title="Edit Dealer" :to="{path: 'edit/dealer/'+props.row.id }"  class="cursor-pointer text-70 hover:text-primary mr-3 spp-save">
                                       <svg data-v-a2b828c2="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current"><path data-v-a2b828c2="" d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path></svg>
                                    </router-link>
                                </span>
                                <span>
                                    <button
                                        title="Delete Dealer"
                                        :data-testid="`${props.row.id}-delete-button`"
                                        :dusk="`${props.row.id}-delete-button`"
                                        class="appearance-none cursor-pointer text-70 hover:text-primary mr-3 spp-delete" @click.prevent="openDeleteModal(props.row.id)">
                                        <icon />
                                    </button>
                                </span>
                                <span>
                                    <button
                                        title="View SMS Credits"
                                        :data-testid="`${props.row.id}-sms-credits-button`"
                                        :dusk="`${props.row.id}-sms-credits-button`"
                                        class="appearance-none cursor-pointer text-70 hover:text-primary mr-3 spp-save" @click.prevent="showSMSCreditsModal(props.row.id, props.row.sms_credits)">
                                        <icon type="view" />
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

            <!-- Add Accounts Modal -->
            <modal v-if="showAddModal" @close="showAddModal = false">

                <div class="bg-white rounded-lg shadow-lg overflow-hidden" style="width: 460px">
                    <div class="p-8">
                        <heading :level="2" class="mb-6" >Manage folder</heading>
                    </div>

                    <table class="table table-condensed table-bordered table-hovered" style="width: 100%">
                        <thead>
                        <tr>
                            <th colspan="2">ID</th>
                            <th colspan="2">Name</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(folder,key) in completefolders">
                            <td colspan="2" align="center">{{folder.id}}</td>
                            <td colspan="2" align="center">{{folder.name}}</td>
                            <td align="center">
                                <button data-v-73ca7226="" title="Delete Folder" data-testid="1-delete-button" dusk="1-delete-button" class="appearance-none cursor-pointer text-70 hover:text-primary mr-3 spp-delete" @click="openDeleteFolderModal(folder.id,key)">
                                    <svg data-v-73ca7226="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current">
                                        <path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>


                    <div class="bg-30 px-6 py-3 flex">
                        <div class="ml-auto">
                            <button type="button" @click="showAddModal = false" class="btn btn-primary btn-default">{{__('Close')}}</button>
                        </div>
                    </div>
                </div>

            </modal>


            <modal v-if="showCreateFolder" @close="showCreateFolder = false">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden" style="width: 460px">
                    <form
                        @submit.prevent="createFolder"
                        method="post"
                        novalidate="true"
                        name="createFolder">
                        <div class="p-8">
                            <heading :level="2" class="mb-6" >{{folder_data.heading}}</heading>
                        </div>
                        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                            <p v-if="showerrors">
                                <b>Please correct the following error(s):</b>
                            <ul class="error_color" v-if="!folder_data.edit">
                                <li>Folder name is required.</li>
                            </ul>
                            <ul class="error_color" v-if="folder_data.edit">
                                <li>Please select a folder.</li>
                            </ul>
                            </p>
                            <div class="mb-4" v-if="!folder_data.edit">
                                <label class="block text-grey-darker text-sm font-bold mb-2" for="folder_id">
                                    Select Parent Folder
                                </label>
                                <select
                                    id="parent_id"
                                    v-model="parent_id"
                                    name="parent_id"
                                    class="block appearance-none w-full bg-grey-lighter border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-grey"
                                >
                                    <option value="0">Save as Parent</option>
                                    <option v-for="folder in folders" v-if="folder.id != selectedFolder" :value="folder.id">{{folder.name}}</option>
                                </select>
                            </div>
                            <div class="mb-4" v-if="!folder_data.edit">
                                <p v-if="!folder_data.edit">
                                    <label class="block text-grey-darker text-sm font-bold mb-2" for="folder_name">
                                        Name
                                    </label>
                                    <input
                                        id="folder_name"
                                        v-model="folder_name"
                                        type="name"
                                        name="folder_name"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
                                        placeholder="Enter Name"
                                        pattern=".+@globex.com"
                                        required>
                                    <span v-if="errors.email" class="error">{{errors.folder_name[0]}}</span>
                                </p>
                            </div>
                            <div v-else>
                                <p>
                                    <label class="block text-grey-darker text-sm font-bold mb-2" for="folder_id">
                                        Select Folder
                                    </label>
                                    <select
                                        id="folder_id"
                                        v-model="folder_id"
                                        name="folder_id"
                                        class="block appearance-none w-full bg-grey-lighter border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-grey"
                                    >
                                        <option value="0">Choose Folder</option>
                                        <option v-for="folder in completefolders" v-if="folder.id != selectedFolder" :value="folder.id">{{folder.name}}</option>
                                    </select>
                                </p>
                            </div>
                        </div>
                        <div class="bg-30 px-6 py-3 flex">
                            <div class="ml-auto">
                                <button type="button" @click="showCreateFolder = false" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}</button>
                                <span v-show="folderLoading">
                                <loader class="mt-1 text-60"/>
                            </span>

                                <button type="submit" v-show="!folderLoading" class="btn btn-default btn-primary "
                                >{{folder_data.button}}
                                </button>

                            </div>
                        </div>
                    </form>

                </div>
            </modal>

            <modal v-if="showSMSCredits" @close="showSMSCredits = false">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden" style="width: 800px;">
                    <div class="p-8">
                        <heading :level="2" class="mb-6" >SMS Credit Balance</heading>
                        <center>
                            <table>
                                <tr>
                                    <td>
                                        <img :src="sms_balance_icon" class="rounded-full w-16 h-16 mr-1">
                                    </td>
                                    <td>
                                        <b class="text-primary font-bold">{{sms_credits}}</b> credit/s left<br/>
                                        <br/>
                                        <i class="text-primary">(¢{{sms_fee}} / sms)</i>
                                    </td>
                                </tr>
                            </table>
                        </center>
                        <br>
                        <heading :level="4" class="mb-6" >SMS Credit Logs</heading> 
                        <table id="messageLogs" class="table table-condensed table-bordered table-hovered" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Top up Credit</th>
                                    <th>Previous Credit</th>
                                    <th>Top up Date</th>
                                </tr>
                            </thead>
                            <tr v-if="sms_credit_logs.length === 0">
                                <td colspan="3">No data found.</td>
                            </tr>
                            <tr v-for="(credit,index) in sms_credit_logs" >
                                <td class="text-center">{{ credit.topup }}</td>
                                <td class="text-center">{{ credit.current }}</td>
                                <td class="text-center">{{ credit.created_at }}</td>
                            </tr>
                        </table>
                        <br>
                        <heading :level="4" class="mb-6" >SMS Message Logs</heading>
                        <table id="messageLogs" class="table table-condensed table-bordered table-hovered" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Segments</th>
                                    <th>Receiver</th>
                                    <th>Message</th>
                                    <th>SMS Sent</th>
                                </tr>
                            </thead>
                            <tr v-if="sms_logs.length === 0">
                                <td colspan="4">No data found.</td>
                            </tr>
                            <tr v-for="(log,index) in sms_logs" >
                                <td class="text-center">
                                    {{log.segments}} 
                                    <br/><i class="text-primary font-bold">¢{{ (log.segments * sms_fee).toFixed(2) }}</i>
                                </td>
                                <td class="text-center">{{ log.email_sms }}</td>
                                <td class="" v-html="log.message"></td>
                                <td class="text-center">{{ log.created_at }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="bg-30 px-6 py-3 flex">
                        <div class="ml-auto">
                            <button type="button" @click="showSMSCredits = false" class="btn btn-primary btn-default">{{__('Close')}}</button>
                        </div>
                    </div>
                </div>
            </modal>

        </div>
    </loading-view>
</template>

<script>
    import {VueGoodTable} from 'vue-good-table';
    import 'vue-good-table/dist/vue-good-table.css'
    import Multiselect from 'vue-multiselect';

    export default {
        name: "dealer-list",
        resourceName: {
            type: String,
        },
        data: () => ({

            headers:{
                heading:'Dealer Listing'
            },
            dropdowns:[

            ],
            deleteModalOpen: false,
            deleteResource:0,
            showAddModal:false,
            showSMSCredits:false,
            showCreateFolder:false,
            accountsDeleteModalOpen: false,
            accountsDeleteResource:0,
            errors: [],
            dealerStatus:[],
            countries:[],
            payment_method:[
                {'id':1,"name":'Braintree'},
                {'id':2,"name":'Stripe'},
                {'id':3,"name":'Eway'},
                {'id':4,"name":'Securepay'}
            ],
            name: null,
            email: null,
            sms_credits: 0,
            max_employees: 0,
            max_dealers: 0,
            max_companies: 0,
            countries_name: '',
            account_id :0,
            movie: null,
            importLoading:false,
            columns: [
                {
                    label: 'ID',
                    field: 'id',
                    type:'number',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                // {
                //     label: 'First Name',
                //     field: 'first_name',
                //     thClass: 'vgt-center-align',
                //     tdClass: 'vgt-center-align',
                // },
                // {
                //     label: 'Last Name',
                //     field: 'last_name',
                //     thClass: 'vgt-center-align',
                //     tdClass: 'vgt-center-align',
                // },
                // {
                //     label: 'Last Name',
                //     field: 'last_name',
                //     thClass: 'vgt-center-align',
                //     tdClass: 'vgt-center-align',
                // },
                {
                    label: 'Company Name',
                    field: 'company_name',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
               /* {
                    label: 'Company Phone',
                    field: 'company_phone',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'City',
                    field: 'city',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },*/
                {
                    label: 'State',
                    field: 'state',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
               /* {
                    label: 'Postal Code',
                    field: 'postal_code',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },*/
                /*{
                    label: 'Username',
                    field: 'username',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },*/
                {
                    label: 'Master Slave',
                    field: 'master_slave',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Account Type',
                    field: 'account_type',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Max Employees',
                    field: 'max_employees',
                    type:'number',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Max Dealers',
                    field: 'max_dealers',
                    type:'number',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Max Companies',
                    field: 'max_companies',
                    type:'number',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'SMS Credits',
                    field: 'sms_credits',
                    type:'number',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                // {
                //     label: 'Payment Plan',
                //     field: 'payment_plan',
                //     thClass: 'vgt-center-align',
                //     tdClass: 'vgt-center-align',
                // },
                {
                    label: 'Brand Access',
                    field: 'brand_access',
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
                    label: 'Login',
                    field: 'login',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                // {
                //     label: 'Company Address',
                //     field: 'company_address',
                //     thClass: 'vgt-center-align',
                //     tdClass: 'vgt-center-align',
                // },
                {
                    label: 'Code',
                    field: 'iso2_code',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Sign Up Date',
                    field: 'created_at',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                /*{
                    label: 'Employees',
                    field: 'employees',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                    sortable: false
                },*/
                {
                    label: 'Action',
                    field: 'action',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                    sortable: false
                },

            ],
            rows: [],
            activeDealer:null,
            newAccounts:{},
            breadcrumbs:[],
            folders:[],
            completefolders:[],
            folderLoading:false,
            ids:[],
            folder_name:null,
            folder_id:0,
            folder_data:{
                heading:'Create Folder',
                button:'Add Folder',
                edit:false
            },
            showerrors:false,
            deleteFolderModalOpen: false,
            deleteFolderResource:0,
            selectedFolder:0,
            folderDataLoading:false,
            parent_id:0,
            subfolders:[],
            selectedSubFolder:0,
            loading:true,
            dealerLoginUrl:"https://dealers.smartpadpro.com.au/api/auth/referrer/",
            sms_balance_icon: "https://www.uts.cw/sites/default/files/pictures/support/icon_checkbalance_sms_1200px.png",
            sms_credit_logs: '',
            sms_fee: '',
            sms_logs: ''
        }),
        components: {
            VueGoodTable,
            Multiselect
        },
        methods: {
            /*
            * function_name: ToggleButton
            * It will toggle all the child rows and sub folders of a folder
            * */
            toggleButton(e){
                let parent = e.target.parentElement.parentNode.parentNode.parentNode.children;
                let display = '';
                if(parent[1]){
                    display = this.toggleDisplay(parent[1],true);
                }

                for (let i = 2 ; i <= parent.length; i++){
                    if(parent[i]){
                        this.toggleDisplay(parent[i], display);
                    }
                }
                this.toggleChildren(e,display);
            },
            // Toggle all the children tbodies and rows of a parent folder
            toggleChildren(e,display){
                let parent = e.target.parentElement;
                let id = parent.getAttribute('data-id');
                if(id != 0){
                    let subfolders = document.getElementsByClassName('subfolder-'+id);
                    for (let i= 0 ; i <= subfolders.length; i++) {
                        if(subfolders[i]){
                            this.toggleDisplay(subfolders[i], display);
                            this.toggleRows(subfolders[i],display);
                            let subfolderId = subfolders[i].getAttribute('data-id');
                            this.toggleSubChildren(subfolderId, display);
                        }
                    }
                }

            },
            // Set display of all child rows of a tbody
            toggleRows(tbody, display){
                let tableRows = tbody.children;
                for(let j = 0; j < tableRows.length; j++){
                    this.toggleDisplay(tableRows[j],display);
                }
            },
            // Recursive function to check n length of subfolders of a folder
            toggleSubChildren(id, display){
                let subfolders = document.getElementsByClassName('subfolder-'+id);
                for (let i= 0 ; i <= subfolders.length; i++) {
                    if(subfolders[i]){
                        this.toggleDisplay(subfolders[i], display);
                        this.toggleRows(subfolders[i],display);
                        let subfolderId = subfolders[i].getAttribute('data-id');
                        this.toggleSubChildren(subfolderId, display)
                    }
                }
            },
            // toggle or set display of an element
            toggleDisplay(e, display){
                if(e){
                    if(display == true){
                        if(e.style.display && e.style.display === 'none'){
                            e.style.display = '';
                        } else {
                            e.style.display = 'none';
                        }
                    }else{
                        e.style.display = display;
                    }

                    return e.style.display;
                }
            },
            getFolderData(e, index){

                if(e){
                    this.selectedFolder = e.target.value;
                }
                this.folderDataLoading = true;
                this.dropdowns =  this.dropdowns.splice(0,index+1);
                Nova.request().get(`/nova-vendor/ProductBuilder/getDealerList/`+this.selectedFolder).then((response) => {
                    this.rows = response.data.accounts;
                    this.folderDataLoading = false;

                    this.selectedSubFolder = 0;
                    this.subfolders = [];

                    setTimeout(function(){
                        this.fillTableClasses();
                    }.bind(this),1000);

                });
                if(this.selectedFolder != this.dropdowns[index].default_value){
                    Nova.request().get(`/nova-vendor/ProductBuilder/getFolders/`+this.selectedFolder).then((response) => {
                        this.dropdowns.push({
                            default_label:'All Sub Folders',
                            default_value:this.selectedFolder,
                            label:'Select Sub Folder',
                            options:response.data.folders
                        });
                    });
                }
            },
            fillSubFolders(event){

                console.log(event.target.value);
            },
            toogledata(data){
                for (var x = 0; x < this.rows.length; x++) {
                    if(data.row.label == this.rows[x].label){
                        console.log(this.rows[x].children);
                        this.$set(this.rows[x], 'children', []);
                    }
                }
            },
            createFolder(){

                if(this.folder_name == null && this.folder_id == 0){
                    this.showerrors = true;
                    return false;
                } else {
                    this.folderLoading = true;
                    let selectedRows = this.$refs['my-table'].selectedRows;
                    selectedRows.forEach((value) => {
                        this.ids.push(value.id);
                    });
                    let formData = new FormData();
                    formData.append('name', this.folder_name);
                    formData.append('folder_id', this.folder_id);
                    formData.append('parent_id', this.parent_id);
                    formData.append('ids', this.ids);
                    Nova.request().post('/nova-vendor/ProductBuilder/addDealerFolder', formData).then((response) => {
                        if (response.data.success) {
                            this.$toasted.show(
                                this.__('Action Applied Successfully!', {
                                    resource: 'Product',
                                }),
                                {type: 'success'}
                            );
                        } else {
                            this.$toasted.show(
                                this.__('Action Failed!', {
                                    resource: 'Product',
                                }),
                                {type: 'error'}
                            );
                        }
                        if(response.data.folder){
                            this.folders.push(response.data.folder);
                        }
                        this.reload();
                    });

                }

            },
            addToFolder(action){

                if(action == 2){
                    this.getFolders();
                    this.folder_data.heading = 'Update Folder';
                    this.folder_data.button = 'Update';
                    this.folder_data.edit = true;
                } else {
                    this.folder_data.heading = 'Create Folder';
                    this.folder_data.button = 'Add Folder';
                    this.folder_data.edit = false;
                }
                this.showCreateFolder = true;
            },
            getFolders(){
                Nova.request().get(`/nova-vendor/ProductBuilder/getFolders`).then((response) => {
                    this.folders = response.data.folders;
                });
            },
            reload(){
                Nova.request().get(`/nova-vendor/ProductBuilder/getDealerList/`+this.selectedFolder).then((response) => {
                    this.rows = response.data.accounts;
                    this.folder_data.edit = false;
                    this.folder_id = 0;
                    this.ids = [];
                    this.folderLoading = false;
                    this.showCreateFolder = false;
                    setTimeout(function(){
                        this.fillTableClasses();
                    }.bind(this),1000);
                });
            },
            getBooleanStatus(status){
                if(status == 1){
                    return true;
                }else{
                    return false;
                }
            },
            showAddAccountsModel(){
                this.newAccounts = {
                    'email': '',
                    'sms_credits': '0',
                    'max_employees': '0',
                    'max_dealers': '0',
                    'max_companies': '0',
                    'countries_name': '',
                    'payment_method': '',
                    'username': '',
                    'password': '',
                    'confirmed_password': '',
                    'status': '',
                };
                this.confirmed_password='';
                this.errors = [];
                this.showAddModal = true;
            },
            async editAccounts(obj){
                //console.log(obj);
                let formData = new FormData();
                formData.append('account_Id', obj.id);
                const response = await Nova.request().post('/nova-vendor/ProductBuilder/getEmployeeRecord',formData);

                let opts = {};
                Object.assign(opts,obj, response.data.employee);
                this.newAccounts = opts;
                this.showAddModal = true;
            },

            // add Accounts
            addAccounts(){
                this.errors = [];
                axios.post('/nova-vendor/ProductBuilder/addAccounts',
                    this.newAccounts
                ).then(response => {
                    if(response.data.success){
                        this.$toasted.show(response.data.message, { type: 'success' });
                        this.showAddAccountsModel();
                        this.showAddModal = false;
                        if(response.data.accounts.accounts){
                            this.rows.push(response.data.accounts.accounts);
                        }else{
                            let editedDealerIndex = this.rows.findIndex(x => x.id == response.data.accounts.id);
                            if(editedDealerIndex >=0) {
                                this.rows[editedDealerIndex].sms_credits = response.data.accounts.sms_credits;
                                this.rows[editedDealerIndex].max_employees = response.data.accounts.max_employees;
                                this.rows[editedDealerIndex].max_dealers = response.data.accounts.max_dealers;
                                this.rows[editedDealerIndex].max_companies = response.data.accounts.max_companies;
                                this.rows[editedDealerIndex].status = response.data.accounts.status;
                            }
                        }
                    }else if(response.data.error != 0){
                        this.$toasted.show(response.data.message, { type: 'error' });
                        this.showAddModal = true;
                    }
                    else{
                        this.$toasted.show(response.data.error, { type: 'error' });
                        this.showAddModal = true;
                    }
                }).catch(error => {
                    if(error.response.status === 422){
                        this.errors = error.response.data.errors;
                    }
                });


            },

            openDeleteModal(id,key) {
                this.deleteResource = id;
                this.deleteModalOpen = true;
            },

            showSMSCreditsModal(id, sms_credits)
            {
                this.showSMSCredits = true;
                this.sms_credits = sms_credits;
                var div = document.getElementById('messageLogs');
                Nova.request().get('/nova-vendor/ProductBuilder/getSMSLogs/'+id).then((response) => {
                    this.sms_logs = response.data.sms_logs;
                    this.sms_credit_logs = response.data.sms_credit_logs;
                    this.sms_fee = response.data.sms_fee;
                });
            },

            confirmDelete() {
                let formData = new FormData();
                formData.append('resourceId', this.deleteResource);
                Nova.request().post('/nova-vendor/ProductBuilder/destroyAccounts',formData);

                this.getAccounts(this.deleteResource);// filter delete
                this.deleteResource = 0;
                this.closeDeleteModal();
            },

            closeDeleteModal() {
                this.deleteResource = 0;
                this.deleteModalOpen = false;
                this.deleteFolderModalOpen = false;
                this.showAddModal = false;
                this.deleteFolderResource=0;

            },

            openDeleteFolderModal(id,key) {
                //alert(key);
                this.deleteFolderResource = id;
                var r = confirm("Are you want to delete this folder. it will unlink the dealers?");
                if (r == true) {
                    let formData = new FormData();
                    formData.append('resourceId', this.deleteFolderResource);
                    Nova.request().post('/nova-vendor/ProductBuilder/destroyFolder',formData);
                    this.reload();
                    this.completefolders.splice(key,1);
                    this.getFolders();
                    this.deleteResource = 0;
                    this.closeDeleteModal();
                }
            },

            confirmFolderDelete() {

            },

            getAccounts(resource_id){
                this.rows= this.rows.filter(element => element.id != resource_id);
            },
            // add Employee Accounts
            async addEmployeeAccounts(){
                const response = await Nova.request().post('/nova-vendor/ProductBuilder/addEmployeeAccounts',
                    this.newEmployee
                );
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

            },
            openEmployeeDeleteModal(id) {
                this.employeeDeleteResource = id;
                this.employeeDeleteModalOpen = true
            },

            confirmEmployeeDelete() {
                let formData = new FormData();
                formData.append('resourceId', this.employeeDeleteResource);
                Nova.request().post('/nova-vendor/ProductBuilder/destroyEmployee',formData);
                this.employeeDeleteResource = 0;
                this.getAccounts();
                this.closeEmployeeDeleteModal();
            },

            closeEmployeeDeleteModal() {
                this.employeeDeleteResource = 0;
                this.employeeDeleteModalOpen = false
            },

            generatePassword() {
                this.newAccounts.password = '';
                this.newAccounts.password = this.randomPassword(10);
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
                Nova.request().post('/nova-vendor/ProductBuilder/updateAccountStatus',formData);

                this.$toasted.show(
                    this.__('The :resource are Updated!',{
                        resource: 'Account',
                    }),
                    {type: 'success'}
                );
            },
            onChangeEmployeeStatus(id,active){
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
            
            getStatusObject(status){
                let obj = this.dealerStatus.find(elem => elem.id == status);
                if(obj){
                    return obj;
                }
                else{
                    return 0;
                }
            },
            fillTableClasses(){
                let folders_key_map = [];
                let folders = document.getElementsByClassName('folder');
                for(let i =0; i < folders.length ; i++){

                    let dataId = folders[i].getAttribute('data-id');
                    let parentId = folders[i].getAttribute('data-parent-id');
                    let th = folders[i].parentElement;
                    let tr = th.parentElement;
                    let tbody = tr.parentElement;
                    tbody.className += ' subfolder-'+parentId;
                    tbody.setAttribute('data-id',dataId);
                    if(parentId > 0){
                        folders_key_map.push({id:dataId, parentId:parentId});
                    }

                }
            },
            /*
            * get Refferal Token
            * @param: dealer id
            * */
            async getRefferalToken(id){
                const uuidv4 = require('uuid/v4');
                let token = uuidv4();
                let fd = new FormData();
                fd.append('token',token);
                await Nova.request().post('/nova-vendor/ProductBuilder/setRefferalToken/'+id,fd).then((response) => {
                    if(response.data.success){
                        let url = this.dealerLoginUrl + token;
                        let opened = window.open(url, "_blank");
                        if(!opened){
                            alert('Please Allow Popups for the website');
                        }
                    }else{
                        console.log('invalid id');
                    }
                });
            }
        },
        async created(){
            /**
             * Get Dealer Status
             * **/
            Nova.request().get('/nova-vendor/ProductBuilder/getDealerStatus/').then((response) => {
                //console.log(response.data.dealerStatus);
                this.dealerStatus = response.data.dealerStatus;

            });
            await Nova.request().get('/nova-vendor/ProductBuilder/getDealerList/'+this.selectedFolder).then((response) => {
                this.rows = response.data.accounts;
                setTimeout(function(){
                    this.fillTableClasses();
                }.bind(this),1000);

            });
            const response = await Nova.request().get('/nova-vendor/ProductBuilder/getActiveCountry');
            this.countries = response.data.countries;

            Nova.request().get(`/nova-vendor/ProductBuilder/getAllFolders`).then((response) => {
                this.folders = response.data.folders;
                this.completefolders = response.data.folders;
                this.dropdowns.push({
                    default_label:'All Folders',
                    default_value:0,
                    label:'Select Folder',
                    options:response.data.folders
                });
            });

            if(!this.resourceName){
                this.resourceName = 'dealers';
            }
            this.breadcrumbs=[
                {url:'',label:this.resourceName},
            ];

            this.loading = false;
        },
        mounted(){
        }
    }
</script>

<style scoped>
    .truncateMessage{
        max-width: 200px !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
    input,select {
        margin-left: 10px;
    }
    .error{
        color: #f5573b;
        padding-left: 11px;
    }

</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

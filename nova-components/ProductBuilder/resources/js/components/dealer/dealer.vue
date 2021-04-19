<template>
    <div>
        <bread-crumbs :breadcrumbs="breadcrumbs"></bread-crumbs>
        <heading class="mb-3">{{heading}}</heading>

        <form  @submit.prevent="addAccounts" id="app" method="post" novalidate="true" name="accountForm">
            <card class="overflow-hidden">
                <!-- Validation Errors -->
                <validation-errors :errors="validationErrors"/>
                <div>
                    <div>
                        <div class="flex border-b border-40">
                            <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                                Account Type
                            </label>
                            </div>
                            <div class="py-6 px-8 w-1/2">
                                <select v-model="activeDealer.master_slave" class="w-full form-control select-box form-input-bordered">
                                    <option value="standard">Standard Dealer</option>
                                    <option value="master">Master</option>
                                    <option value="trial">30 Day Trial Account</option>
                                </select>
                                <div class="help-text help-text mt-2">
                                </div>
                            </div>
                        </div>
                        <div class="flex border-b border-40" v-show="activeDealer.master_slave == 'trial'">
                            <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                                Trial Starting Date
                            </label>
                            </div>
                            <div class="py-6 px-8 w-1/2">
                                <datetime type="date"
                                          v-model="activeDealer.trial_date"
                                          title="Choose Start Date"
                                          input-id="startDate"
                                          input-class="date-filters"
                                          :max-datetime="current_date"
                                          input-style="border:1px solid darkgray; padding:10px;text-align:center"
                                          input-placeholder="Start Date">
                                    <label for="startDate" slot="before">Choose Start Date &nbsp;&nbsp;</label>
                                </datetime>
                            </div>
                        </div>
                        <div class="flex border-b border-40">
                            <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                                Connect As Slave To
                            </label>
                            </div>
                            <div class="py-6 px-8 w-1/2">
                                <select v-model="activeDealer.master_id" class="w-full form-control select-box form-input-bordered">
                                    <option value="0">Select Account</option>
                                    <option v-if="notslave" v-for="account in notslave" :value="account.id">{{account.company_name}}</option>
                                </select>
                                <div class="help-text help-text mt-2">
                                </div>
                            </div>
                        </div>

                        <div class="flex border-b border-40">
                            <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                                Status
                            </label>
                            </div>
                            <div class="py-6 px-8 w-1/2">
                                <multiselect :options="dealerStatus"
                                                   v-model="activeDealer.status"
                                                   track-by="id"
                                                   label="label"
                                                   placeholder="Select Status"
                                                 :option-height="40"
                                                 :searchable="false"
                                                 :allow-empty="false"
                                >

                                    <template slot="singleLabel" slot-scope="props">
                                        <span contenteditable="false" v-html="props.option.icon"></span> &nbsp;
                                        <span class="option__title">{{ props.option.label }}</span>
                                    </template>
                                    <template slot="option" slot-scope="props">
                                        <span contenteditable="false" v-html="props.option.icon"></span>
                                        <span class="option__title">{{ props.option.label }}</span>
                                    </template>

                                </multiselect>

                                <div class="help-text help-text mt-2">
                                    <help-text class="text-center error-text mt-2 text-danger"
                                               v-if="validationErrors.hasOwnProperty('errors') &&
                               validationErrors.errors.hasOwnProperty('status')">
                                        {{validationErrors.errors.status[0]}}
                                    </help-text>
                                </div>
                            </div>
                        </div>
                        <div class="flex border-b border-40">
                            <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                                Account Detail
                            </label>
                            </div>
                            <div class="py-6 px-8 w-1/2">
                                <select v-model="activeDealer.account_type" class="w-full form-control select-box form-input-bordered">
                                    <option value=""  selected="selected">Choose Account Type</option>
                                    <option v-if="accountstype" v-for="account in accountstype" :value="account.id">{{account.name}}</option>
                                </select>
                                <div class="help-text help-text mt-2">
                                    <help-text class="text-center error-text mt-2 text-danger"
                                               v-if="validationErrors.hasOwnProperty('errors') &&
                               validationErrors.errors.hasOwnProperty('account_type')">
                                        {{validationErrors.errors.account_type[0]}}
                                    </help-text>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div via-resource="" via-resource-id="" via-relationship="" class="flex border-b border-40">
                                <div class="w-1/5 py-6 px-8">
                                    <label for="Notes" class="inline-block text-80 pt-2 leading-tight">Notes</label>
                                </div>
                                <div class="py-6 px-8 w-4/5"><span style="display: none;">
                                    <svg viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="mt-1 text-60 mx-auto block" style="width: 50px;"><circle cx="15" cy="15" r="13.2851"><animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate></circle><circle cx="60" cy="15" r="10.7149" fill-opacity="0.3"><animate attributeName="r" from="9" to="9" begin="0s" dur="0.8s" values="9;15;9" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="0.5" to="0.5" begin="0s" dur="0.8s" values=".5;1;.5" calcMode="linear" repeatCount="indefinite"></animate></circle><circle cx="105" cy="15" r="13.2851"><animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate></circle></svg></span>
                                    <textarea id="Notes" v-model="activeDealer.notes" rows="5" class="w-full form-control form-input form-input-bordered py-3 h-auto"></textarea>
                                    <div class="help-text help-text mt-2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex border-b border-40">
                            <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                                Max Employees
                            </label>
                            </div>
                            <div class="py-6 px-8 w-1/2">
                                <input type="number" v-model="activeDealer.max_employees" class="w-full form-control form-input form-input-bordered"> <!---->
                                <div class="help-text help-text mt-2">
                                </div>
                            </div>
                        </div>
                        <div class="flex border-b border-40">
                            <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                                Max Dealers
                            </label>
                            </div>
                            <div class="py-6 px-8 w-1/2">
                                <input type="number" v-model="activeDealer.max_dealers" class="w-full form-control form-input form-input-bordered"> <!---->
                                <div class="help-text help-text mt-2">
                                </div>
                            </div>
                        </div>
                        <div class="flex border-b border-40">
                            <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                                Max Companies
                            </label>
                            </div>
                            <div class="py-6 px-8 w-1/2">
                                <input type="number" v-model="activeDealer.max_companies"  class="w-full form-control form-input form-input-bordered"> <!---->
                                <div class="help-text help-text mt-2">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            First Name
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input autocomplete="off" type="text" v-model="activeDealer.first_name" placeholder="First Name" class="w-full form-control form-input form-input-bordered"> <!---->
                            <div class="help-text help-text mt-2">
                            </div>
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            Last Name
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input autocomplete="off" type="text" v-model="activeDealer.last_name" placeholder="Last Name" class="w-full form-control form-input form-input-bordered"> <!---->
                            <div class="help-text help-text mt-2">
                            </div>
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            Company Name
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="text" v-model="activeDealer.company_name" @input="setUsername(activeDealer.company_name)" placeholder="Company Name" class="w-full form-control form-input form-input-bordered"> <!---->
                            <div class="help-text help-text mt-2">
                                <help-text class="text-center error-text mt-2 text-danger"
                                           v-if="validationErrors.hasOwnProperty('errors') &&
                               validationErrors.errors.hasOwnProperty('company_name')">
                                    {{validationErrors.errors.company_name[0]}}
                                </help-text>
                            </div>
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            Company Address
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <vue-google-autocomplete
                                v-model="activeDealer.address"
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
                            <input type="text" v-model="activeDealer.company_address" placeholder="Company Address" class="w-full form-control form-input form-input-bordered"> <!---->
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
                            <input type="text" v-model="activeDealer.city" @input="setUsernameCity(activeDealer.company_name,activeDealer.city)" placeholder="City" class="w-full form-control form-input form-input-bordered"> <!---->
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
                            <input type="text" v-model="activeDealer.state" placeholder="State" class="w-full form-control form-input form-input-bordered">
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
                            <input type="text" v-model="activeDealer.postal_code" placeholder="Zip / Postal Code" class="w-full form-control form-input form-input-bordered"> <!---->
                            <div class="help-text help-text mt-2">
                            </div>
                        </div>
                    </div>


                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            Country
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <model-list-select :list="countries"
                                               v-model="activeDealer.country_id"
                                               option-value="id"
                                               option-text="name"
                                               placeholder="Select a Country"
                                               isDisabled="true"
                            ></model-list-select>
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
                            <input type="text" v-model="activeDealer.company_phone" placeholder="Company Phone" class="w-full form-control form-input form-input-bordered"> <!---->
                            <div class="help-text help-text mt-2">
                            </div>
                        </div>
                    </div>

                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            Company Email
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="email" :disabled="editDealer" v-model="activeDealer.email" placeholder="Company Email" class="w-full form-control form-input form-input-bordered"> <!---->
                            <div class="help-text help-text mt-2">
                                <help-text class="text-center error-text mt-2 text-danger"
                                           v-if="validationErrors.hasOwnProperty('errors') &&
                               validationErrors.errors.hasOwnProperty('email')">
                                    {{validationErrors.errors.email[0]}}
                                </help-text>
                            </div>
                        </div>
                    </div>


                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            UserName
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="text" v-model="activeDealer.username" :disabled="editDealer" placeholder="Company UserName" class="w-full form-control form-input form-input-bordered"> <!---->
                            <div class="help-text help-text mt-2">
                                <help-text class="text-center error-text mt-2 text-danger"
                                           v-if="validationErrors.hasOwnProperty('errors') &&
                               validationErrors.errors.hasOwnProperty('username')">
                                    {{validationErrors.errors.username[0]}}
                                </help-text>
                            </div>
                        </div>
                    </div>
                    <div class="flex border-b border-40" v-if="activeDealer.id">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            Brand Access
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <router-link :to="{ path: 'dealers/'+activeDealer.id+'/brands' }" class="cursor-pointer text-70 hover:text-primary mr- spp-warning" :title="'Assign Brands'">
                                <svg  class="fill-current text-teal inline-block" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path d="M9.26 13a2 2 0 0 1 .01-2.01A3 3 0 0 0 9 5H5a3 3 0 0 0 0 6h.08a6.06 6.06 0 0 0 0 2H5A5 5 0 0 1 5 3h4a5 5 0 0 1 .26 10zm1.48-6a2 2 0 0 1-.01 2.01A3 3 0 0 0 11 15h4a3 3 0 0 0 0-6h-.08a6.06 6.06 0 0 0 0-2H15a5 5 0 0 1 0 10h-4a5 5 0 0 1-.26-10z"></path>
                                </svg></router-link>
                            <div class="help-text help-text mt-2">

                            </div>
                        </div>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            Admin Dashboard Access
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <select v-model="activeDealer.has_admin_dashboard" class="w-full form-control select-box form-input-bordered" placeholder="Please Select">
                                <option value="1">Activate</option>
                                <option value="0">Deactivate</option>
                            </select>
                            <div class="help-text help-text mt-2">
                            </div>
                        </div>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            Price controlled by Master
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <span>
                                <toggle-button @change="updatePriceControlled($event)"
                                               :value="activeDealer.price_controlled_by_master"
                                               color="#8bc34a"
                                               :sync="true"
                                               :labels="true"/>
                            </span>
                        </div>
                    </div>
                    <!--<div class="flex border-b border-40">-->
                        <!--<div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">-->
                            <!--Password-->
                        <!--</label>-->
                        <!--</div>-->
                        <!--<div class="py-6 px-8 w-1/2">-->
                            <!--<vue-password v-model="activeDealer.password"-->
                                          <!--classes="input w-full form-control form-input form-input-bordered"-->
                                          <!--autocomplete="off"-->
                                         <!---->
                                          <!--placeholder="Password"-->
                            <!--&gt;-->
                            <!--</vue-password>-->
                            <!--<input type="button" class="button" value="Generate" @click="generatePassword" tabindex="2">-->
                            <!--<div class="help-text help-text mt-2">-->
                                <!--<help-text class="text-center error-text mt-2 text-danger"-->
                                           <!--v-if="validationErrors.hasOwnProperty('errors') &&-->
                               <!--validationErrors.errors.hasOwnProperty('password')">-->
                                    <!--{{validationErrors.errors.password[0]}}-->
                                <!--</help-text>-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</div>-->

                    <!--<div class="flex border-b border-40" v-if="!editDealer">-->
                        <!--<div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">-->
                            <!--Confirm Password-->
                        <!--</label>-->
                        <!--</div>-->
                        <!--<div class="py-6 px-8 w-1/2">-->
                            <!--<vue-password v-model="activeDealer.confirm_password"-->
                                          <!--classes="input w-full form-control form-input form-input-bordered"-->
                                          <!--autocomplete="off"-->

                                          <!--placeholder="Confirm Password"-->
                            <!--&gt;-->
                            <!--</vue-password>-->
                            <!--<div class="help-text help-text mt-2">-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</div>-->

                </div>
            </card>
            <br/>
            <card class="overflow-hidden">
                <div>
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            SMS Credits <br/><b>({{activeDealer.sms_credits}} Balance)</b>
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="number" v-model="activeDealer.sms_credits_topup"  class="w-full form-control form-input form-input-bordered"> <!---->
                            <div class="help-text help-text mt-2">
                            </div>
                        </div>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            SMS Credits Recurring
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <input type="number" v-model="activeDealer.sms_credits_recurring"  class="w-full form-control form-input form-input-bordered"> <!---->
                            <div class="help-text help-text mt-2">
                            </div>
                        </div>
                    </div>
                    <div class="flex border-b border-40">
                        <div class="w-1/5 py-6 px-8"><label class="inline-block text-80 pt-2 leading-tight">
                            SMS Credits Recurring Start
                        </label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <datetime type="date"
                                      v-model="activeDealer.sms_credits_recurring_start"
                                      title="Choose Start Date"
                                      input-id="sms_credits_recurring_start"
                                      input-class="date-filters form-control form-input form-input-bordered"
                                      :max-datetime="current_date"
                                      :input-value="activeDealer.sms_credits_recurring_start"
                                      input-style="border:1px solid darkgray; padding:10px;text-align:center;width:100%;"
                                      input-placeholder="Start Date">
                            </datetime>
                        </div>
                    </div>

                    <div class="float-right" style="padding-top: 2%; padding-bottom: 2%; margin-right: 50px;">
                        <!--<button dusk="create-button" class="btn btn-default btn-primary">-->
                            <!--{{__('Cancel')}}-->
                        <!--</button>-->
                        <span v-show="postLoading">
                            <loader class="mt-1 text-60"/>
                        </span>
                        <button v-show="!postLoading" dusk="create-button" class="btn btn-default btn-primary bg-green">
                            {{__('Save')}}
                        </button>
                    </div>
                </div>
            </card>
        </form>


    </div>

</template>

<script>
    import Vue from 'vue';
    import {Errors, InteractsWithResourceInformation} from 'laravel-nova';
    import {ModelListSelect} from 'vue-search-select';
    import Multiselect from 'vue-multiselect';
    import VueGoogleAutocomplete from 'vue-google-autocomplete';
    import VuePassword from 'vue-password';
    import {Datetime} from 'vue-datetime';
    import 'vue-datetime/dist/vue-datetime.css';

    export default {
        components: {
            VueGoogleAutocomplete ,
            ModelListSelect,
            Multiselect,
            VuePassword,
            Datetime
        },
        mixins: [InteractsWithResourceInformation],
        name: "dealer",
        data: () => ({
            heading:'Create Dealer',
            loading: true,
            postLoading: false,
            validationErrors: new Errors(),
            editDealer:false,
            countries:[],
            dealerStatus:[],
            accountstype:[],
            notslave:[],
            activeCountry:null,
            address:'',
            current_date:'',
            activeDealer:{
                trial_date:'',
                notes:'',
                account_id:0,
                master_slave:'standard',
                status:'',
                account_type:'',
                max_employees:0,
                max_dealers:0,
                max_companies:0,
                first_name:'',
                last_name:'',
                company_name:'',
                address:'',
                company_address:'',
                company_phone:'',
                email:'',
                city:'',
                state:'',
                country_id:0,
                postal_code:'',
                username:'',
                password:'',
                confirm_password:'',
                master_id:0,
                sms_credits:0,
                sms_credits_recurring:0,
                sms_credits_recurring_start:'',
                has_admin_dashboard:0,
                sms_credits_topup:0,
                price_controlled_by_master:0
            },
        }),
        async created() {

            /**
             * Get Dealer Status
             * **/
            Nova.request().get(`/nova-vendor/ProductBuilder/getDealerStatus`).then((response) => {
                this.dealerStatus = response.data.dealerStatus;
            });

            Nova.request().get(`/nova-vendor/ProductBuilder/getAccountTypes`).then((response) => {
                this.accountstype = response.data.types;
            });

            Nova.request().get(`/nova-vendor/ProductBuilder/getNotSlave`).then((response) => {
                this.notslave = response.data.accounts;
            });

            //console.log(this.notslave);

            if(this.$route.params.account_id){
                this.editDealer = true;
                this.heading = "Update Dealer";
                Nova.request().get(`/nova-vendor/ProductBuilder/getDealerInfo/`+this.$route.params.account_id).then((response) => {
                    //console.log(response.data.account);
                    this.activeDealer = response.data.account;
                    this.activeDealer.status = this.dealerStatus.find(x => x.id == response.data.account.status );
                    this.activeDealer.account_id = this.$route.params.account_id;
                    this.activeDealer.password = '';
                    this.activeDealer.sms_credits = response.data.account_sms.sms_credits;
                    this.activeDealer.sms_credits_recurring = response.data.account_sms.sms_credits_recurring;
                    this.activeDealer.sms_credits_recurring_start = response.data.account_sms.sms_credits_recurring_start;
                });
            }

            //console.log(this.activeDealer.status);

            this.breadcrumbs=[
                {url:'/dealers',label:'Dealers'},
                {url:'',label: this.heading},
            ];

            this.setCountries();
            //
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1; //January is 0!
            var yyyy = today.getFullYear();
            today = yyyy + '-' + mm + '-' + dd ;
            this.current_date = today;
            this.loading = false;


        },
        methods:{
            setUsername(company){
                if(this.activeDealer.city){
                    this.activeDealer.username = company.toLowerCase().replace(/\s/g, '')+'_'+this.activeDealer.city.toLowerCase().replace(/\s/g, '');
                } else {
                    this.activeDealer.username = company.toLowerCase().replace(/\s/g, '');
                }
            },
            setUsernameCity(company,city){
                this.activeDealer.username = company.toLowerCase().replace(/\s/g, '')+'_'+city.toLowerCase().replace(/\s/g, '');
            },
            setCountries(){
                Nova.request().get(`/nova-vendor/ProductBuilder/countries`).then((response) => {
                    this.countries = response.data.countries;
                    this.activeCountry = this.getSelectedCountry();
                    this.activeDealer.country_id = this.activeCountry.id;
                });

            },
            getSelectedCountry(){
                return this.countries.find(x => x.selected == 1 );
            },
            getAddressData: function (addressData, placeResultData, id) {

                this.activeDealer.city = placeResultData.address_components[ this.findAddressIndex(placeResultData.address_components, 'locality') ].short_name;
                this.activeDealer.state = placeResultData.address_components[this.findAddressIndex(placeResultData.address_components, 'administrative_area_level_1') ].long_name;
                this.activeDealer.postal_code = placeResultData.address_components[this.findAddressIndex(placeResultData.address_components, 'postal_code')].short_name;
                this.activeDealer.address = addressData.street_number+' '+addressData.route;
            },
            findAddressIndex(obj, needle){
                let index = obj.findIndex(elem => elem.types.indexOf(needle)>=0);
                return index;
            },
            addAccounts(){
                this.postLoading = true;
                this.activeDealer.status = this.activeDealer.status.id;
                axios.post('/nova-vendor/ProductBuilder/addAccounts',
                    this.activeDealer
                ).then(response => {
                    this.postLoading = false;
                    if(response.data.success){
                        if(this.$route.params.account_id > 0 ) {
                            Nova.request().get(`/nova-vendor/ProductBuilder/getDealerInfo/`+this.$route.params.account_id).then((response) => {
                                this.activeDealer = response.data.account;
                                this.activeDealer.account_id = this.$route.params.account_id;
                                this.activeDealer.password = '';
                                this.activeDealer.status = this.dealerStatus.find(x => x.id == response.data.account.status );
                                this.activeDealer.sms_credits = response.data.account_sms.sms_credits;
                                this.activeDealer.sms_credits_recurring = response.data.account_sms.sms_credits_recurring;
                                this.activeDealer.sms_credits_recurring_start = response.data.account_sms.sms_credits_recurring_start;
                            });
                        } else {
                            this.activeDealer = {};
                        }
                        this.validationErrors = {};
                        this.$toasted.show(response.data.message, { type: 'success' });
                    }

                }).catch(error => {
                    if (error.response.status == 422) {
                        this.activeDealer.status = this.dealerStatus.find(x => x.id == this.activeDealer.status );
                        this.postLoading = false;
                        this.validationErrors = new Errors(error.response.data.errors);
                        this.$toasted.show('The given data was invalid. check the error(s).', { type: 'error' });
                    }
                });
            },
            generatePassword() {
                this.activeDealer.password = '';
                this.activeDealer.password = this.randomPassword(10);
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
            updatePriceControlled(event)
            {
                this.activeDealer.price_controlled_by_master = event.value;
            }
        },
        mounted() {
            // To demonstrate functionality of exposed component functions
            // Here we make focus on the user input
            this.$refs.address.focus();
        },

    }
</script>

<style scoped>
    .option__title{
        font-size:18px;
        margin-bottom:3px;
    }
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<template>

    <div>
        <h1 class="mb-3 text-90 font-normal text-2xl">{{headers.heading}}</h1>
        <bread-crumbs :breadcrumbs="breadcrumbs"></bread-crumbs>
        <div class="flex" style="">
            <div class="flex items-center mb-6">
                <div class="flex-no-shrink ml-auto">

                </div>
            </div>
        </div>


        <div class="card relative">
            <div class="overflow-hidden overflow-x-auto relative">
                <div class="w-full" style="padding:10px">
                    <h2 class="mb-3 text-90 font-normal text-2xl">Get Logs by Date</h2>

                    <div class="flex">

                <span class="w-2/6 flex-1 rounded-l p-3 choose-field" >
                    <datetime
                        type="date"
                        v-model="from_date"
                        title="Choose Start Date"
                        input-id="startDate"
                        input-class="date-filters"
                        :max-datetime="current_date"
                        input-style="border:1px solid darkgray; padding:10px;text-align:center"
                        input-placeholder="Start Date"
                    >
                        <label for="startDate" slot="before">Choose Start Date &nbsp;&nbsp;</label>

                    </datetime>
                </span>

                        <span class="w-2/6 flex-1 rounded-l p-3 choose-field">
                    <datetime
                        type="date"
                        v-model="to_date"
                        title="Choose End Date"
                        input-id="endDate"
                        input-class="date-filters"
                        :min-datetime="from_date"
                        :max-datetime="current_date"
                        input-style="border:1px solid darkgray; padding:10px;text-align:center"
                    >
                        <label for="endDate" slot="before">Choose End Date  &nbsp;&nbsp;</label>

                    </datetime>

                </span>
                        <span class="w-2/6 flex-1 rounded-l p-3 focus:outline-none">
                    <input type="button" class="btn btn-default btn-primary" value="Get Logs" @click="refreshLogs">
                </span>

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

                            <span v-if="props.column.field == 'content'">

                                <div style="margin-top: 5px;" contenteditable="false" v-html="props.row.content"></div>
                            </span>
                            <span v-else-if="props.column.field == 'text_color'">

                                <div style="display:inline-block;width:40px;height:20px;border:1px solid black" v-bind:style="{backgroundColor:props.row.text_color}"></div>
                            </span>
                            <span v-else-if="props.column.field == 'background_color'">

                                <div style="display:inline-block;width:40px;height:20px;border:1px solid black" v-bind:style="{backgroundColor:props.row.background_color}"></div>
                            </span>
                            <span v-else>
                                {{props.formattedRow[props.column.field]}}
                            </span>
                        </template>
                    </vue-good-table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {VueGoodTable} from 'vue-good-table';
    import 'vue-good-table/dist/vue-good-table.css'
    import {Datetime} from 'vue-datetime';
    import 'vue-datetime/dist/vue-datetime.css'

    export default {
        name: "notification logs",
        resourceName: {
            type: String,
        },
        data: () => ({

            headers:{
                heading:'Notifications Logs'
            },
            columns: [
                {
                    label: 'Notification ID',
                    field: 'notification_id',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Dealer',
                    field: 'account_id',
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
                    label: 'Content',
                    field: 'content',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Text Color',
                    field: 'text_color',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Background Color',
                    field: 'background_color',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'notiFy Type',
                    field: 'notify_type',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Date',
                    field: 'created_at',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                }
            ],
            rows: [],
            breadcrumbs:[],
            to_date:'',
            from_date:'',
            current_date:''
        }),
        components: {
            VueGoodTable,
            Datetime
        },
        methods: {
            getCurrentDate(dayIncrement = false){
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth()+1; //January is 0!
                var yyyy = today.getFullYear();
                if(dayIncrement){
                    dd++;
                }
                if(dd<10) {
                    dd = '0'+dd
                }

                if(mm<10) {
                    mm = '0'+mm
                }

                today = yyyy + '-' + mm + '-' + dd ;
                this.current_date = today;
                this.to_date = today;
                this.from_date= today;
            },
            async refreshLogs(){
                await Nova.request().get(`/nova-vendor/ProductBuilder/getNotificationLogs?to=`+this.to_date+'&from='+this.from_date).then((response) => {
                    this.rows = response.data.logs;
                });

            }

        },
        async created(){
            this.getCurrentDate();
            this.refreshLogs();
            this.getCurrentDate(true);
            if(!this.resourceName){
                this.resourceName = 'Notification Logs';
            }
            this.breadcrumbs=[
                {url:'',label:this.resourceName},
            ];
        }
    }
</script>

<style scoped>
    .choose-field{
        font-weight: 500;
        font-size:18px;
    }
    .choose-field label{
        margin-right: 15px;
    }
</style>

<template>
    <div>
        <bread-crumbs :breadcrumbs="breadcrumbs"></bread-crumbs>
        <h1 class="mb-3 text-90 font-normal text-2xl">{{headers.heading}}</h1>

        <div class="card relative">
            <div class="overflow-hidden overflow-x-auto relative">
                <div class="w-full" style="padding:10px">
                    <h2 class="mb-3 text-90 font-normal text-2xl">Get Logs by Date</h2>

                    <div class="flex">

                        <span class="w-2/6 flex-1 rounded-l p-3 choose-field">
                            <datetime
                                type="date"
                                v-model="from_date"
                                title="Choose Start Date"
                                input-id="startDate"
                                :max-datetime="current_date"
                                input-style="border:1px solid darkgray; padding:10px;text-align:center"

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
                        :search-options="{
                            enabled: true,
                            placeholder: 'Search this Logs',
                        }"
                        :pagination-options="{
                            enabled: true,
                            perPage: 50
                        }"
                    >
                        <template slot="table-row" slot-scope="props">
                            <span v-if="props.column.field == 'user_id'">
                                {{props.row.user.name}}
                            </span>

                            <span v-else-if="props.column.field == 'data'">
                                <ul class="data-column" v-html="getRenderedDataList(props.formattedRow[props.column.field])">
                                </ul>
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
  import 'vue-good-table/dist/vue-good-table.css';
  import {Datetime} from 'vue-datetime';
  import 'vue-datetime/dist/vue-datetime.css'

  export default {
        name: "role-list",
          resourceName: {
              type: String,
          },
        data: () => ({
            headers:{
                heading:'Log Listing'
            },
            columns: [
                {
                    label: 'Resource ID',
                    field: 'auditable_id',
                    type: 'number',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',

                },
                {
                    label: 'User',
                    field: 'user_id',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',

                },
                {
                    label: 'Event',
                    field: 'event',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',

                },
                {
                    label: 'Model',
                    field: 'auditable_type',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',

                },
                {
                    label: 'Data',
                    field: 'data',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',

                },
                {
                    label: 'IP Address',
                    field: 'ip_address',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',

                },
                {
                    label: 'Timestamp',
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
                await axios.get(`/nova-vendor/ProductBuilder/getLogs?to=`+this.to_date+'&from='+this.from_date).then((response) => {
                    this.rows = response.data.logs;
                });
            },
            getRenderedDataList(data_json){

                let columns_to_ignore = ['created_at', 'updated_at'];
                let data_array = JSON.parse(data_json);
                console.log(data_array);
                let html = '<ul>';
                console.log('>>>>>>>>>>>>>>> data <<<<<<<<<<<<<<<<<<<<');
                for (var key in data_array) {
                    if (data_array.hasOwnProperty(key) && columns_to_ignore.indexOf(key) == -1) {
                        html += '<li><b>'+ key+': </b>' + data_array[key] + '</li>';
                        console.log(data_array[key])
                    }
                }
                html += '</ul>';
                return html;
            }
        },
      created(){
          this.getCurrentDate();
          this.refreshLogs();
          this.getCurrentDate(true);
          if(!this.resourceName){
              this.resourceName = 'Logs';
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
    .data-column{
        text-align: left;
    }
    .choose-field{
        font-weight: 500;
        font-size:18px;
    }
    .choose-field label{
        margin-right: 15px;
    }
</style>
<!--<style href="vue-datetime/dist/vue-datetime.css" ></style>-->

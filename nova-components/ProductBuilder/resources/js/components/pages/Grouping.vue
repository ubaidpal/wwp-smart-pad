<template>
    <div>
        <div>
            <h1 class="mb-3 text-90 font-normal text-2xl">{{headers.heading}}</h1>
            <bread-crumbs :breadcrumbs="breadcrumbs"></bread-crumbs>
            <div class="flex" style="">
                <div class="flex items-center mb-6">
                    <div class="flex-no-shrink ml-auto">
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>
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
                <span v-if="props.column.field == 'tree'">
                    <!-- Tree view for Brands > Collections > Products -->
                    <ul>
                        <tree-item :model='props.row' ></tree-item>
                    </ul>
                </span>
                <span v-else>
                    {{props.formattedRow[props.column.field]}}
                </span>
            </template>
        </vue-good-table>
    </div>

</template>

<script>
    import {VueGoodTable} from 'vue-good-table';
    import 'vue-good-table/dist/vue-good-table.css'

    export default {
        name: "Grouping",
        data: () => ({
            headers:{
                heading:'Group Listing'
            },
            breadcrumbs:[],
            rows: [],
            columns: [
                {
                    label: 'ID',
                    field: 'id',
                    type:'number',
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
                    label: 'Max Employee',
                    field: 'max_employees',
                    type:'number',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
                {
                    label: 'Employees(s) > Companies(s)',
                    field: 'tree',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                    sortable: false,
                },
                {
                    label: 'Status',
                    field: 'status',
                    thClass: 'vgt-center-align',
                    tdClass: 'vgt-center-align',
                },
            ],
        }),
        components: {
            VueGoodTable,
        },
        async created(){

            await axios.get(`/nova-vendor/ProductBuilder/getDealerCompanies`).then((response) => {
                this.rows = response.data.accounts;
            });

            if(!this.resourceName){
                this.resourceName = 'grouping';
            }
            this.breadcrumbs=[
                {url:'',label:this.resourceName},
            ];
        }
    }
</script>

<style scoped>

</style>

<template>
    <div>
        <modal v-if="showModal" @close="closeMainPopup">

            <div class="bg-white rounded-lg shadow-lg overflow-hidden" style="width: 980px;padding:20px">
                <div class="flex border-b border-40">
                    <div>
                        <heading :level="2" class="mb-6 pull-left">{{__('Constraints Editor')}}</heading>
                    </div>
                </div>

                <div v-if="!isGroupOption">

                    <table class="table table-condensed table-bordered table-hovered">
                        <thead>
                        <tr>
                            <th colspan="6">
                                <heading :level="2">{{optionName}}</heading>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th class="font-bold" align="center" width="80px">ID</th>
                            <th class="font-bold" align="center" width="80px">Delete</th>
                            <th class="font-bold" align="center" width="80px">Edit</th>
                            <th class="font-bold" align="center" width="95%">Constraint</th>
                        </tr>
                        <tr v-for="(constraint,key) in this.option.constraints">
                            <td align="center">{{constraint.id}}</td>
                            <td align="center">
                                <button v-on:click="deleteMainObj(key,constraint)" dusk="delete-button"
                                        title="Delete Option"
                                        class="py-1 px-2 text-sm font-bold btn-danger rounded mr-2">
                                    Delete
                                </button>
                            </td>
                            <td align="center">
                                <button v-on:click="editConstraints(constraint)" title="Edit"
                                        class="py-1 px-2 text-sm font-bold btn-primary rounded mr-2">Edit
                                </button>
                            </td>
                            <td align="left">
                                <div v-for="(constraint,key) in constraint.conditions">
                                    1. If sub option <span style="color:green; font-weight: bold;">{{subOptionName(constraint.sub_option)}}</span>
                                    is selected for <span style="color:green; font-weight: bold;">{{optionName}}</span>
                                    then show option group <span style="color:green; font-weight:bold;">{{getOptionName(constraint.option_label)}}</span>.
                                    <br>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="this.option.constraints.length ==0">
                            <td colspan="6" align="center">No Record Found!!</td>
                        </tr>
                        </tbody>
                    </table>

                    <div>&nbsp;</div>

                    <!--<div class="pull-left">-->
                    <!--<a class="btn btn-default btn-primary" href="#" v-on:click="resetEditor()" >Add New</a>-->
                    <!--</div>-->

                    <div>&nbsp;</div>
                    <!---->
                    <table class="table table-condensed table-bordered table-hovered" style="width: 100%">
                        <thead>
                        <tr>
                            <th colspan="3">
                                <heading :level="2">{{headingConstraint}}</heading>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td align="center">
                                <label class="font-bold">IF</label>&nbsp;
                                <select id="event_type" v-model="event_type" class="form-control">
                                    <option value="">Choose</option>
                                    <option v-for="event in eventType" :value="event.id">{{event.name}}</option>
                                </select>
                            </td>
                            <td align="center">
                                <label class="font-bold">THEN</label>&nbsp;
                                <select id="action_type" v-model="action_type" class="form-control">
                                    <option value="">Choose</option>
                                    <option v-for="action in actionType" :value="action.id">{{action.name}}</option>
                                </select>
                            </td>
                            <td align="left">
                                <button v-on:click="addConstraint" type="button" class="btn btn-default btn-primary">Add
                                    New
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <div>&nbsp;</div>

                    <div v-if="editMode">
                        <div class="flex border-b border-40">
                            <div>
                                <heading :level="2" class="mb-6 pull-left">{{__('Parameters')}}</heading>
                            </div>
                        </div>

                        <table class="table table-condensed table-bordered table-hovered" style="width: 100%">
                            <thead>
                            <tr>
                                <th class="tableHeader" align="center">Delete</th>
                                <th class="tableHeader" align="center">Edit</th>
                                <th class="tableHeader" align="center" colspan="3">Event Parameter (if)</th>
                                <th class="tableHeader" align="center" colspan="3">Action Parameter (then)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(constraint,key) in this.conditions">

                                <td align="center">
                                    <button @click="deleteCondition(key,constraint)" data-v-098c3fac=""
                                            dusk="delete-button" title="Delete Option"
                                            class="py-1 px-2 text-sm font-bold btn-danger rounded mr-2">
                                        Delete
                                    </button>
                                </td>
                                <td align="center">
                                    <button @click="editCondition(constraint)" title="Edit"
                                            class="py-1 px-2 text-sm font-bold btn-primary rounded mr-2">Edit
                                    </button>
                                </td>
                                <td align="left">{{event_type}}</td>
                                <td align="left"><span style="color:green; font-weight: bold;">{{subOptionName(constraint.sub_option)}}</span>
                                </td>

                                <td align="center">{{action_type}}</td>
                                <td align="center" colspan="2"><span style="color:green; font-weight: bold;">{{getOptionName(constraint.option_label)}}</span>
                                </td>

                            </tr>
                            <tr v-if="this.conditions.length ==0">
                                <td colspan="6" align="center">No Record Found!!</td>
                            </tr>
                            </tbody>
                        </table>

                        <div>&nbsp;</div>


                        <div class="flex border-b border-40">
                            <div>
                                <heading :level="2" class="mb-6 pull-left">{{__('Edit Param')}}</heading>
                            </div>
                        </div>

                        <table class="table table-condensed table-bordered table-hovered" style="width: 100%">
                            <thead>
                            <tr>
                                <th colspan="2">Event Parameter (IF) Sub Option</th>
                                <th colspan="2">Action Parameter (THEN) Option Group</th>
                                <th>&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td align="center" colspan="2">
                                    <select v-model="sub_option" class="form-control " id="sub_option">
                                        <option value="0">Select</option>
                                        <option v-for="(option,key) in this.option.sub_options" :value="option.id">
                                            {{option.sub_option_name}}
                                        </option>
                                    </select>
                                </td>
                                <td align="center" colspan="2">
                                    <select v-model="option_label" class="form-control" id="option_label">
                                        <option value="0">Select</option>
                                        <option v-for="(option,key) in this.groupOptions" :value="option.id">
                                            {{option.options_name}}
                                        </option>
                                    </select>
                                </td>
                                <td align="left">
                                    <button v-on:click="addCondition" type="button" class="btn btn-default bg-green">
                                        Add
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
                <div v-if="showCalculationPanel">
                    <form @submit="submitFormula">
                        <table class="table table-condensed table-bordered table-hovered" style="width: 100%">
                            <thead>
                            <tr>
                                <th colspan="3">
                                    <heading :level="2">{{__('Calculation Options')}}</heading>
                                </th>
                            </tr>
                            <tr v-if="errors.length">
                                <td colspan="3" class="p-8" style="color:red;font-size:18px;">
                                    <b>Please correct the following error(s):</b>
                                    <ul>
                                        <li v-for="error in errors">{{ error }}</li>
                                    </ul>

                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>

                                <td align="center" colspan="3">
                                    <div class="w-1/5 py-2 px-8">
                                        <label class="inline-block font-bold">
                                            Connected to
                                        </label>
                                    </div>
                                    <div class="py-2 px-8 w-1/2">
                                        <select class="form-control form-select mb-3 w-full"
                                                v-model="calculationFormula.optionId">
                                            <option value="0">Choose Option</option>
                                            <option v-if="fabricOptions" v-for="fabricOption in fabricOptions"
                                                    :value="fabricOption.id">{{fabricOption.options_name}}
                                            </option>

                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="p-4">
                                    <label for="formula_box" class="text-lg font-bold py-2">
                                        Enter Calculation
                                    </label>

                                    <textarea id="formula_box" v-model="calculationFormula.calculation"
                                              class="w-full shadow-inner p-4" rows="6"
                                              placeholder="Enter your Formula"></textarea>
                                </td>
                            </tr>
                            <tr class="selectionBox">
                                <td>
                                    Is Quantity
                                    <toggle-button
                                        color="#8bc34a"
                                        :sync="true"
                                        :labels="true"
                                        v-model="calculationFormula.isQuantity"
                                    />
                                </td>
                                <!--<td>-->
                                <!--Is Dollar Value-->
                                <!--<toggle-button-->
                                <!--color="#8bc34a"-->
                                <!--:sync="true"-->
                                <!--:labels="true"-->
                                <!--v-model="calculationFormula.isDollarValue"-->
                                <!--/>-->
                                <!--</td>-->
                                <td>
                                    Is Fabric Cuts
                                    <toggle-button
                                        color="#8bc34a"
                                        :sync="true"
                                        :labels="true"
                                        v-model="calculationFormula.isFabricCuts"
                                    />
                                </td>
                            </tr>
                            <!--<tr>-->
                            <!--<td colspan="3" align="right">-->
                            <!--<input class="btn btn-default btn-primary" type="submit" name="submit" value="Save Calculation" />-->
                            <!--</td>-->
                            <!--</tr>-->
                            </tbody>
                        </table>
                    </form>
                </div>
                <div v-else>
                    <div class="bg-red-lightest border border-red-light text-red-dark px-4 py-3 rounded relative"
                         role="alert"
                         style="background-color:rgb(255, 177, 177);border:1px solid #BD1010;font-size:18px;opacity: 0.7;color:#BD1010;">
                        <span class="block sm:inline">Save Option First to Enter Calculation</span>
                    </div>
                </div>
                <div style="padding-bottom: 10px; padding-top: 30px;">

                    <div class="float-right">
                        <button v-on:click="saveData" type="button" class="btn btn-default bg-green">Save</button>
                        <a href="javascript:void(0)" class="btn btn-default btn-danger"
                           v-on:click="closeModel">Close</a>
                    </div>
                </div>

            </div>

        </modal>
    </div>
</template>

<script>

    export default {
        name: "Constraints",
        props:['productID','option','productOption','isGroupOption'],
        data: () => ({
            errors:'',
            optionName:'',
            headingConstraint:'Add Constraint',
            showModal:true,
            event_type:'',
            action_type:'',
            sub_option:0,
            option_label:0,
            editMode:false,
            groupOptions:[],
            conditions:[],
            editConst:false,
            editObj:{},
            eventType:[
                {
                    id:1,
                    name:'Sub Option is Selected'
                }
            ],
            actionType:[
                {
                    id:1,
                    name:'Show Group'
                }
            ],
            fabricOptions:[],
            showCalculationPanel:false,
            calculationFormula:{
                optionId:0,
                calculation: '',
                isQuantity: false,
                isDollarValue: false,
                isFabricCuts: false
            }
        }),
        async created() {
            this.showModal = true;
            this.optionName = this.option.options_name;
            if(!this.option.constraints){
                this.option.constraints = [];
            }
            this.getProductOptions();
            this.fillFabricOptions();
            this.getCalculationFormula();
        },
        methods: {

            /**/
            async getProductOptions(){
                await Nova.request().get('/nova-vendor/ProductBuilder/productOptions/' + this.productID).then((response) => {
                    this.productOption = JSON.parse(response.data.options.options);
                });
            },
            /*
            * Hide Constraints Editor
            * */
            closeModel() {
                this.showModal = false;
                this.$emit('close');
            },
            /*
            * Generate Random ID
            * */
            getRandomId(){
                let min=10000;
                let max=99999;
                let random = 'r-'+Math.floor(Math.random() * (+max - +min));
                return random;
            },
            /*
            *
            * */
            addConstraint(){
                var data = {
                    'id':this.getRandomId(),
                    'if':this.event_type,
                    'then':this.action_type,
                    'conditions':[]
                };
                //this.constraints.push(data);
                this.option.constraints.push(data);
                this.event_type = '';
                this.action_type = '';
            },
            /*
            *
            * */
            editConstraints(constraint){

                this.headingConstraint = 'Edit Constraint: '+constraint.id;
                this.event_type = constraint.if;
                this.action_type = constraint.then;
                this.conditions = constraint.conditions;

                this.editMode = true;
                this.editConstraint = true;

                this.groupOptions = [];
                for (let i=0; i< this.productOption.length; i++){
                    if(this.productOption[i].type == 'group'){
                        if(this.productOption[i].linked_sub_id == undefined || this.productOption[i].linked_sub_id == 0 || this.productOption[i].linked_sub_id == this.option.id){
                            this.groupOptions.push(this.productOption[i]);
                        }
                    }
                }
            },
            /*
            *
            * */
            addCondition(){
                if(this.editConst){

                    this.$set(this.editObj, 'sub_option', this.sub_option);
                    this.$set(this.editObj, 'option_label', this.option_label);
                    this.editObj = {};
                } else {
                    var data = {
                        'id':this.getRandomId(),
                        'sub_option':this.sub_option,
                        'option_label':this.option_label,
                    };
                    this.conditions.push(data);
                }
                // Group Linking...
                this.$emit('groupLinking',this.option.id,this.option_label);
                this.option.sub_options.forEach((option)=>{
                    if(option.id == this.sub_option){
                        option.linked_group_id = this.option_label;
                    }
                });
                //
                this.editConst = false;
                this.sub_option = 0;
                this.option_label = 0;
            },
            editCondition(condition){
                this.editConst = true;
                this.editObj =condition;
                this.sub_option = condition.sub_option;
                this.option_label = condition.option_label;
            },
            subOptionName(id){
                for (let i=0; i< this.option.sub_options.length; i++){
                    if(this.option.sub_options[i].id === id){
                        return this.option.sub_options[i].sub_option_name;
                        break;
                    }
                }
            },
            getOptionName(id){
                for (let i=0; i< this.productOption.length; i++){
                    if(this.productOption[i].id == id){
                        return this.productOption[i].options_name;
                        break;
                    }
                }
            },
            resetEditor(){
                this.editMode = false;
                this.headingConstraint = 'Add Constraint';
                this.event_type = '';
                this.action_type = '';
            },
            deleteMainObj(index,constraint){
                var r = confirm("Are you sure to delete?");
                if (r == true) {

                    this.unLinkGroupConstraint(constraint);
                    this.option.constraints.splice(index, 1);
                    /**/
                    let formData = new FormData();
                    formData.append('id', constraint.id);
                    const response = Nova.request().post('/nova-vendor/ProductBuilder/deleteConstraint',
                        formData
                    );

                    if (response) {
                        this.saveData(false);
                    }
                    /**/
                }
            },
            deleteCondition(index,constraint){
                var r = confirm("Are you sure to delete?");
                if (r == true) {
                    this.unLinkConstraint(constraint);
                    this.conditions.splice(index, 1);

                    // if(this.conditions.length == 1){
                    //     this.conditions = [];
                    //
                    // } else {
                    //     for (let i=0; i< this.option.constraints.length; i++){
                    //         for (let j=0; j< this.option.constraints[i].conditions.length; j++){
                    //             if(this.option.constraints[i].conditions[j].id === constraint.id){
                    //                 this.option.constraints[i].conditions.splice(j, 1);
                    //             }
                    //         }
                    //     }
                    // }



                    let formData = new FormData();
                    formData.append('id', constraint.id);
                    const response = Nova.request().post('/nova-vendor/ProductBuilder/deleteCondition',
                        formData
                    );

                    //console.log(response.data);
                    if (response) {
                        this.saveData(false);
                    }
                    /**/
                }
            },
            async saveData(closeModel = true){
                let option_id = ""+this.option.id;
                let n = option_id.includes("r");
                if(n){
                    this.$emit('saveConstraints');
                    this.closeModel();
                } else {
                    try {

                        let formData = new FormData();
                        formData.append('constraint', JSON.stringify(this.option.constraints));
                        formData.append('product_option_id', this.option.id);

                        const response =  await Nova.request().post('/nova-vendor/ProductBuilder/addConstraint',
                            formData
                        );
                        //this.calculationFormula.optionId = this.option.id;
                        //this.submitFormula();

                        if (response) {

                            this.$emit('updateConstraints',this.option.id,response.data.constraint);

                            this.event_type = '';
                            this.action_type = '';
                            this.submitFormula();
                            //this.$emit('saveConstraints');
                            // if(closeModel){
                            //     this.closeModel();
                            // }


                            this.$toasted.show(
                                this.__(':resource loaded!', {
                                    resource: 'Option Constraint',
                                }),
                                {type: 'success'}
                            );
                        }

                    } catch (error) {
                        console.log('>>>saveData>>' + error);
                    }
                }

            },
            unLinkConstraint(constraint){
                for (let i=0; i< this.option.sub_options.length; i++){
                    if(this.option.sub_options[i].id === constraint.sub_option){
                        this.$set(this.option.sub_options[i], 'linked_group_id', 0);
                        this.$emit('unLinkGroup',constraint.option_label);
                        break;
                    }
                }
                return true;
            },
            unLinkGroupConstraint(constraint){

                if(constraint.conditions.length == 1){
                    let sub_option_id = constraint.conditions[0].sub_option;
                    let group_id = constraint.conditions[0].option_label;
                    this.unlinking(sub_option_id,group_id);
                }
                if(constraint.conditions.length > 1){
                    for (let i=0; i< constraint.conditions.length; i++){
                        let sub_option_id = constraint.conditions[i].sub_option;
                        let group_id = constraint.conditions[i].option_label;
                        this.unlinking(sub_option_id,group_id);
                    }
                }
                return true;
            },
            unlinking(sub_option_id,group_id){
                for (let i=0; i< this.option.sub_options.length; i++){
                    if(this.option.sub_options[i].id == sub_option_id){
                        this.$set(this.option.sub_options[i], 'linked_group_id', 0);
                    }
                }
                this.$emit('unLinkGroup',group_id);
                return true;
            },
            /*
             * This will fill the fabric options array
             */
            fillFabricOptions(){
                if(this.productOption){
                    let fabric_types  = [
                        'fabric',
                        'trim',
                        'lining',
                        'accessory'
                    ];
                    for (let i=0; i< this.productOption.length; i++){
                        if(fabric_types.indexOf(this.productOption[i].type )>-1 && this.productOption[i].id != this.option.id){
                            this.fabricOptions.push(this.productOption[i]);
                        }
                        if(this.productOption[i].type == 'group'){
                            for (let j=0; j< this.productOption[i].sub_options.length; j++){
                                if(fabric_types.indexOf(this.productOption[i].sub_options[j].type )>-1 && this.productOption[i].sub_options[j].id != this.option.id){
                                    this.fabricOptions.push(this.productOption[i].sub_options[j]);
                                }
                            }
                        }
                    }

                }
            },
            /*
            * Get the calculation data for an option
            * */
            async getCalculationFormula(){
                await Nova.request().get('/nova-vendor/ProductBuilder/getCalculation/'+this.option.id).then((response)=>{
                    if(response.data.success){
                        this.calculationFormula = response.data.calculation;
                        this.showCalculationPanel = true;
                    }

                });
            },
            /*
            * Validation Checks for Calculation Formula
            * */
            validateCalculationFormula(){
                this.errors = [];
                // if (this.calculationFormula.optionId && this.calculationFormula.calculation) {
                //     return true;
                // }
                //
                //
                // if (!this.calculationFormula.optionId) {
                //     this.errors.push('Option is Required.');
                // }
                //
                // if (!this.calculationFormula.calculation) {
                //     this.errors.push('Calculation Formula is Required.');
                // }
                //
                // return false;

                return true;
            },
            /*
            * This will submit the Calculation Formula for the option
            * */
            async submitFormula(){
                //console.log('------');
                //e.preventDefault();
                //this.validateCalculationFormula();
                if(this.errors.length == 0){
                    let fd = new FormData();
                    fd.append('calculation',this.calculationFormula.calculation);
                    fd.append('is_quantity', this.getBooleanNumber(this.calculationFormula.isQuantity));
                    fd.append('is_dollar_value',this.getBooleanNumber(this.calculationFormula.isDollarValue));
                    fd.append('is_fabric_cuts',this.getBooleanNumber(this.calculationFormula.isFabricCuts));
                    if(this.calculationFormula.optionId == null){
                        this.calculationFormula.optionId = 0;
                    }
                    fd.append('optionId',this.calculationFormula.optionId);
                    await Nova.request().post('/nova-vendor/ProductBuilder/saveCalculation/'+this.option.id,
                        fd
                    ).then((response)=>{
                        if(response.data.success){
                            this.option['calculation'] = this.calculationFormula.calculation;
                            this.option['isQuantity'] = this.calculationFormula.isQuantity;
                            this.option['isDollarValue'] = this.calculationFormula.isDollarValue;
                            this.option['isFabricCuts'] = this.calculationFormula.isFabricCuts;
                            this.option['connectedTo'] = this.calculationFormula.optionId;
                            this.$emit('saveConstraints');

                            this.$toasted.show(
                                this.__('Calculation Saved Successfully!', {
                                    resource: 'Option Constraint',
                                }),
                                {type: 'success'}
                            );
                        }else{
                            this.$toasted.show(
                                this.__('Unable to Save Calculation!', {
                                    resource: 'Option Constraint',
                                }),
                                {type: 'error'}
                            );
                        }
                    });

                }
            },
            getBooleanNumber(value){
                if(value){
                    return 1;
                }else {
                    return 0;
                }
            }
        }
    }
</script>

<style scoped>
    #formula_box{
        border:1px solid lightgray;
        padding:5px;

    }
    .selectionBox td{
        text-align: center;
    }
</style>

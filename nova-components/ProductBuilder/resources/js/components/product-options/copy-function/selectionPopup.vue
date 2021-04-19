<template>
    <modal v-if="showModal" @close="showModal = false">

        <div class="bg-white rounded-lg shadow-lg overflow-hidden"
             style="width: 920px">
                <div class="p-8">

                    <heading :level="2" class="mb-6" >Copy Option(s) </heading>
                </div>

                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <div class="mb-4">
                        <div v-if="activeOption">
                            <label class="block text-grey-darker text-sm font-bold mb-2" for="name">
                                Name
                            </label>
                            <input
                                type="text"
                                v-model="editedName"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="Enter Option Name"
                                required>
                        </div>
                        <div id="group" v-if="selectedCopyFunction === 'group'">
                            <div class="flex border-b border-40">
                                <div class="w-1/5 py-6 px-8">
                                    <label class="inline-block text-80 pt-2 leading-tight">
                                        Choose Group
                                    </label>
                                </div>
                                <div class="py-6 px-8 w-1/2">
                                    <select  data-testid="brands-select" dusk="brands" class="form-control form-select mb-3 w-full" v-model="selectedGroup">
                                        <option value="0">Choose Group</option>
                                        <option v-for="option in options" :value="option.id" v-if="option.type == 'group'">{{option.options_name}}</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div id="product" v-if="selectedCopyFunction === 'product'">
                            <div class="flex border-b border-40">
                                <div class="w-1/5 py-6 px-8">
                                    <label class="inline-block text-80 pt-2 leading-tight">
                                        Choose Brand
                                    </label>
                                </div>
                                <div class="py-6 px-8 w-1/2">
                                    <select  data-testid="brands-select" dusk="brands" class="form-control form-select mb-3 w-full" v-model="selectedBrand" @change="refreshProducts">
                                        <option value="0">Choose Brand First</option>
                                        <option v-if="brands" v-for="brand in brands" :value="brand.id" >{{brand.name}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex border-b border-40">
                                <div class="w-1/5 py-6 px-8">
                                    <label class="inline-block text-80 pt-2 leading-tight">
                                        Choose Product
                                    </label>
                                </div>
                                <div class="py-6 px-8 w-1/2">
                                    <multiselect v-model="selectedProduct"
                                                 placeholder="Select a Product"
                                                 label="name"
                                                 track-by="id"
                                                 :required="true"
                                                 :options="products"
                                                 :multiple="true"
                                                 open-direction="top"
                                    ></multiselect>
                                </div>

                            </div>


                        </div>



                    </div>
                    <div class="text-center" v-if="selectedCopyFunction == ''">
                        <toggle-button v-model="includeConstraints"
                                       color="#8bc34a"
                                       :sync="true"
                                       :labels="true"/>
                        <label class="text-grey-darker text-sm font-bold mb-2" for="name">
                            Copy With Constraints
                        </label>
                    </div>
                </div>
                <div class="bg-30 px-6 py-3 flex">

                    <div class="ml-auto" v-if="selectedCopyFunction == ''">

                        <button class="btn btn-default btn-primary " @click="setSelection('here')"
                        >{{__('Copy Here')}}
                        </button>

                        <button v-if="groupNeeded && !includeConstraints" class="btn btn-default btn-primary " @click="setSelection('group')"
                        >{{__('Copy to Another Group')}}
                        </button>

                        <button class="btn btn-default btn-primary " @click="setSelection('product')"
                        >{{__('Copy to Another Product')}}
                        </button>

                        <button type="button" @click="closeModel" class="btn btn-default btn-danger">{{__('Cancel')}}</button>

                    </div>
                    <div class="ml-auto" v-else="">

                        <button class="btn btn-default btn-primary " @click="copyOption"
                        >{{__('Copy Option(s)')}}
                        </button>

                        <button type="button" @click="resetSelection" class="btn btn-default btn-danger">{{__('Choose Another Option')}}</button>
                    </div>
                </div>
        </div>

    </modal>
</template>

<script>
    import Vue from 'vue'
    import VSwitch from 'v-switch-case'
    import Multiselect from 'vue-multiselect';
    Vue.use(VSwitch);
    export default {
        name: "Copy Option",
        props:[
            'product',
            'options',
            'activeOption',
            'productID'
        ],
        data: () => ({
            showModal:true,
            editedName:'',
            selectedCopyFunction:'',
            brands:[],
            selectedBrand:0,
            products:[],
            selectedProduct:[],
            selectedGroup:0,
            includeConstraints:false,
            newPriceGrids:[],
            groupNeeded:true,
            keyMap:[]
        }),
        methods: {
            /*
            * Option Selection Popup
            * */
            openModal(){
              this.showModal = true;
              if(this.activeOption && this.activeOption.options_name){
                  this.editedName = this.activeOption.options_name;
              }
            },
            /*
            * Close the Selection Popup
            * */
            closeModel() {
                this.showModal = false;
                this.resetSelection(); // Reset all the Variables
                this.$emit('close');
            },
            /*
            * Select a particular Function for copy among here,in product or in group
            * */
            setSelection(functionName){
                this.selectedCopyFunction = functionName;
                if(this.selectedCopyFunction == 'here'){
                    this.selectLinkedGroups(); // Get all the linked groups of selected options
                    this.filterCloneProductsOption(this.selectedCopyFunction ,this.activeOption);
                }else if(this.selectedCopyFunction == 'product'){
                    Nova.request().get(`/nova-vendor/ProductBuilder/getBrandList`).then((response) => {
                        this.brands = response.data.brand;
                    });
                }else if(this.selectedCopyFunction == 'group'){
                    
                }else{
                    console.log('error >>>>>> Unknown copy function: '+this.selectedCopyFunction);
                }
            },
            // It will select the linked groups of all the selected options
            selectLinkedGroups(){
                if(this.includeConstraints){
                    for(let i = 0; i < this.options.length; i++){
                        if(this.options[i].is_selected){
                            let linkedGroups = this.options.filter(elem => elem.linked_sub_id == this.options[i].id );
                            for(let sub_index = 0 ; sub_index < linkedGroups.length; sub_index++){
                                let op = this.options.findIndex(elem => elem.id == linkedGroups[sub_index].id);
                                if(op > -1){
                                    this.options[op].is_selected = 1;
                                }
                            }
                        }
                    }
                }
            },
            //IT will select the linked group of single option
            selectSingleLinkedGroup(){
                let linkedGroupsIndex = [];
                if(this.includeConstraints){
                    let linkedGroups = this.options.filter(elem => elem.linked_sub_id == this.activeOption.id );
                    for(let sub_index = 0 ; sub_index < linkedGroups.length; sub_index++){
                        let op = this.options.findIndex(elem => elem.id == linkedGroups[sub_index].id);
                        if(op > -1){
                            linkedGroupsIndex.push(op);
                        }
                    }
                    return linkedGroupsIndex;

                }
                return linkedGroupsIndex;
            },
            copyOption(){

                this.selectLinkedGroups();
                this.filterCloneProductsOption(this.selectedCopyFunction ,this.activeOption);

            },
            //filter Clone Products Options
            filterCloneProductsOption(type , cloneProducts){
                let newOption = [];
                if(cloneProducts){

                    let linkedGroups = this.selectSingleLinkedGroup();
                    let copiedOption = this.generateMainOption(cloneProducts,true);
                    if(copiedOption.type == 'group'){
                        copiedOption.linked_sub_id = 0;
                    }
                    newOption.push(copiedOption);
                    for(let i = 0 ; i < linkedGroups.length; i++){
                        newOption.push(this.generateMainOption(this.options[linkedGroups[i]],false));
                    }
                }else{
                    newOption = [];
                    this.options.forEach(function (option) {
                        if(option.is_selected){
                            newOption.push(this.generateMainOption(option));
                        }else if(option.type == 'group'){
                            option.sub_options.forEach(function (sub_option) {
                                if(sub_option.is_selected){
                                    newOption.push(this.generateMainOption(sub_option));
                                }
                            }.bind(this));
                        }
                    }.bind(this));
                }
                newOption = this.saperatePriceGrids(newOption);
                newOption = this.replaceOldWithNew(newOption);
                newOption = this.injectPriceGrids(newOption);

               switch(type){
                   case 'here':
                       this.$emit('addOption',newOption);
                       break;
                   case 'group':
                       this.$emit('addOptionToGroup',newOption,this.selectedGroup);
                       break;
                   case 'product':
                       for(let i = 0; i < this.selectedProduct.length ; i++){
                           let fd = new FormData();
                           fd.append('id',this.selectedProduct[i].id);
                           fd.append('optionsJson',JSON.stringify(newOption));
                           Nova.request().post(`/nova-vendor/ProductBuilder/appendProductOption`,fd).then((response) => {
                           });
                       }
                       break;
                   default:
               }
                this.$toasted.show(
                    this.__('Option(s) Copied Successfully', {
                        resource: 'Product Options',
                    }),
                    {type: 'success'}
                );
               this.closeModel();
            },
            /*
            * it will generate a new option
            * @param cloneProducts its the option to be copied
            * */
            generateMainOption(cloneProducts, useActiveName = false){
                let newOption = _.cloneDeep( cloneProducts);
                newOption.id = this.getRandomId(); // Generate Random Id
                this.keyMap.push({
                    'old':cloneProducts.id,
                    'new':newOption.id
                });
                if(this.editedName && useActiveName)
                newOption.options_name = this.editedName;
                newOption.is_selected = false;
                // if(newOption.linked_sub_id && !this.isSelectedExist(cloneProducts.linked_sub_id) ){
                //     newOption.linked_sub_id = 0;
                // }

                if(newOption.linked_options) {
                    let new_linked_count = 0;
                    let new_linked_options = [];
                    newOption.linked_options.forEach(function (value) {
                        if (this.isSelectedExist(value)) {
                            new_linked_options.push(value);
                            new_linked_count++;
                        }
                    }.bind(this));
                    newOption.linked_options = new_linked_options;
                    newOption.linked_sub_count = new_linked_count;
                }


                if (this.includeConstraints) {

                    if(newOption.constraints){

                        let newConstraints = [];
                        newOption.constraints.forEach(function (value ,key) {
                            value.id = this.getRandomId();

                            if(value.conditions && value.conditions.length){
                                for(let index = 0; index < value.conditions.length; index++){
                                    value.conditions[index].id = "" + this.getRandomId();
                                    value.conditions[index].sub_option = "" +value.conditions[index].sub_option;
                                }
                                newConstraints.push(value);
                            }

                        }.bind(this));
                        newOption.constraints = newConstraints;
                    }
                } else {
                    if(  newOption.sub_options){
                        for (let i=0; i< newOption.sub_options.length; i++){
                            if(newOption.sub_options[i].linked_group_id && newOption.sub_options[i].linked_group_id > 0){
                                this.$set(newOption.sub_options[i], 'linked_group_id', 0);
                            }
                        }
                    }
                    newOption.constraints = [];
                }
                newOption.sub_options = this.generateSubOptions(newOption.sub_options, newOption.id, newOption.id);
                return newOption;

            },
            /*
            * It will check that if the option that is linked to one option is selected or not
            * */
            isSelectedExist(option_link_id){
                let linked_option =  this.options.find(x => x.id == option_link_id );
                if(linked_option){
                    return linked_option.is_selected;
                }else{
                    return false;
                }

            },
            /*
            * Saperate price grid from options
            * */
            saperatePriceGrids(newOptions){
                for(let i = 0; i< newOptions.length; i++){
                    if(newOptions[i].sub_options){
                        newOptions[i].sub_options = this.saperateSubPriceGrids(newOptions[i].sub_options);
                    }
                }
                return newOptions;
            },
            saperateSubPriceGrids(subOptions){
                for(let j = 0; j < subOptions.length; j++){
                    this.newPriceGrids.push({
                        option:subOptions[j].id,
                        price_grid:subOptions[j].price_grid
                    });
                    subOptions[j].price_grid = "";
                    if(subOptions[j].sub_options){
                        subOptions[j].sub_options = this.saperatePriceGrids(subOptions[j].sub_options);
                    }
                }
                return subOptions;
            },
            /*
            * Inject Price Grid
            * */

            injectPriceGrids(newOptions){
                for(let i = 0; i< newOptions.length; i++){
                    if(newOptions[i].sub_options){
                        newOptions[i].sub_options = this.injectSubPriceGrids(newOptions[i].sub_options);
                    }
                }
                return newOptions;
            },
            injectSubPriceGrids(subOptions){
                for(let j = 0; j < subOptions.length; j++){
                    let newPriceGrid = this.newPriceGrids.find(elem => elem.option == subOptions[j].id);
                    if(newPriceGrid){
                        subOptions[j].price_grid = newPriceGrid.price_grid;
                    }
                    if(subOptions[j].sub_options){
                        subOptions[j].sub_options = this.injectSubPriceGrids(subOptions[j].sub_options);
                    }
                }
                return subOptions;
            },
            /*
            * It will replace all the options with the new the new generated ids
            * */
            replaceOldWithNew(newOptions){
                let newOptionsString = JSON.stringify(newOptions);
                this.keyMap.forEach(function(value,key){
                    let newReplacedOptionsString = this.replaceAll(newOptionsString,value.old, value.new);
                    newOptionsString = newReplacedOptionsString;
                }.bind(this));
                return JSON.parse(newOptionsString);
            },
            replaceAll(string, search, replacement) {
                var re = new RegExp("'*\"*"+search+"'*\"*" , "g");
                return string.replace(re, '"'+replacement+'"');
            },
            /*
            * It will generate sub options of an option
            * */
            generateSubOptions(options,parent_id,top_parent_id){
               if(options.length > 0){
                   options.forEach(function (option, key) {
                       // if(value.linked_option_id > 0){
                       //    // filterProductOption.push(value);
                       // }
                       //

                       let new_id = this.getRandomId();
                       this.keyMap.push({
                           'old':option.id,
                           'new':new_id
                       });
                       option.id= new_id;
                       option.parent_id = parent_id;
                       option.top_parent_id = top_parent_id;
                       if(option.linked_option_id > 0){
                           this.$emit('increaseLinked', {linked_option_id:option['linked_option_id'], sub_id: option.top_parent_id});
                       }

                       if(option.sub_options.length > 0){
                           option.sub_options = this.generateSubOptions(option.sub_options,option.id, top_parent_id);
                       }
                       // if(option.linked_group_id){
                       //     option.linked_group_id = option.linked_group_id.toString();
                       // }

                   }.bind(this));
               }
               return options;
            },
            /*
            * it will reset the selection boxes of all the options
            * */
            resetSelection(){
                this.editedName = '';
                this.selectedCopyFunction = '';
                this.selectedBrand = 0;
                this.selectedProduct = 0;
                this.selectedGroup = 0;
                Nova.$emit('resetView');

            },
            // It will refresh the products of a specific brand
            refreshProducts(){
                if(this.selectedBrand > 0){
                    Nova.request().get(`/nova-vendor/ProductBuilder/products/`+this.selectedBrand+`/-1`).then((response) => {
                        this.products = response.data.products.data;
                        this.products = this.products.filter(x => x.id != this.productID);
                    });
                }
            },
            // It will generate the random ID for the options
            getRandomId(){
                // let min=10000;
                // let max=99999;
                // let random = '-'+Math.floor(Math.random() * (+max - +min));
                // return random;
                let length = 5;
                let chars = '1zd2ace4';
                var result = '';
                for (var i = length; i > 0; --i) result += chars[Math.floor(Math.random() * chars.length)];
                return result;
            },
            // it will check if the copy to group option is needed or not
            checkIfGroupNeeded(){
                if(this.activeOption){
                    this.groupNeeded = this.activeOption.type != 'group';
                }else{
                    this.options.forEach(function (option, key) {
                        if(option.is_selected && option.type == 'group'){
                            this.groupNeeded = false;
                            return;
                        }
                    }.bind(this))
                }
            }
        },
        async created(){
            this.checkIfGroupNeeded();
            this.openModal();

        },
        beforeDestroy(){
            this.$emit('copyDone');
        },
        components:{
            Multiselect,
        }
    }
</script>

<style scoped>
    .error_color{
        color: #f5573b;
    }


</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

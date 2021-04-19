<template>
    <div v-bind:class="{'sub-options': isGroupOption }" >
        <table :class="[this.isGroupOption ? 'table w-full child-table-bg sub-options option-level-'+level : 'table w-full option-level-'+level]" style="padding: 0px;">
            <thead>
            <tr v-if="productOptions.length > 0 && !isGroupOption ">
                <th class="option-delete-checkbox-header" v-if="!isGroupOption">&nbsp;</th>
                <th class="option-view-button-header">&nbsp;</th>
                <th class="option-name-header">Option Name</th>
                <th class="option-type-header">Type</th>
                <th class="option-hide-function-header">Hide Function</th>
                <th class="option-action-buttons">Action</th>
                <th class="option-delete-checkbox"><input @change="checkboxOptionEvent($event)" type="checkbox" v-model="selectAll"></th>
            </tr>
            <tr v-else-if="productOptions.length > 0 && isGroupOption">
                <th style="background: #f6f6f6;" colspan="6">{{ parentOptionName }} Group Sub Options</th>
            </tr>
            </thead>
        </table>
        <!--<tbody  is="draggable" element="tbody" :options="{group:'document'}">-->
        <table class="table w-full child-table-bg" is="draggable" element="table"
               v-model="productOptions"
               :handle="!isGroupOption?'.main-option':'.group-subs'"
               @start="startDrag"
               @end="endDrag"
        >
            <tbody v-for="(p,key) in productOptions" :key="key">
                <tr v-bind:class="{'group-sub-options': p.type == 'group', 'main-option':!isGroupOption, 'group-subs':isGroupOption}" >
                    <td v-if="!isGroupOption">
                            <span title="Drag" class="py-2 px-2" style="height:10px">
                                <svg cursor="grab" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20"><path d="M10 12a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0-6a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 12a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg>
                            </span>
                    </td>
                    <td class="option-view-button">
                        <!-- Show toggle buttons if the option type has sub options-->

                        <div v-if="hasSubOptions(p)" >


                            <!-- Button to maximize options -->
                            <button title="View" v-if="!isOptionOpen(p.id)" @click="openOption(p.id)" class="py-1 px-2 text-sm font-bold btn-primary rounded mr-2">
                                View
                            </button>
                            <!-- Button to minimize options -->
                            <button title="View" v-if="isOptionOpen(p.id)" @click="closeOption(p.id)" class="py-1 px-2 text-sm font-bold btn-primary rounded mr-2">
                                Close
                            </button>
                        </div>
                    </td>
                    <td class="option-name">
                        <input v-model="p.options_name" name="options_name"
                               class="w-full form-control form-input form-input-bordered"/>
                    </td>
                    <td class="option-type">
                        <select @click="oldOptionType = p.type" @change="addComponent($event,p.sub_options,p,key)" v-model="p.type" id="type" name="type"
                                class="form-control" style="width:150px">
                            <template v-for="type in optionGroups" >
                                <template v-if="type.group == ''">
                                    <option v-for="option in type.options" :value="option.name">{{option.label}}</option>
                                </template>

                                <optgroup v-else :label="type.group">
                                    <option v-for="option in type.options" :value="option.name">{{option.label}}</option>
                                </optgroup>
                            </template>
                        </select>
                    </td>
                    <td class="option-hide-function" v-if="p.type != 'group'">
                        <div class="secret-select-buttons">
                            <button
                                v-if="p.secret.length != options.length"
                                class="py-1 px-2 text-sm font-bold btn-primary rounded mr-2"
                                @click="setSecret(options,key)"
                            >
                                Select All
                            </button>
                            <button
                                class="py-1 px-2 text-sm font-bold btn-primary rounded mr-2"
                                v-if="p.secret.length != 0"
                                @click="setSecret([],key)"
                            >
                                Unselect All
                            </button>
                        </div>
                        <multiselect v-model="p.secret"
                                     placeholder="Search or Select Hide"
                                     label="name"
                                     track-by="id"
                                     :required="true"
                                     :options="options"
                                     :multiple="true"
                                     open-direction="top"
                        ></multiselect>
                    </td>
                    <td v-else>Grouped under: {{ getLinkedOption(p) }}</td>
                    <td class="option-action-buttons">
                        <button title="Copy"
                                class="py-1 px-2 text-sm font-bold btn-primary rounded mr-2"
                                @click="showCopySelectionPopup(p,key)"
                        >
                            Copy
                        </button>
                        <button v-if="p.type != 'group'" @click="showConstraintsPopup(p)" title="Add Constraints" class="py-1 px-2 text-sm font-bold btn-primary rounded mr-2">
                            {{getAddConstraintTitle(p)}}
                        </button>
                        <button v-if="!(p.linked_sub_count>0)" @click="deleteOption(key,p)" dusk="delete-button" title="Delete Option"
                                class="py-1 px-2 text-sm font-bold btn-danger rounded mr-2">
                            Delete
                        </button>
                        <button v-if="(p.linked_sub_count>0)" title="Linked Option" @click="linkedToOptions = getOptionsById(p.linked_options)"
                                class="py-1 px-2 text-sm font-bold btn-danger rounded mr-2" style="background: orange;">
                            Linked
                        </button>
                    </td>
                    <td align="center" class="option-delete-checkbox">
                        <input type="checkbox" @change="checkboxOptionSelect($event,p)" v-bind:value="key" v-model="checkedOptions">
                    </td>
                </tr>
                <!-- If the selected option is not group -->
                <tr v-if="p.type != 'group' && p.type != 'size' && isOptionOpen(p.id)">
                    <td style="height: 10px; padding: 0px;">&nbsp;</td>
                    <td colspan="7" style="height: 10px; padding: 0px;">
                        <product-sub-options v-if="isOptionOpen(p.id)"
                                             :option_name="p.options_name"
                                             :sub_options="p.sub_options"
                                             :folder="hasSubOptions(p)"
                                             :odata="p"
                                             :level="level+1"
                                             :options="productOptions"
                                             :mainOptions="mainOptions"
                                             :optionIndex="key"
                                             @increaseLinked="increaseLinkedCount"
                                             @decreaseLinked="decreaseLinkedCount"
                                             @deleteSub="deleteSubOptions"
                                             ref="resetSubCheckbox"
                                             @save="saveData"
                                             @endDrag="endGroupSubDrag"

                        >
                        </product-sub-options>
                    </td>
                </tr>
                <template v-else-if="p.type == 'group'">
                    <tr v-if="isOptionOpen(p.id) && p.sub_options.length" >
                        <td style="height: 10px; padding: 0px;">&nbsp;</td>
                        <td colspan="6" style="height: 10px; padding: 0px;">
                            <component v-if="isOptionOpen(p.id)" v-bind:is="p.type"
                                       :productOptions="p.sub_options"
                                       :mainOptions="productOptions"
                                       :productID="productID"
                                       :parentOptionName="p.options_name"
                                       :isGroupOption="true"
                                       :folder="hasSubOptions(p)"
                                       :parentOptionType="p.type"
                                       v-on:input="triggerChange"
                                       :level="level+1"
                                       @addOption="copyHere"
                                       @addOptionToGroup="copyToGroup"
                                       @endDrag="endGroupSubDrag"
                            >
                            </component>
                        </td>
                    </tr>
                    <tr v-if="isOptionOpen(p.id)" class="sub-buttions-row">
                        <td>&nbsp;</td>
                        <td colspan="6" class="add-sub-btn sub-options" style="text-align:left">
                            <button title="Add Sub Option" @click="addGroupSubOptions(p, key)" class="py-1 px-2 text-sm btn-primary rounded mr-2">Add Group Option</button>
                        </td>
                    </tr>
                </template>
                <tr v-else-if="p.sub_options && isOptionOpen(p.id)">
                    <td>&nbsp;</td>
                    <td colspan="6" style="height: 10px;padding:0">
                        <component v-bind:is="p.type"
                                   :sub_options="p.sub_options"
                                   :size_name="p.options_name"
                                   @input="triggerChange"
                                   :option="key"
                                   :level="level+1"

                        >
                        </component>
                    </td>
                </tr>
                <tr v-if="!isGroupOption">
                <td class="saperator" colspan="6">

                </td>
            </tr>
            </tbody>
            <!--</tbody>-->
            <constraints-editor
                :option="passOptionModal"
                :productOption="mainOptions"
                :productID="productID"
                :isGroupOption="isGroupOption"
                v-if="showConstraintsModal"
                @close="closeConstraintsPopup"
                @saveConstraints="saveData"
                @unLinkGroup="unLinkGroup"
                @groupLinking="groupLinking"
                @updateConstraints="updateConstraints"
            >
            </constraints-editor>
        </table>
        <modal v-if="linkedToOptions" @close="linkedToOptions = null">

            <div class="bg-white rounded-lg shadow-lg overflow-hidden"
                 style="width: 460px">
                <div class="p-8">
                    <heading :level="2" class="mb-6">Linked Options</heading>
                </div>
                <div class="flex border-b border-40">
                    <table class="table w-full">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr v-for="option in linkedToOptions">
                            <td>{{ option.id }}</td>
                            <td>{{ option.options_name }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <button type="button" @click="linkedToOptions = null" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Close')}}</button>

                    </div>
                </div>
            </div>
        </modal>
        <option-copy-popup
            v-if="copySelectionPopup"
            @close="closeCopySelectionPopup"
            :activeOption="copySelectedOption"
            :options="mainOptions"
            :productID="productID"
            :activeOptionIndex="copySelectedOptionIndex"
            @increaseLinked="increaseLinkedCount"
            @decreaseLinked="decreaseLinkedCount"
            @addOption="copyHere"
            @addOptionToGroup="copyToGroup"

        />
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import draggable from 'vuedraggable';
    export default {
        name: "OptionsTable",
        props: [ 'productID','mainOptions','productOptions','isGroupOption','parentOptionName','level' ],
        data: () => ({
            // store: store,
            linkedToOptions:null,
            passOptionModal:'',
            showConstraintsModal:false,
            checkedOptions: [],
            decisionCheckboxes:[],
            addSubOptioneModal:false,
            folder:false,
            activeOption: '',
            showSub: [],
            optionNumber:0,
            genOptions:[],
            activeObject:{},
            optionTypes:[],
            oldOptionType:'',
            optionGroups:[
                {
                    group:'',options:[
                        { is_sub_allowed:true,name:"option", label:"Option"},
                        { is_sub_allowed:true,name:"finishes", label:"Finishes"},
                        { is_sub_allowed:true,name:"multi",label:"Multi Select"},
                        { is_sub_allowed:true,name:"group",label:"Group"},
                        // { is_sub_allowed:false,name:"size",label:"Size"},
                        { is_sub_allowed:false,name:"text",label:"Text and Price"},
                        { is_sub_allowed:false,name:"text_only",label:"Text Only"},
                        { is_sub_allowed:false,name:"product_notes",label:"Product Notes"},
                        { is_sub_allowed:false,name:"display",label:"Display"},
                        { is_sub_allowed:true,name:"base_price",label:"Base Price Option"},
                    ]
                },
                {
                    group:'Soft Furnishings',options:[
                        { is_sub_allowed:false,name:"price",label:"Price"},
                        { is_sub_allowed:false,name:"cost_price",label:"Cost Price"},
                        { is_sub_allowed:false,name:"manufactoring",label:"Manufactoring"},
                        { is_sub_allowed:false,name:"fabric_quantity",label:"Fabric Quantity"},
                        { is_sub_allowed:false,name:"fabric_quantity_not_edit",label:"Fabric Quantity Not Editable"},
                        { is_sub_allowed:false,name:"fabric",label:"All Fabrics"},
                        { is_sub_allowed:false,name:"trim",label:"Trim Selection"},
                        { is_sub_allowed:false,name:"lining",label:"Lining Selection"},
                        { is_sub_allowed:false,name:"accessory",label:"Accessory Selection"},
                    ]
                },

            ],
            options:[
                { name: 'Hide On Screen', id: 'S' },
                { name: 'Hide On Quote', id: 'Q' },
                { name: 'Hide On Order', id: 'O' },
                { name: 'Hide On Install & Measure', id: 'I' }
            ],
            copySelectionPopup:false,
            copySelectedOption:null,
            copySelectedOptionIndex:-1
        }),
        input(){

        },
        async created() {
            this.fillTypesArray();
            if(this.isGroupOption && this.optionGroups){
                this.optionGroups[0].options = this.optionGroups[0].options.filter(function(value, index, arr){
                    return value.name != 'group';
                });
            }
            this.fillTypesArray();
            if(!this.parentOptionName)
                this.parentOptionName = '';
        },
        methods: {
            /*
            * It will update the constraints
            * @param: option_id
            * @param: response
            * */
            updateConstraints(option_id,response){
                for (let i=0; i< this.productOptions.length; i++){
                    if(this.productOptions[i].id == option_id){
                        //this.option.constraints.push(response.data.constraint[i]);
                        this.$set(this.productOptions[i], 'constraints', response);
                    }
                }
            },
            /*
            * It will Link a Group with a constraint
            * @param: option_id
            * @param: group id
            * */
            groupLinking(option_id,group_id){
                for (let i=0; i< this.productOptions.length; i++){
                    if(this.productOptions[i].type == 'group' && this.productOptions[i].id == group_id){
                        this.$set(this.productOptions[i], 'linked_sub_id', ""+option_id);
                    }
                }
            },
            /*
            * It will unLink a Group from constraints
            * @param: option_id
            * */
            unLinkGroup(option_id){
                for (let i=0; i< this.productOptions.length; i++){
                    if(this.productOptions[i].type == 'group' && this.productOptions[i].id == option_id){
                        this.$set(this.productOptions[i], 'linked_sub_id', "0");
                    }
                }
            },
            /*
            * It will copy the options to the current product
            * @param: new options(array of new options)
            * if the component is not called by group then it will copy here
            * otherwise it will call the same event from parent
            * */
            copyHere(newOption){
                if(!this.isGroupOption){
                    if (this.copySelectedOptionIndex >= 0) {
                        for(let i=0; i< newOption.length; i++){
                            this.productOptions.splice(this.copySelectedOptionIndex + i + 1, 0, newOption[i]);
                        }

                    } else {
                        for(let i=0; i< newOption.length; i++){
                            this.productOptions.push(newOption[i]);
                        }
                    }

                    this.saveData(false);
                }else{
                    this.$emit('addOption',newOption);
                }

            },
            /*
            * It will copy the options to a group
            * @param: new options(array of new options)
            * @param: group id
            * if the component is not called by group then it will copy here
            * otherwise it will call the same event from parent
            * */
            copyToGroup(newOption,groupId){
                if(this.isGroupOption) {
                    this.$emit('addOptionToGroup',newOption,groupId);
                }else{
                    let groupIndex = this.productOptions.findIndex(x => x.id == groupId);
                    if(groupIndex >= 0){
                        for(let i=0; i< newOption.length; i++){
                            this.productOptions[groupIndex].sub_options.push(newOption[i]);
                        }
                        this.saveData(false);
                    }else{
                        console.log('failed to find')
                    }
                }
            },
            /*
            * show copy selection popup
            * */
            showCopySelectionPopup(selectedOption,index){
                this.copySelectionPopup = true;
                this.copySelectedOption = selectedOption;
                this.copySelectedOptionIndex = index;

            },
            /*
            * close copy selection popup
            * */
            closeCopySelectionPopup(){
                this.copySelectionPopup = false;
                this.copySelectedOption = null;
                this.copySelectedOptionIndex = -1;
            },
            /*
            * return all linked options of a group
            * */
            getLinkedOption(p){
                let name = '';
                if(p.linked_sub_id){
                    this.productOptions.forEach((option)=>{
                        if(option.id == p.linked_sub_id){
                            name = option.options_name;
                            return name;
                        }
                    });
                }
                return name;
            },
            /*
            * Return options by id
            * @param: option id
            * */
            getOptionById(id){
                return this.productOptions.find(x => x.id == id);
            },
            /*
            * Return options by multiple ids
            * @param: option ids array
            * */
            getOptionsById(arr){
                let result = this.productOptions.filter(x => arr.indexOf(x.id) >= 0);
                return result;
            },
            startDrag(){
            },
            /*
            * Reorder options after drag end
            * */
            endDrag(){
                if(this.isGroupOption){
                    this.$emit('endDrag',this.productOptions);
                }else{
                    this.$emit('orderChange',this.productOptions);
                }
            },
            /*
            *
            * if group sub options is dragged then it will change the sub options of that group then call end drag method
            * */
            endGroupSubDrag(options){
                if(options.length > 0){
                    let parentIndex = this.productOptions.findIndex(elem => elem.id == options[0].parent_id);

                    if(parentIndex > -1){
                        this.productOptions[parentIndex].sub_options = options;
                        this.$emit('orderChange',this.productOptions);

                    }
                }
            },
            /*
            * Show Constraints popup for an option
            * @param: option
            * */
            showConstraintsPopup(option){

                this.passOptionModal = option;
                this.showConstraintsModal = true;
            },
            /*
            * close Constraints popup
            * */
            closeConstraintsPopup(){
                this.showConstraintsModal = false;
            },
            /*
            * It will check or unselect the selection checkboxes
            * */
            checkboxOptionEvent(event) {
                if(event.target.checked){
                    for (let option of this.productOptions) {
                        this.$set(option, 'is_selected', true);
                        this.decisionCheckboxes.push(option.id);
                    }
                } else {
                    for (let option of this.productOptions) {
                        this.$set(option, 'is_selected', false);
                    }
                    this.decisionCheckboxes = [];
                }
            },
            /*
            * It will check or unselect the selection checkbox for a particular option
            * */
            checkboxOptionSelect(event,option) {
                if(event.target.checked){
                    let index = this.decisionCheckboxes.indexOf(option.id);
                    if(index == -1){
                        this.decisionCheckboxes.push(option.id);
                    }
                    this.$set(option, 'is_selected', true);
                } else {
                    let index = this.decisionCheckboxes.indexOf(option.id);
                    if(index >= 0){
                        this.decisionCheckboxes.splice(index,1);
                        this.$set(option, 'is_selected', false);
                    }

                }
            },
            /*
            * Increase Linked option count for an option
            * @param: option
            * */
            increaseLinkedCount(option){
                let selectedOptionIndex = this.productOptions.findIndex(o => o.id == option.linked_option_id);

                if(selectedOptionIndex >=0){
                    this.productOptions[selectedOptionIndex]['linked_sub_count'] = this.productOptions[selectedOptionIndex]['linked_sub_count']? this.productOptions[selectedOptionIndex]['linked_sub_count']+1:1;
                    if(this.productOptions[selectedOptionIndex].linked_options){
                        this.productOptions[selectedOptionIndex].linked_options.push(option.sub_id);

                    }else{
                        this.productOptions[selectedOptionIndex].linked_options = [option.sub_id];
                    }
                }
            },
            /*
            * decrease Linked option count for an option
            * @param: option
            * */
            decreaseLinkedCount(option){
                let selectedOptionIndex = this.productOptions.findIndex(o => o.id == option.linked_option_id);
                if(selectedOptionIndex >=0){
                    this.productOptions[selectedOptionIndex]['linked_sub_count'] = this.productOptions[selectedOptionIndex]['linked_sub_count']? this.productOptions[selectedOptionIndex]['linked_sub_count']-1:0;
                    if(this.productOptions[selectedOptionIndex].linked_options){
                        this.productOptions[selectedOptionIndex].linked_options.splice(this.productOptions[selectedOptionIndex].linked_options.indexOf(option.sub_id),1);

                    }

                }
            },
            /*
            * Fill option types from groups of types
            * */
            fillTypesArray(){
                let types = [];
                let ge = this.isGroupOption;
                this.optionGroups.forEach(function (group) {
                    group.options.forEach(function (type) {
                        if(type.name == 'group'){
                            if(!ge){
                                types.push(type);
                            }
                        }else{
                            types.push(type);
                        }

                    });
                });
                this.optionTypes=types;
            },
            /*
            * Check whether an option is open or not
            * */
            isOptionOpen(index){
                if(this.showSub.indexOf(index) > -1){
                    return true;
                }else{
                    return false;
                }
            },
            /*
            * open an option
            * */
            openOption(index){
                this.showSub.push(index);
            },
            /*
            * close an option
            * */
            closeOption(index){
                let activeIndex = this.showSub.indexOf(index);
                if(activeIndex > -1){
                    this.showSub.splice(activeIndex,1);
                }
            },
            /*
            * It will generate specific number of sub options
            * */
            genSuboptions() {
                for(let i=0; i<this.optionNumber; i++){
                    this.genOptions.push({
                        'sub_option_name': '',
                        'price_structure': '',
                        'flat_price': '',
                        'percent': '',
                        'mark_default': '',
                        'is_selected': false,
                        'sub_options':[]
                    });
                }
            },
            /*
            * Generate Sub options
            * */
            genSuboptionsAdd() {
                for (let option of this.genOptions) {
                    this.activeObject.sub_options.push(option);
                }
            },
            /*
            * add sub options to a group
            * */
            addGroupSubOptions(p, index) {
                var data = [];
                let top_parent_id = p.top_parent_id?p.top_parent_id:p.id;
                data = {
                    'id': this.getRandomId(),
                    'parent_id':p.id,
                    'top_parent_id':top_parent_id,
                    'option_name': '',
                    'type': '',
                    'secret': '',
                    'price': '',
                    'is_selected': false,
                    'sub_options': []
                };
                p.sub_options.push(data);
                this.showSub.push(index);

            },
            /*
            * Check whether an option have sub options or not
            * */
            hasSubOptions(option){
                let type = option.type;

                if(type){
                    let subOption = this.optionTypes.find(o => o.name === type);
                    return subOption.is_sub_allowed;
                }
            },
            /*
            * Change type of an option and perform operation accordingly
            *
            * */
            addComponent(event, subOptions,object,key) {
                let type = event.target.value;
                /*
                * IF the option type is option, finishes or multi select then the sub options need to be preserved
                * IF the option type is group or size or any other then we dont need to preserve the old values
                * */
                let subOptionsPreserve = ['option', 'multi', 'finishes', 'base_price'];
                if(subOptionsPreserve.indexOf(type) > -1 && subOptionsPreserve.indexOf(this.oldOptionType) > -1){

                }else if(object.sub_options && object.sub_options.length){

                    if(confirm('Are you sure? All sub Options will be deleted!')){
                        object.sub_options = [];
                        if (type == 'size') {
                            object.sub_options.push({'min_value': 0, 'max_value': 0});
                        }
                    }else{
                        object.type = this.oldOptionType;
                    }
                }
                this.oldOptionType = '';
                //this.showSub.push(key);
            },
            /*
            * Delete an option
            * @param: option index
            * @param : option
            * */
            async deleteOption(index,p) {
                if(confirm('Are you sure')){
                    let fd = new FormData();
                    fd.append('id',p.id);
                    await Nova.request().post(`/nova-vendor/ProductBuilder/deleteProductOption`,fd)
                        .then((response) =>{
                            this.productOptions.splice(index, 1);
                            this.destoryLinks(p);
                            this.$emit('delete',this.productOptions,true);
                        });
                }

            },
            /*
            * Destroy links of an option
            * */
            destoryLinks(p){
                for (let option of p.sub_options) {
                    if(option.linked_option_id){
                        this.decreaseLinkedCount(option);
                    }
                }
            },
            expand(key) {
                this.activeOption = key;
            },
            /*
            * Get Random Id
            * */
            getRandomId(){
                let min=10000;
                let max=99999;
                let random = 'r-'+Math.floor(Math.random() * (+max - +min));
                return random;
            },
            addProductSubCard(sub, key,object) {

                this.showSub.push(key);
                let top_parent_id = object.top_parent_id?object.top_parent_id:object.id;

                if(object.type == 'group'){
                    object.sub_options.push({
                        'id': this.getRandomId(),
                        'parent_id':object.id,
                        'top_parent_id':top_parent_id,
                        'option_name': '',
                        'type': '',
                        'secret': '',
                        'price': '',
                        'is_selected': false,
                        'sub_options': []
                    });
                } else {
                    sub.push({
                        'id': this.getRandomId(),
                        'parent_id':object.id,
                        'top_parent_id':top_parent_id,
                        'sub_option_name': '',
                        'price_structure': '',
                        'flat_price': '',
                        'percent': '',
                        'mark_default': '',
                        'sub_options':[]

                    });
                }
            },
            ProductOptionCard: function () {
                var data = [];
                data = {
                    'id': this.getRandomId(),
                    'option_name': '',
                    'type': '',
                    'secret': '',
                    'price': '',
                    'is_selected': false,
                    'sub_options': []
                };
                this.productOptions.push(data);
            },
            triggerChange(key, name, value) {
                this.productOptions[key]['sub_options'][0][name] = value;
            },
            /*
            * Delete Selected and All Options
            * */
            async deleteBulk(){

                if(this.checkedOptions.length == 0){
                    this.$toasted.show(
                        this.__('Please select at least one :resource for this action.', {
                            resource: 'Product Option',
                        }),
                        {type: 'error'}
                    );
                    return false;
                } else {
                    let decision = confirm("Are you sure to Delete?");
                    if (decision == true) {
                        let fd = new FormData();
                        fd.append('id', this.decisionCheckboxes);
                        await Nova.request().post(`/nova-vendor/ProductBuilder/deleteProductOption`,fd)
                            .then((response) => {
                                if(response.data.success){
                                    this.decisionCheckboxes = [];
                                    if (this.checkedOptions.length == this.productOptions.length) {
                                        this.$emit('delete', 'All');
                                    } else {
                                        let counter = 0;
                                        let length = this.productOptions.length;
                                        for (let i = 0; i < length; i++) {
                                            if (this.productOptions[counter].is_selected) {
                                                this.destoryLinks(this.productOptions[counter]);
                                                this.productOptions.splice(counter, 1);
                                                counter--;
                                            } else {
                                                this.checkSubOption(this.productOptions[counter].sub_options);
                                            }
                                            counter++;
                                        }
                                        this.$emit('delete');
                                    }
                                    this.checkedOptions = [];
                                    Nova.$emit('resetCheckboxes');
                                }
                            });
                    }
                }

            },
            /*
            * Check whether an option is linked to any option or not
            * @param Id
            * */
            checkLinking(id){
                for (let main_option of this.productOptions) {
                    if(main_option.linked_options && main_option.linked_options.length > 0){
                        for (let link of main_option.linked_options) {
                            if(link == id){
                                return main_option.is_selected;
                                break;
                            }
                        }
                    }
                }
                return true;
            },
            checkSubOption(option){
                let counter = 0;
                let length = option.length;
                for (let i=0; i< length; i++) {
                    if(option[counter].is_selected){
                        option.splice(counter,1);
                        counter--;
                    } else {
                        this.checkSubOption(option[counter].sub_options);
                    }
                    counter++;
                }
            },
            deleteSubOptions(data){
                if(data != 'One'){
                    this.productOptions[data].sub_options = [];
                }
                this.$emit('delete');
            },

            saveData(resetView=true){
                this.$emit('delete',false,resetView)
            },
            getAddConstraintTitle(option){
                if(option.constraints && option.constraints.length > 0){
                    return option.constraints.length+' Constraints';
                }
                return 'Add Constraints';
            },
            setSecret(secret,optionIndex){
                this.productOptions[optionIndex].secret = secret;
                console.log(this.productOptions[optionIndex]);
            }
        },
        mounted() {
            //
            Nova.$on('resetView', () => {
                this.showSub = [];
                this.checkedOptions = [];
            });
        },
        computed: {
            selectAll: {
                get: function () {
                    return this.productOptions ? this.checkedOptions.length == this.productOptions.length : false;
                },
                set: function (value) {
                    var selected = [];
                    if (value) {
                        this.productOptions.forEach(function (key,value) {
                            selected.push(value);
                        });
                    }
                    this.checkedOptions = selected;
                }
            }
        },
        components: {
            Multiselect,
            draggable,
            // Vuex
        }
    }
</script>

<style scoped>
    .child-table-bg {
        background-color: #f6f6f6;
    }
    .child-table-bg tr td {
        background-color: #f6f6f6;
        text-align: center;
    }
    tr{
        text-align: center;
    }
    .sub-buttions-row{
        text-align:left;
    }
    table{
        overflow:scroll;
    }
    table tr:hover td{
        background-color: transparent;
    }

    .add-sub-btn{
        background-color: #f6f6f6;;
    }
    .sub-options:hover {
        background-color: #f1ebeb;
    }
    .saperator{
        height: 10px;
        background-color:white;
    }
    .option-hide-function{
        overflow: visible;
    }
    .option-action-buttons{
        width:300px;
    }
    tr.group-sub-options td{
        background: #d8d7d7;
    }
    tr.group-sub-options:hover td{
        background: #d8d7d7;
    }
    .option-view-button{
        min-width: 8%;
        max-width: 8%;
    }
    .option-name{
        min-width: 30%;
        max-width: 30%;
    }
    .option-type{
        min-width: 17.5%;
        max-width: 17.5%;
    }
    .option-hide-function{
        min-width: 14.5%;
        max-width: 14.5%;
    }
    .option-action-buttons{
        min-width: 25%;
        max-width: 25%;
    }
    .option-delete-checkbox{
        min-width: 5%;
        max-width: 5%;
    }
    /*Header*/
    .option-view-button-header{
        width: 8.3%;
    }
    .option-name-header{
        width: 24.5%;
    }
    .option-type-header{
        width: 18%;
    }
    .option-hide-function-header{
        width: 21.5%;
    }
    .option-delete-checkbox-header{
        width: 6.2%;
    }
    .secret-select-buttons{
        margin:5px 0px;
    }
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

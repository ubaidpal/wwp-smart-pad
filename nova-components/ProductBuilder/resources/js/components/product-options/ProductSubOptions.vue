<template>
    <div>
        <!--<label>Sub Options Of {{option_name}}</label>-->
        <table v-bind:class="'table option-level-'+level" style="width: 100%;">
            <tr v-if="sub_options.length > 0">
                <th class="sub_option" colspan="10">{{ option_name}} Options</th>
            </tr>
            <tr v-if="sub_options.length > 0">
                <td class="sub-option-space">&nbsp;</td>
                <td class="sub-option-space sub-option-linked-icon">&nbsp;</td>
                <td align="center" class="sub-option-name">Name</td>
                <td align="center" class="sub-option-price-structure">Price Structure<br/>
                    <select v-model="price_structure_all" @change="changeAll('price_structure')" name="priceStructureSelect"
                            class="form-control priceStructureSelect">
                        <option value="">Change All</option>
                        <option v-for="type in measure_system" :value="type.name">{{type.label}}</option>
                    </select>
                </td>
                <td align="center" class="sub-option-quantity-status">
                    Quantity Status<br/>
                    <select v-model="quantity_status_all" @change="changeAll('quantity_status')" name="quantity_status"
                            class="form-control example-getting-started">
                        <option value="">Change All</option>
                        <option value="0">Disabled</option>
                        <option value="1">Enabled</option>
                    </select>
                </td>
                <td align="center" class="sub-option-amount-discount">
                    Apply Discount<br/>
                    <select v-model="apply_discount_all" @change="changeAll('apply_discount')" name="apply_discount"
                            class="form-control example-getting-started">
                        <option value="">Change All</option>
                        <option value="0">No</option>
                        <option value="1">yes</option>
                    </select>
                </td>
                <!--<td align="center">Flat Price</td>-->
                <td align="center" class="sub-option-price-percent"> Price Percent<br/><input @input="changeAll('price_percent_set')"
                                                                                              type="text" placeholder="Price / Percent"
                                                                                              v-model="price_percent_set"
                                                                                              name="price_percent_set"
                                                                                              class=" form-control form-input form-input-bordered" size="5"/>
                </td>
                <td class="sub-option-default">Default</td>
                <td class="sub-option-action">Action</td>
                <td class="sub-option-checkbox"><input type="checkbox" @change="checkboxEvent($event)" v-model="selectAll"></td>
            </tr>
        </table>
        <table v-bind:class="'table option-level-'+level" style="width: 100%;" is="draggable"
               element="table"
               v-model="sub_options"
               handle=".sub-option-row"
               @start="startDrag"
               @end="endDrag">

            <tbody v-for="(s,sindex) in sub_options" class="sub-option-row">
                <tr>
                    <td class="sub-option-view-button">
                        <button title="View" v-if="s.sub_options.length > 0 && !isOptionOpen(s.id)" @click="openOption(s.id)"
                                class="py-1 px-2 text-sm font-bold btn-primary rounded mr-2">
                            View
                        </button>
                        <button title="View" v-if="isOptionOpen(s.id)" @click="closeOption(s.id)"
                                class="py-1 px-2 text-sm font-bold btn-primary rounded mr-2">
                            Close
                        </button>
                    </td>
                    <td class="sub-option-linked-icon sub-option-space">
                        <a v-if="s.linked_group_id" href="#" class="cursor-pointer text-70 hover:text-primary mr- spp-warning"
                           :title="getGroupName(s.linked_group_id)">
                            <svg data-v-73ca7226="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                 class="fill-current text-teal inline-block">
                                <path data-v-73ca7226=""
                                      d="M9.26 13a2 2 0 0 1 .01-2.01A3 3 0 0 0 9 5H5a3 3 0 0 0 0 6h.08a6.06 6.06 0 0 0 0 2H5A5 5 0 0 1 5 3h4a5 5 0 0 1 .26 10zm1.48-6a2 2 0 0 1-.01 2.01A3 3 0 0 0 11 15h4a3 3 0 0 0 0-6h-.08a6.06 6.06 0 0 0 0-2H15a5 5 0 0 1 0 10h-4a5 5 0 0 1-.26-10z"></path>
                            </svg>
                        </a>
                    </td>
                    <td class="sub-option-name">
                        <input type="text" class="w-full form-control form-input form-input-bordered"
                               v-model="s.sub_option_name" name="sub_option_name">
                    </td>
                    <td class="sub-option-price-structure">
                        <select v-model="s.price_structure" @change="changePriceStructure(s)" name="price_structure"
                                class="form-control priceStructureSelect">
                            <option v-for="type in measure_system" :value="type.name">{{type.label}}</option>
                        </select>
                        <button v-if="allowChart(s)" class="py-1 px-2 text-sm font-bold btn-primary rounded mr-2"
                                @click="showChartPopup(s)">Edit
                        </button>
                    </td>
                    <td class="sub-option-quantity-status">
                        <select v-bind:class="getClass(s.quantity_status)" v-model="s.quantity_status" name="quantity_status"
                                class="form-control example-getting-started">
                            <option selected="selected" value="0">Disabled</option>
                            <option value="1">Enabled</option>
                        </select>
                    </td>
                    <td class="sub-option-amount-discount">
                        <select v-bind:class="getClass(s.apply_discount)" v-model="s.apply_discount" name="apply_discount"
                                class="form-control example-getting-started">
                            <option selected="selected" value="0">No</option>
                            <option value="1">yes</option>
                        </select>
                    </td>
                    <td class="sub-option-price-percent">
                        <input size="5" v-model="s.price_percent" type="text" placeholder="Price / Percent"
                               class=" form-control form-input form-input-bordered">
                    </td>
                    <td class="sub-option-default">
                        <toggle-button :value="s.mark_default" @change="onChangeEventHandler(s)"
                                       color="#8bc34a"
                                       :sync="true"
                                       :labels="true"/>
                    </td>
                    <td class="sub-option-action">
                        <button @click="addSubOption(s,sindex)" title="Add Sub" class="py-1 px-2 text-sm btn-primary rounded mr-2">Add Sub
                        </button>
                        <button @click="deleteSubOption(sub_options,sindex)" dusk="delete-button"
                                title="Delete SubOption"
                                class="py-1 px-2 text-sm font-bold btn-danger rounded mr-2">Delete
                        </button>
                    </td>
                    <td align="center" class="sub-option-checkbox">
                        <input type="checkbox" @change="checkboxSelect($event,s)" v-bind:value="sindex" v-model="checkedOptions">
                    </td>
                </tr>
                <tr v-if="isOptionOpen(s.id) && s.sub_options && s.sub_options.length > 0" style="border-bottom: 3px solid #C0C0C0;">
                    <td>&nbsp;</td>
                    <td colspan="9">
                        <product-sub-options
                            :option_name="s.sub_option_name"
                            :sub_options="s.sub_options"
                            :level="level+1"
                            :odata="s"
                            :options="options"
                        >
                        </product-sub-options>
                    </td>
                </tr>
            </tbody>
        </table>
        <table>
            <tr v-if="this.folder" class="sub-buttons-row">
                <td class="sub-option-space">&nbsp</td>
                <td colspan="10" style="height: 0px; padding: 10px;">
                    <button title="Add Option" @click="addSubCard()" class="py-1 px-2 text-sm btn-primary rounded mr-2">Add option</button>
                    <button title="Add Multiple" @click="addSubOptioneModal = true" class="py-1 px-2 text-sm btn-primary rounded mr-2">Add
                        Multiple
                    </button>
                    <button style="position: absolute; right: 2%" title="Delete Multiple" @click="deleteBulkSubOption()"
                            class="py-1 px-2 text-sm btn-danger rounded mr-2">Delete all Selected
                    </button>
                </td>
            </tr>
            <!-- Add Sub Option Modal -->

        </table>

        <modal v-if="addSubOptioneModal" @close="addSubOptioneModal = false">

            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-8">
                    <heading :level="2" class="mb-6">{{__('Add Option Items')}}</heading>
                </div>
                <div class="flex border-b border-40">
                    <div class="w-2/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight">Number of Options</label>
                    </div>
                    <div class="py-6 px-8 w-1/2">
                        <input type="number" v-model="optionNumber" name="number_of_options"
                               class="form-control form-input form-input-bordered">
                    </div>
                    <div class="py-6 px-8 w-1/2">
                        <button @click="generate" class="btn btn-default btn-primary">Generate</button>
                    </div>
                </div>
                <div class="px-4" v-if="showgenrateoptions">
                    <div class="text-center font-bold py-4">
                        <p class="py-4">Option Item Name</p>
                    </div>
                    <div class="text-center font-bold px-4" v-for="(option,index) in generateOptions">
                            <span>
                                <label for="file" class="inline-block text-80 pt-2 leading-tight">{{index+1}}</label>
                                <input type="text" v-model="option.sub_option_name" class="form-control form-input form-input-bordered">
                            </span>
                    </div>
                </div>
                <div class="flex border-b border-40">
                    <div class="w-2/5 py-6 px-8">
                        <label for="file" class="inline-block text-80 pt-2 leading-tight">Attachment</label>
                    </div>
                    <div class="py-6 px-8 w-1/2">
                            <span class="form-file mr-4">
                                <input type="file" id="file" name="file" @change="onFileChanged">
                            </span>
                    </div>
                </div>
                <div>&nbsp;</div>
                <div class="bg-30 px-6 py-3 flex">
                    <div class="float-left">
                        <a href="/sample_files/sample_options_and_prices.xls" class="btn btn-default btn-primary" download="">Download
                            Sample</a>
                    </div>
                    <div class="ml-auto">
                        <button type="button" @click="cancelReset" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}
                        </button>

                        <button class="btn btn-default btn-primary" @click="submitSubOptionsFile">
                            <span v-show="!showLoading">{{__('Add Option Items')}}</span>
                            <span v-show="showLoading">
                                    <loader class="mt-1 text-60"/>
                                </span>
                        </button>
                    </div>
                </div>
            </div>
        </modal>

        <option-price-grid v-if="showOptionPriceGridModal" :option="priceGridOption" @close="closePriceGridPopup"
                           @saveOptionGrid="saveOptionGrid"/>
        <modal v-if="showPercentAddedToOptionModal" @close="showPercentAddedToOptionModal = false">

            <div class="bg-white rounded-lg shadow-lg overflow-hidden"
                 style="width: 460px">
                <div class="p-8">
                    <heading :level="2" class="mb-6">Choose Option</heading>
                </div>
                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight" for="file">Choose Option</label>
                    </div>
                    <div class="py-6 px-8 w-1/2">
                        <span class="form-file mr-4">
                            <select v-model="selectedDropdownOption" class="form-control form-select mb-3 ">
                                <option value="0">Choose Option</option>
                                <template v-for="option in mainOptions">
                                    <option :value="option.id" v-if="option.id != odata.id">{{ option.options_name }}</option>
                                    <template v-if="option.type == 'group'">
                                        <option v-for="opt in option.sub_options" v-if="opt.id != odata.id" :value="opt.id"> -> {{ opt.options_name }}</option>
                                    </template>
                                </template>
                            </select>
                        </span> <!---->
                    </div>
                </div>

                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <button type="button" @click="closeAddPercentToOptionPopup" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">
                            {{__('Cancel')}}
                        </button>

                        <button :disabled="selectedDropdownOption == 0" class="btn btn-default btn-primary"
                                v-on:click="setPercentAddedToOptionModal">{{__('Save')}}
                        </button>
                    </div>
                </div>
            </div>
        </modal>

    </div>
</template>

<script>
    import draggable from 'vuedraggable';

    export default {
        name: "ProductSubOptions",
        props: [ 'options', 'mainOptions', 'option_name', 'sub_options', 'folder', 'odata', 'level', 'price_percent'],
        data: () => ({
            checkedOptions: [],
            decisionCheckboxes: [],
            showSub: [],
            sliced: [],
            addSubOptioneModal: false,
            optionNumber: 0,
            showLoading: false,
            generateOptions: [],
            showgenrateoptions: false,
            price_structure_all: '',
            price_percent_set: '',
            quantity_status_all: '',
            apply_discount_all: '',
            price_type_all: '',
            sub_options_array: [],
            measure_system: [],
            priceGridOption: null,
            selectedDropdownOption: 0,
            showOptionPriceGridModal: false,
            showPercentAddedToOptionModal: false,
            metric: [
                {name: "free", label: "Free", is_grid_type: false, grid_type: ''},
                {name: "flat_price", label: "Flat Price", is_grid_type: false, grid_type: ''},
                {name: "percent_added_to_base", label: "Percent Added to Base", is_grid_type: false, grid_type: ''},
                {name: "per_metre_width", label: "Per Metre Width", is_grid_type: false, grid_type: ''},
                {name: "per_metre_height", label: "Per Metre Height", is_grid_type: false, grid_type: ''},
                {name: "width_based_chart_mt", label: "Width Based Chart", is_grid_type: true, grid_type: 'width_based'},
                {name: "height_based_chart_mt", label: "Height Based Chart", is_grid_type: true, grid_type: 'height_based'},
                {name: "width_and_height_based_chart_mt", label: "Grid Option Chart", is_grid_type: true, grid_type: 'grid_based'},
                {name: "square_meter_rate", label: "Add Price Per SM", is_grid_type: false, grid_type: ''},
                {
                    name: "percent_added_to_option",
                    label: "Percent Added to Option",
                    is_grid_type: true,
                    grid_type: 'percent_added_to_option'
                },
            ],
            imperial: [
                {name: "free", label: "Free", is_grid_type: false, grid_type: ''},
                {name: "flat_price", label: "Flat Price", is_grid_type: false, grid_type: ''},
                {name: "percent_added_to_base", label: "Percent Added to Base", is_grid_type: false, grid_type: ''},
                {name: "per_feet_width", label: "Per Feet Width", is_grid_type: false, grid_type: ''},
                {name: "per_feet_height", label: "Per Feet Height", is_grid_type: false, grid_type: ''},
                {name: "per_inche_width", label: "Per Inche Width", is_grid_type: false, grid_type: ''},
                {name: "per_inche_height", label: "Per Inche Height", is_grid_type: false, grid_type: ''},
                {name: "width_based_chart_ft", label: "Width Based Chart (SQ FT)", is_grid_type: true, grid_type: 'width_based'},
                {name: "height_based_chart_ft", label: "Height Based Chart (SQ FT)", is_grid_type: true, grid_type: 'height_based'},
                {name: "width_and_height_based_chart_ft", label: "Grid Option Chart (SQ FT)", is_grid_type: true, grid_type: 'grid_based'},
                {name: "width_based_chart_in", label: "Width Based Chart (SQ IN)", is_grid_type: true, grid_type: 'width_based'},
                {name: "height_based_chart_in", label: "Height Based Chart (SQ IN)", is_grid_type: true, grid_type: 'height_based'},
                {name: "width_and_height_based_chart_in", label: "Grid Option Chart (SQ IN)", is_grid_type: true, grid_type: 'grid_based'},
                {name: "square_feet_rate", label: "Add Price Per SF", is_grid_type: false, grid_type: ''},
                {name: "square_inche_rate", label: "Add Price Per SI", is_grid_type: false, grid_type: ''},
                {
                    name: "percent_added_to_option",
                    label: "Percent Added to Option",
                    is_grid_type: true,
                    grid_type: 'percent_added_to_option'
                },
            ]
        }),
        async created() {
            const response = await Nova.request().get(`/nova-vendor/ProductBuilder/getCountryMeasureUnit`);
            if (response.data.countries[0].measure_system == 'METRIC') {
                this.measure_system = this.metric;
            } else {
                this.measure_system = this.imperial;
            }
        },
        methods: {
            changePriceStructure(option) {
                this.$set(option, 'price_grid', []);
                ///console.log(option);
            },
            checkboxEvent(event) {
                if (event.target.checked) {
                    for (let suboption of this.sub_options) {
                        this.$set(suboption, 'is_selected', true);
                        this.decisionCheckboxes.push(suboption.id);
                    }
                } else {
                    for (let suboption of this.sub_options) {
                        this.$set(suboption, 'is_selected', false);
                    }
                    this.decisionCheckboxes = [];

                }
            },
            checkboxSelect(event, suboption) {
                if (event.target.checked) {
                    let index = this.decisionCheckboxes.indexOf(suboption.id);
                    if (index == -1) {
                        this.decisionCheckboxes.push(suboption.id);
                    }
                    this.$set(suboption, 'is_selected', true);
                } else {
                    let index = this.decisionCheckboxes.indexOf(suboption.id);
                    if (index >= 0) {
                        this.decisionCheckboxes.splice(index, 1);
                        this.$set(suboption, 'is_selected', false);
                    }
                }
            },
            setPercentAddedToOptionModal() {
                this.$emit('decreaseLinked', {
                    linked_option_id: this.priceGridOption['linked_option_id'],
                    sub_id: this.priceGridOption.top_parent_id
                });
                this.priceGridOption['linked_option_id'] = this.selectedDropdownOption;
                this.$emit('increaseLinked', {
                    linked_option_id: this.priceGridOption['linked_option_id'],
                    sub_id: this.priceGridOption.top_parent_id
                });
                this.closeAddPercentToOptionPopup();
            },
            showChartPopup(option) {
                this.priceGridOption = option;
                if (option.price_structure == 'percent_added_to_option') {
                    this.showPercentAddedToOptionModal = true;
                    this.selectedDropdownOption = this.priceGridOption.linked_option_id;
                } else {
                    this.showOptionPriceGridModal = true;
                }
            },
            closeAddPercentToOptionPopup() {
                this.priceGridOption = null;
                this.showPercentAddedToOptionModal = false;
                this.selectedDropdownOption = 0;
            },
            closePriceGridPopup() {
                this.priceGridOption = null;
                this.showOptionPriceGridModal = false;
            },
            allowChart(option) {
                let price_structure = option.price_structure;

                if (price_structure) {
                    let allow_grid = this.measure_system.find(o => o.name === price_structure);
                    if (allow_grid) {
                        return allow_grid.is_grid_type;
                    }
                }
                return false;
            },
            getClassPriceType(value) {
                if (value == 0) {
                    return 'option_green';
                } else if (value == 1) {
                    return 'price_type_red';
                } else {
                    return '';
                }
            },
            onChangeEventHandler(option) {
                var defaultSet = (option.mark_default == 0) ? 1 : 0;
                for (let suboption of this.sub_options) {
                    suboption.mark_default = 0;
                }
                this.$set(option, 'mark_default', defaultSet);
            },
            changeAll(changed) {

                for (let suboption of this.sub_options) {

                    if (changed == 'price_structure') {
                        this.$set(suboption, 'price_structure', this.price_structure_all);
                        if (suboption.price_grid && suboption.price_grid.length > 0) {
                            this.$set(suboption, 'price_grid', '');
                            let fd = new FormData();
                            fd.append('option_id', this.suboption.id);
                            Nova.request().post('/nova-vendor/ProductBuilder/deletePriceGrid', fd).then((response) => {
                            });
                        }

                    } else if (changed == 'quantity_status') {
                        this.$set(suboption, 'quantity_status', this.quantity_status_all);
                    } else if (changed == 'price_type') {
                        this.$set(suboption, 'price_type', this.price_type_all);
                    } else if (changed == 'price_percent_set') {
                        this.$set(suboption, 'price_percent', this.price_percent_set);
                    } else if (changed == 'apply_discount') {
                        this.$set(suboption, 'apply_discount', this.apply_discount_all);
                    }
                }
            },
            getClass(value) {
                if (value == 0) {
                    return 'option_gray';
                } else {
                    return 'option_green';
                }
            },
            cancelReset() {
                this.optionNumber = 0;
                this.addSubOptioneModal = false;
                this.generateOptions = [];
                this.showgenrateoptions = false;
            },
            generate() {

                for (let i = 0; i < this.optionNumber; i++) {
                    this.generateOptions.push({
                        'id': this.getRandomId(),
                        'parent_id': this.odata.id,
                        'sub_option_name': '',
                        'price_structure': 'free',
                        'quantity_status': 0,
                        'apply_discount': 0,
                        'price_percent': '',
                        'price_type': 0,
                        'mark_default': 0,
                        'is_selected': false,
                        'sub_options': []
                    });
                }
                this.showgenrateoptions = true;
            },
            isOptionOpen(index) {
                if (this.showSub.indexOf(index) > -1) {
                    return true;
                } else {
                    return false;
                }
            },
            openOption(index) {
                this.showSub.push(index);
            },
            closeOption(index) {
                let activeIndex = this.showSub.indexOf(index);
                if (index > -1) {
                    this.showSub.splice(activeIndex, 1);
                }
            },
            genSuboptionsAdd() {
                this.addSubOptioneModal = false;
            },
            async deleteSubOption(sub, index) {
                if (sub[index].price_structure == 'percent_added_to_option') {
                    this.$emit('decreaseLinked', {
                        'linked_option_id': sub[index]['linked_option_id'],
                        'sub_id': sub[index]['top_parent_id']
                    });
                }
                let fd = new FormData();
                fd.append('id', sub[index].id);
                await Nova.request().post(`/nova-vendor/ProductBuilder/deleteProductSubOption`, fd)
                    .then((response) => {

                    });
                sub.splice(index, 1);
            },
            async deleteBulkSubOption() {
                var counter = 0;
                let decision = confirm("Are you sure to Delete?");
                if (decision == true) {
                    let fd = new FormData();
                    fd.append('id', this.decisionCheckboxes);
                    await Nova.request().post(`/nova-vendor/ProductBuilder/deleteProductSubOption`, fd)
                        .then((response) => {
                            if (response.data.success) {
                                this.decisionCheckboxes = [];

                                if (this.sub_options.length == this.checkedOptions.length) {
                                    this.sub_options.splice(0, this.sub_options.length);
                                } else {
                                    let length = this.sub_options.length;
                                    for (let i = 0; i < length; i++) {
                                        if (this.sub_options[counter].is_selected) {
                                            this.sub_options.splice(counter, 1);
                                        } else {
                                            counter++;
                                        }
                                    }
                                }
                                this.checkedOptions = [];
                            }

                        });
                }
            },
            addSubOption(option, index) {
                this.showSub.push(index);
                if (!option.sub_options) {
                    option.sub_options = [];
                }
                let top_parent_id = option.top_parent_id ? option.top_parent_id : option.id;
                option.sub_options.push({
                    'id': this.getRandomId(),
                    'parent_id': option.id,
                    'top_parent_id': top_parent_id,
                    'sub_option_name': '',
                    'price_structure': 'free',
                    'quantity_status': 0,
                    'apply_discount': 0,
                    'price_percent': '',
                    'price_type': 0,
                    'mark_default': 0,
                    'is_selected': false,
                    'sub_options': []
                });
            },
            addSubCard() {
                let top_parent_id = this.odata.top_parent_id ? this.odata.top_parent_id : this.odata.id;

                this.odata.sub_options.push({
                    'id': this.getRandomId(),
                    'parent_id': this.odata.id,
                    'top_parent_id': top_parent_id,
                    'sub_option_name': '',
                    'price_structure': 'free',
                    'quantity_status': 0,
                    'apply_discount': 0,
                    'price_percent': '',
                    'price_type': 0,
                    'mark_default': 0,
                    'is_selected': false,
                    'sub_options': []

                });
            },
            onFileChanged(event) {
                this.selectedFile = event.target.files[0]
            },
            async submitSubOptionsFile() {

                try {
                    this.showLoading = true;
                    if (this.selectedFile) {

                        let formData = new FormData();
                        formData.append('file', this.selectedFile);

                        const response = await Nova.request().post('/nova-vendor/ProductBuilder/importSubOptions',
                            formData, {headers: {'Content-Type': 'multipart/form-data'}}
                        );

                        if (response.data.data.rows.length > 0) {

                            for (let suboption of response.data.data.rows) {
                                this.odata.sub_options.push({
                                    'id': this.getRandomId(),
                                    'parent_id': this.odata.id,
                                    'sub_option_name': suboption.option_name,
                                    'price_structure': 'free',
                                    'price_type': 0,
                                    'quantity_status': 0,
                                    'apply_discount': 0,
                                    'price_percent': suboption.price_percent,
                                    'mark_default': 0,
                                    'is_selected': false,
                                    'sub_options': []

                                });
                            }
                        }

                    } else {

                        for (let suboption of this.generateOptions) {
                            if (suboption.sub_option_name) {
                                this.odata.sub_options.push(suboption);
                            }
                        }
                        this.generateOptions = [];
                        this.showgenrateoptions = false;

                    }

                    this.$toasted.show(
                        this.__(':resource loaded!', {
                            resource: 'Options',
                        }),
                        {type: 'success'}
                    );
                    this.selectedFile = '';
                    this.optionNumber = 0;
                    this.showLoading = false;
                    this.addSubOptioneModal = false;


                } catch (error) {
                    this.$toasted.show(
                        this.__('No :resource were found', {
                            resource: 'Options',
                        }),
                        {type: 'success'}
                    );

                }

            },
            saveOptionGrid(grid) {
                this.priceGridOption['price_grid'] = grid;
                let resetView = false;
                this.$emit('save', resetView);

            },
            getRandomId() {
                let min = 10000;
                let max = 99999;
                let random = 'r-' + Math.floor(Math.random() * (+max - +min));
                return random;
            },
            getGroupName(id) {
                let name = '';
                this.options.forEach((option) => {
                    if (option.type == 'group' && option.id == id) {
                        name = option.options_name;
                        return name;
                    }
                });
                return 'Constraint Linked: ' + name;
            },
            startDrag() {
            },
            /*
            * Reorder options after drag end
            * */
            endDrag() {
                this.$emit('endDrag', this.sub_options);
            },
            subOptionOrderChange(subOptions) {
                if (subOptions.length > 0) {
                    let index = this.sub_options.findIndex(elem => elem.id == subOptions[0].parent_id);
                    this.sub_options[index] = subOptions;
                    this.emit('endDrag', this.sub_options);
                }
            }

        },
        mounted() {
            //
            Nova.$on('resetCheckboxes', () => {
                this.checkedOptions = [];
            });
            Nova.$on('resetView', () => {
                this.showSub = [];
                this.checkedOptions = [];
            });
            //UnLink Percent Added To Option
            for (let index in this.sub_options) {
                this.$watch(['sub_options', index, 'price_structure'].join('.'), (newVal, oldVal) => {
                    if (oldVal == 'percent_added_to_option') {
                        this.$emit('decreaseLinked', {
                            linked_option_id: this.sub_options[index]['linked_option_id'],
                            sub_id: this.sub_options[index].top_parent_id
                        });
                    }
                });
            }

        },
        computed: {
            selectAll: {
                get: function () {
                    return this.sub_options ? this.checkedOptions.length == this.sub_options.length : false;
                },
                set: function (value) {
                    var selected = [];
                    if (value) {
                        this.sub_options.forEach(function (key, value) {
                            selected.push(value);
                        });
                    }
                    this.checkedOptions = selected;
                }
            }
        },
        components: {
            draggable,
            // Vuex
        }
    }
</script>

<style scoped>
    .table tr td {
        background-color: #f6f6f6;
    }

    .table tr:hover td {
        background-color: #f1ebeb;
    }

    .sub_option {
        background-color: #f6f6f6;
    }

    .sub-buttons-row {
        text-align: left;
    }

    .option_gray {
        background-color: rgb(186, 183, 187);
        color: white;
    }

    .option_green {
        background-color: rgb(0, 128, 0);
        color: white;
    }

    .price_type_red {
        background-color: red;
        color: white;
    }

    .sub-option-name {
        min-width: 28%;
        max-width: 28%;
        width: 28%;
    }

    .sub-option-price-structure {
        min-width: 10%;
        width: 10%;
        max-width: 10%;
    }
    .sub-option-quantity-status{
        min-width: 10%;
        max-width: 10%;
        width: 10%;
    }
    .sub-option-amount-discount{
        min-width: 15%;
        max-width: 15%;
        width: 15%;
    }
    .sub-option-price-percent{
        min-width: 15%;
        max-width: 15%;
        width: 15%;
    }
    .sub-option-price-percent input {
    }
    .sub-option-default{
        min-width: 5%;
        max-width: 5%;
        width: 5%;
    }
    .sub-option-action{
        min-width: 15%;
        max-width: 15%;
        width: 15%;
    }
    .sub-option-checkbox{

    }
    .sub-option-space{
        min-width:45px;
        max-width:45px;
        width:45px;
    }
</style>

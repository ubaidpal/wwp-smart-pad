<template>
    <div class="vld-parent">
        <loading :active.sync="isLoading"
                 :can-cancel="false"
                 :on-cancel="onCancel"
                 :is-full-page="fullPage"></loading>
        <input type="hidden" v-model="this.productID" name="product_id">
        <input type="hidden" v-model="this.choose_price_grid" name="price_grid_type">
        <div class="price_grid_type" style="">
            <table class="table w-full" v-if="sizesWH && (cols.length > 0 || rows.length > 0)">
                <thead>
                <tr>
                    <th>Height /  Width</th>
                    <th v-for="(column,index) in cols" :key="index">
                        <label v-if="column > 0">{{ column }}
                            <button data-testid="brands-items-0-delete-button" @click="deleteCol(column)" title="Delete" class="appearance-none cursor-pointer text-70 hover:text-primary mr-3"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg></button>
                        </label>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item,index) in rows" :key="index">
                    <th>
                        <label>
                            {{ item[0] }}
                            <button data-testid="brands-items-0-delete-button" @click="deleteRow(index)" title="Delete" class="appearance-none cursor-pointer text-70 hover:text-primary mr-3"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg></button>
                        </label>
                    </th>
                    <td v-for="(column,indexColumn) in cols" :key="indexColumn">
                        <input size="5" v-if="column > 0" v-model="item[column]" class="form-control form-input form-input-bordered" type="text">
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <table class="table w-full" v-if="widthObject">
            <thead>
            <tr>
                <td v-model="changeLabel">{{changeLabel}}</td>
                <td>Price</td>
                <td>Action</td>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item,indexWith) in width" :key="indexWith">
                <td>
                    <input v-if="changeLabel == 'Width'"  v-model="item.width" name="width[]" class="w-full form-control form-input form-input-bordered" type="text">
                    <input v-if="changeLabel == 'Height'"  v-model="item.height" name="height[]" class="w-full form-control form-input form-input-bordered" type="text">
                </td>
                <td><input v-model="item.price" name="price[]" class="w-full form-control form-input form-input-bordered" type="text"></td>
                <td><button data-testid="brands-items-0-delete-button" @click="deleteWidth(item,indexWith)" title="Delete" class="appearance-none cursor-pointer text-70 hover:text-primary mr-3"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg></button></td>
            </tr>
            </tbody>
        </table>

        <table class="table" v-if="squaremeter">
            <tr>
                <td></td>
                <td>Price ({{ this.textShow }})</td>
                <td><input v-model="square_meter_rate" name="square_meter_rate" class="w-full form-control form-input form-input-bordered" type="number"></td>
            </tr>
        </table>

        <div v-if="this.productID == 0" class="bg-orange-lightest border-l-4 border-orange text-orange-dark p-4" role="alert">
            <p class="font-bold">Be Warned</p>
            <p v-if="this.productID == 0">Please Add Product Information First.</p>
        </div>

        <div v-if="this.productID > 0 && this.createPriceGrid" class="bg-orange-lightest border-l-4 border-orange text-orange-dark p-4" role="alert">
            <p class="font-bold">Be Warned</p>
            <p v-if="this.productID > 0 && this.createPriceGrid">There is no Price Grid added for this product.</p>
        </div>

        <div class="float-right" style="padding-bottom: 10px; padding-top: 30px;">
            <a href="javascript:void(0)" v-if="createPriceGrid && this.productID > 0" class="btn btn-default btn-primary" v-on:click="showModal = true" >{{__('Create Price Grid')}}</a>
            <a href="javascript:void(0)" v-if="sizesWH" class="btn btn-default btn-primary" v-on:click="showAddRowsPopup = true">{{__('Add Row')}}</a>
            <a href="javascript:void(0)" v-if="sizesWH" class="btn btn-default btn-primary" v-on:click="showAddColsPopup = true">{{__('Add Column')}}</a>
            <a href="javascript:void(0)" v-if="widthObject" class="btn btn-default btn-primary" v-on:click="addObjectWidth()">Add {{changeLabel}}</a>
            <a href="javascript:void(0)" v-if="showUploadGrid" class="btn btn-default btn-primary" v-on:click="uploadGrid = true">Upload Grid</a>
            <a href="javascript:void(0)" v-if="resetGrid" class="btn btn-default btn-danger" v-on:click="resetPriceGrid">Remove Grid</a>
            <button @click="changeTab = false;" dusk="create-button" class="btn btn-default btn-primary bg-green" v-on:click="submitPriceGrid" :disabled="isLoading == true">
                {{__('Save')}}
            </button>
            <button @click="changeTab = true;" dusk="create-button" class="btn btn-default btn-primary" v-if="saveGrid && this.productID > 0" v-on:click="submitPriceGrid">
                {{__('Product Options >>')}}
            </button>
        </div>

        <modal v-if="showModal" @close="showModal = false">

            <div class="bg-white rounded-lg shadow-lg overflow-hidden"
                 style="width: 460px">
                <div class="p-8">
                    <heading :level="2" class="mb-6">{{__('Create Price Grid')}}</heading>
                </div>
                <div class="flex border-b border-40">
                    <div class="py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight" for="choose_price_grid">Select Grid Type</label>
                    </div>
                    <div class="py-6 px-8 w-1/2">
                        <select v-model="choose_price_grid" id="choose_price_grid" name="choose_price_grid" class="form-control form-select mb-3 w-full">
                            <option selected="selected">Select One</option>
                            <optgroup v-for="group in measure_system" :label=group.group>
                                <option v-for="type in group.options" :value="type.name">{{type.label}}</option>
                            </optgroup>
                        </select>

                    </div>
                </div>

                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <button type="button" @click="showModal = false" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}
                        </button>

                        <button v-show="!importLoading" class="btn btn-default btn-primary"
                                v-on:click="addTableOfPriceGrid()">{{__('Done')}}
                        </button>
                    </div>
                </div>
            </div>
        </modal>

        <modal v-if="uploadGrid" @close="uploadGrid = false">

            <div class="bg-white rounded-lg shadow-lg overflow-hidden"
                 style="width: 460px">
                <div class="p-8">
                    <heading :level="2" class="mb-6">Upload Price Grid </heading>
                </div>
                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight" for="file">Attachment</label>
                    </div>
                    <div class="py-6 px-8 w-1/2">
                        <span class="form-file mr-4">
                             <input type="file" id="file" name="file" @change="onFileChanged">
                        </span> <!---->
                    </div>
                </div>

                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <button type="button" @click="uploadGrid = false" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}</button>

                        <button v-show="!importLoading" class="btn btn-default btn-primary" v-on:click="submitPriceGridFile()">{{__('Done')}}</button>
                        <span v-show="importLoading"><loader class="mt-1 text-60"/></span>
                    </div>
                </div>
            </div>
        </modal>


        <!-- Add Rows/Cols Popup-->
        <modal v-if="showAddColsPopup" @close="showAddColsPopup = false">

            <div class="bg-white rounded-lg shadow-lg overflow-hidden"
                 style="width: 460px">
                <div class="p-8">
                    <heading :level="2" class="mb-6">Add Column</heading>
                </div>
                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight" for="file">Enter Height</label>
                    </div>
                    <div class="py-6 px-8 w-1/2">
                        <span class="form-file mr-4">
                             <input type="number" v-model="colsToAdd">
                        </span> <!---->
                    </div>
                </div>

                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <button type="button" @click="showAddColsPopup = false" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}</button>

                        <button v-show="!importLoading" class="btn btn-default btn-primary" v-on:click="addTableCols()">{{__('Create')}}</button>
                        <span v-show="importLoading"><loader class="mt-1 text-60"/></span>
                    </div>
                </div>
            </div>
        </modal>
        <modal v-if="showAddRowsPopup" @close="showAddRowsPopup = false">

            <div class="bg-white rounded-lg shadow-lg overflow-hidden"
                 style="width: 460px">
                <div class="p-8">
                    <heading :level="2" class="mb-6">Add Row</heading>
                </div>
                <div class="flex border-b border-40">
                    <div class="w-1/5 py-6 px-8">
                        <label class="inline-block text-80 pt-2 leading-tight" for="file">Enter Width</label>
                    </div>
                    <div class="py-6 px-8 w-1/2">
                        <span class="form-file mr-4">
                             <input type="number" v-model="rowsToAdd">
                        </span> <!---->
                    </div>
                </div>

                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <button type="button" @click="showAddRowsPopup = false" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}</button>

                        <button v-show="!importLoading" class="btn btn-default btn-primary" v-on:click="addTableRows()">{{__('Create')}}</button>
                        <span v-show="importLoading"><loader class="mt-1 text-60"/></span>
                    </div>
                </div>
            </div>
        </modal>

    </div>
</template>

<script>
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';
    export default {
        name: "PriceGrid",
        props:['productID'],
        data: () => ({
            changeTab:false,
            createPriceGrid:true,
            showUploadGrid:false,
            cols:[],
            rows:[],
            showModal:false,
            uploadGrid:false,
            sizesWH:false,
            addCols:false,
            addRows:false,
            width:[],
            widthObject:false,
            sizesButtonWH:false,
            squaremeter:false,
            changeLabel:'',
            resetGrid:false,
            saveGrid:false,
            square_meter_rate:'',
            showAddRowsPopup:false,
            showAddColsPopup:false,
            colsToAdd:0,
            rowsToAdd:0,
            textShow:'',
            measure_system:[],
            imperial:[
                {
                    group:'Square Meter',options:[
                        { name:"width_based_chart_mt", label:"Width Based Chart"},
                        { name:"height_based_chart_mt",label:"Height Based Chart"},
                        { name:"width_and_height_based_chart_mt",label:"Width & Height Based Chart"},
                        { name:"square_meter_rate",label:"Square Meter Rate"}
                    ]
                }
            ],
            metric:[
                {
                    group:'Square Feet',options:[
                        { name:"width_based_chart_ft", label:"Width Based Chart (SQ FT)"},
                        { name:"height_based_chart_ft",label:"Height Based Chart (SQ FT)"},
                        { name:"width_and_height_based_chart_ft",label:"Width & Height Based Chart (SQ FT)"},
                        { name:"square_feet_rate",label:"Square Feet Rate"}
                    ]
                },
                {
                    group:'Square Inche',options:[
                        { name:"width_based_chart_in", label:"Width Based Chart (SQ IN)"},
                        { name:"height_based_chart_in",label:"Height Based Chart (SQ IN)"},
                        { name:"width_and_height_based_chart_in",label:"Width & Height Based Chart (SQ IN)"},
                        { name:"square_inche_rate",label:"Square Inche Rate"}
                    ]
                }
            ],
            isLoading:false,
        }),
        async created() {

            const response = await Nova.request().get(`/nova-vendor/ProductBuilder/getCountryMeasureUnit`);
            if(response.data.countries[0].measure_system == 'METRIC'){
                this.measure_system = this.imperial;
            } else {
                this.measure_system = this.metric;
            }

            if(this.productID){
                this.getProductPriceGrid();
            }
        },
        methods: {
            async deleteCol(col){
                if(confirm('Are you sure')){

                    let widthToDel = col;
                    let heightToDel = [];
                    this.cols.splice( this.cols.indexOf(col), 1 );
                    for(let i=0; i< this.rows.length; i++){
                        heightToDel.push(this.rows[i][0]);
                        delete this.rows[i][col];
                    }
                    let fd = new FormData();
                    fd.append('product_id',this.productID);
                    fd.append('heights',heightToDel);
                    fd.append('widths',widthToDel);
                    await Nova.request().post('/nova-vendor/ProductBuilder/deletePriceGrid',
                        fd
                    ).then((response) =>{
                        if(response.data.success){
                            this.isLoading = false;
                            this.submitPriceGrid();
                        }
                    });
                }

            },
            async deleteRow(index){
                if(confirm('Are you sure ')){
                    this.isLoading = true;
                    let heightToDel = this.rows[index][0];
                    let widthToDel = [];
                    this.rows.splice( index , 1 );

                    let fd = new FormData();
                    fd.append('product_id',this.productID);
                    fd.append('heights',heightToDel);
                    fd.append('widths',widthToDel);
                    await Nova.request().post('/nova-vendor/ProductBuilder/deletePriceGrid',
                        fd
                    ).then((response) =>{
                        if(response.data.success){
                            this.submitPriceGrid();
                            this.isLoading = false;
                        }
                    });

                }

            },
            addTableCols: function(){
                this.cols.push(this.colsToAdd);
                for(let i=0; i< this.rows.length; i++){
                    //console.log(this.rows[i]);
                    this.rows[i][this.colsToAdd]= '';
                }
                this.showAddColsPopup = false;
            },
            addTableRows: function(){
                let newRow =  [];
                newRow[0] = this.rowsToAdd;
                //console.log(this.rowsToAdd);
                for(let i=1; i< this.cols.length; i++){
                    newRow[this.cols[i]] = '';
                }
                this.rows.push(newRow);
                this.showAddRowsPopup = false;
            },
            addTableOfPriceGrid: function () {
                this.resetGrid = true;
                this.addTable(this.choose_price_grid);

            },
            addTable: function(choose_price_grid){

                if(choose_price_grid == 'width_and_height_based_chart_in' || choose_price_grid == 'width_and_height_based_chart_ft' || choose_price_grid == 'width_and_height_based_chart_mt'){
                    this.sizesWH = true;
                }
                if(choose_price_grid == 'width_based_chart_in' || choose_price_grid == 'width_based_chart_ft' || choose_price_grid == 'width_based_chart_mt'){
                    this.changeLabel = 'Width';
                    this.widthObject = true;
                }
                if(choose_price_grid == 'height_based_chart_in' || choose_price_grid == 'height_based_chart_ft' || choose_price_grid == 'height_based_chart_mt'){
                    this.changeLabel = 'Height';
                    this.widthObject = true;
                }
                this.showUploadGrid = true;
                if(choose_price_grid == 'square_meter_rate' || choose_price_grid == 'square_feet_rate' || choose_price_grid == 'square_inche_rate' ){
                    var words = choose_price_grid.split('_');
                    this.textShow = 'Sq '+words[1];
                    this.squaremeter = true;
                    this.showUploadGrid = false;
                }
                this.showModal = false;
                this.createPriceGrid = false;
                this.saveGrid = true;

            },
            addObjectWidth: function(){
                if(this.changeLabel == 'Height'){
                    this.width.push({'height':'', 'price':''});
                } else {
                    this.width.push({'width':'', 'price':''});
                }
            },
            async deleteWidth(item,index) {
                if(confirm('Are you sure')){
                    this.isLoading = true;
                    let heightToDel = [];
                    let widthToDel = [];
                    if(item.hasOwnProperty('height')){
                        widthToDel = item.width;
                        heightToDel = [];

                    }else if(item.hasOwnProperty('height')){
                        heightToDel  = item.height;
                        widthToDel = [];
                    }

                    var idx = this.width.indexOf(item);
                    this.width.splice(idx, 1);

                    let fd = new FormData();
                    fd.append('product_id',this.productID);
                    fd.append('heights',heightToDel);
                    fd.append('widths',widthToDel);
                    await Nova.request().post('/nova-vendor/ProductBuilder/deletePriceGrid',
                        fd
                    ).then((response) =>{
                        if(response.data.success){
                            this.isLoading = false;
                            this.submitPriceGrid();
                        }
                    });
                }

            },
            onFileChanged (event) {
                this.selectedFile = event.target.files[0]
            },
            uploadGridButton: function(){
                this.showModal = false;
                this.showUploadGrid = true;
                this.uploadGrid = true;
            },
            async submitPriceGridFile() {

                try {
                    this.isLoading = true;

                    let formData = new FormData();
                    formData.append('file', this.selectedFile);
                    formData.append('price_grid_type', this.choose_price_grid);

                    const response = await Nova.request().post('/nova-vendor/ProductBuilder/excelReader',
                        formData, {headers: {'Content-Type': 'multipart/form-data'}}
                    );

                    if (response.data.data.rows.length > 0) {

                        if(this.choose_price_grid == 'width_and_height_based_chart_in' || this.choose_price_grid == 'width_and_height_based_chart_ft' || this.choose_price_grid == 'width_and_height_based_chart_mt'){
                            this.cols = response.data.data.header;
                            this.rows = response.data.data.rows;
                            this.sizesWH = true;

                        } else {

                            for (let price of response.data.data.rows) {
                                if(this.changeLabel == 'Height'){
                                    this.width.push({'height':price.height, 'price':price.price});
                                } else {
                                    this.width.push({'width':price.width, 'price':price.price});
                                }
                            }
                        }


                        this.uploadGrid = false;
                        this.showUploadGrid = false;
                        this.createPriceGrid = false;
                        this.resetGrid = true;
                        this.saveGrid = true;
                        this.$toasted.show(
                            this.__(':resource loaded!', {
                                resource: 'Price Gird',
                            }),
                            {type: 'success'}
                        )
                        this.isLoading = false;

                    } else {
                        this.$toasted.show(
                            this.__('No :resource were found', {
                                resource: 'Price Gird',
                            }),
                            {type: 'success'}
                        )
                    }

                } catch (error) {
                    console.log('>>>555>>' + error);
                }

            },
            async submitPriceGrid() {

                try {

                    this.isLoading = true;

                    let formData = new FormData();
                    formData.append('product_id',this.productID);
                    if(this.rows.length >= 1 || this.width.length >= 1 || this.square_meter_rate > 0){
                        formData.append('price_grid_type',this.choose_price_grid);
                        if(this.choose_price_grid == 'square_meter_rate' || this.choose_price_grid == 'square_feet_rate' || this.choose_price_grid == 'square_inche_rate'){
                            formData.append('square_meter_rate',this.square_meter_rate);
                        } else {
                            const gridData = [];
                            if(this.choose_price_grid == 'width_and_height_based_chart_in' || this.choose_price_grid == 'width_and_height_based_chart_ft' || this.choose_price_grid == 'width_and_height_based_chart_mt'){
                                for (let height of this.rows){
                                    for (let price of this.cols){
                                        if(height[0] != height[price] && price != 0){
                                            gridData.push({'height':height[0],'width':price,'price':height[price]});
                                        }
                                    }
                                }
                            } else {
                                for (let price of this.width){
                                    if(this.changeLabel == 'Height'){
                                        gridData.push({'height':price.height, 'price':price.price});
                                    } else {
                                        gridData.push({'width':price.width, 'price':price.price});
                                    }

                                }
                            }
                            formData.append('grid_data',JSON.stringify(gridData));
                        }
                    }

                    const response = await Nova.request().post('/nova-vendor/ProductBuilder/updateProduct',
                        formData
                    );

                    if (response) {
                        // alert('here');
                        this.isLoading = false;

                        this.$toasted.show(
                            this.__(':resource loaded!', {
                                resource: 'Product Price',
                            }),
                            {type: 'success'}
                        );
                        if(this.changeTab){
                            this.setTabs();
                        }

                    } else {
                        this.$toasted.show(
                            this.__('No :resource were found', {
                                resource: 'Product Price',
                            }),
                            {type: 'success'}
                        )
                    }

                } catch (error) {
                    console.log('>>>2222>>' + error);
                }

            },
            async resetPriceGrid() {

                if(confirm('Are you Sure, you want to Reset?')){
                    this.isLoading = true;

                    this.cols = [];
                    this.rows = [];
                    this.width = [];
                    this.showUploadGrid = false;
                    this.sizesWH = false;
                    this.resetGrid = false;
                    this.widthObject = false;
                    this.saveGrid = false;
                    this.createPriceGrid = true;
                    this.squaremeter = false;
                    this.choose_price_grid = '';
                    let removeGrid = new FormData();
                    removeGrid.append('product_id', this.productID);
                    await Nova.request().post('/nova-vendor/ProductBuilder/resetPriceGrid', removeGrid).then((response) => {
                        this.isLoading = false;

                    });
                    this.$toasted.show(
                        this.__(':resource Removed!', {
                            resource: 'Product Price',
                        }),
                        {type: 'success'}
                    );
                }
            },
            setTabs: function () {
                Nova.$emit('changeTabPanel', {
                    value: 3
                });
            },
            async getProductPriceGrid() {

                const response = await Nova.request().get('/nova-vendor/ProductBuilder/priceGrid/'+this.productID);
                if(response.data.price_grid_type){
                    this.addShowDataTable(response.data);
                }

            },
            addShowDataTable: function(product){

                this.choose_price_grid = product.price_grid_type;

                if(product.price_grid_type == 'width_and_height_based_chart_in' || product.price_grid_type == 'width_and_height_based_chart_ft' || product.price_grid_type == 'width_and_height_based_chart_mt'){
                    this.sizesWH = true;
                    //console.log(product.price_grid);
                    this.cols = product.price_grid.columns;
                    this.rows = product.price_grid.rows;
                }
                if(product.price_grid_type == 'width_based_chart_in' || product.price_grid_type == 'width_based_chart_ft' || product.price_grid_type == 'width_based_chart_mt'){
                    this.changeLabel = 'Width';
                    this.widthObject = true;
                    this.width = JSON.parse(product.price_grid);
                }
                if(product.price_grid_type == 'height_based_chart_in' || product.price_grid_type == 'height_based_chart_ft' || product.price_grid_type == 'height_based_chart_mt'){
                    this.changeLabel = 'Height';
                    this.widthObject = true;
                    this.width = JSON.parse(product.price_grid);
                }
                if(product.price_grid_type == 'square_meter_rate' || product.price_grid_type == 'square_feet_rate' || product.price_grid_type == 'square_inche_rate'){
                    var words = product.price_grid_type.split('_');
                    this.textShow = 'Sq '+words[1];
                    this.squaremeter = true;
                    this.square_meter_rate = product.square_meter_rate;
                }
                this.showUploadGrid = false;
                this.showModal = false;
                this.createPriceGrid = false;
                this.saveGrid = true;
                this.resetGrid = true;
                this.isLoading = false;

            },

        },
        components: {
            Loading
        },
    }
</script>

<style scoped>
    .price_grid_type{
        overflow-x:scroll;
        width: 100%;
    }
</style>

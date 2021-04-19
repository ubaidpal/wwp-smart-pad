<template>
    <div>

        <modal v-if="showModal" @close="closeMainPopup">

                <div class="bg-white rounded-lg shadow-lg overflow-hidden"
                     style="width: 1400px;padding:20px">
                    <div class="flex border-b border-40">
                        <div>
                            <heading :level="2" class="mb-6 pull-left">{{__('Price Chart')}}</heading>
                        </div>
                    </div>
                    <!--<div class="flex border-40">-->
                        <!--<div class="w-2/5 py-6 px-8">-->
                            <!--<label class="inline-block text-80 pt-2 leading-tight">Number of Options</label>-->
                        <!--</div>-->
                        <!--<div class="py-6 px-8 w-1/2">-->
                            <!--<input type="number" v-model="optionNumber" name="number_of_options" class="form-control form-input form-input-bordered">-->
                        <!--</div>-->
                        <!--<div class="py-6 px-8 w-1/2">-->
                            <!--<button @click="generate" class="btn btn-default btn-primary">Generate</button>-->
                        <!--</div>-->
                    <!--</div>-->
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
                    <div class="flex border-40" v-if="this.width.length == 0">
                        <div class="w-2/5 py-6 px-8">
                            <label for="file" class="inline-block text-80 pt-2 leading-tight">Attachment</label>
                        </div>
                        <div class="py-6 px-8 w-1/2">
                            <span class="form-file mr-4">
                                <input type="file" name="file" @change="onFileChanged">
                            </span>
                        </div>
                        <div class="py-6 px-8 w-1/2">&nbsp;</div>
                    </div>


                    <div v-bind:class="{price_grid_type:this.option.price_structure == 'grid_option_chart'}" class="overflow-scroll" style="">
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

                    <div v-if="this.productID == 0" class="bg-orange-lightest border-l-4 border-orange text-orange-dark p-4" role="alert">
                        <p class="font-bold">Be Warned</p>
                        <p v-if="this.productID == 0">Please Add Product Information First.</p>
                    </div>

                    <div v-if="this.productID > 0 && this.createPriceGrid" class="bg-orange-lightest border-l-4 border-orange text-orange-dark p-4" role="alert">
                        <p class="font-bold">Be Warned</p>
                        <p v-if="this.productID > 0 && this.createPriceGrid">There is no Price Grid added for this product.</p>
                    </div>

                    <div style="padding-bottom: 10px; padding-top: 30px;">
                        <div class="float-left">
                            <a href="/sample_files/sample_import_for_height_price_grid.xls" v-if="option_price_structure == 'height_based_chart'" class="btn btn-default btn-primary" download="">Download Sample</a>
                            <a href="/sample_files/sample_width_based_chart.xls" v-if="option_price_structure == 'width_based_chart'" class="btn btn-default btn-primary" download="">Download Sample</a>
                            <a href="/sample_files/sample_import_for_width_and_height_price_grid-2.xls" v-if="option_price_structure == 'grid_option_chart'" class="btn btn-default btn-primary" download="">Download Sample</a>
                        </div>
                        <div class="float-right">
                            <a href="javascript:void(0)" v-if="widthObject" class="btn btn-default btn-primary" v-on:click="addObjectWidth()">Add {{changeLabel}}</a>
                            <a href="javascript:void(0)" @click="submitPriceGridFile" v-if="this.width.length == 0" class="btn btn-default btn-primary">Upload Chart</a>

                            <a href="javascript:void(0)" v-if="width.length >0 || rows.length>0" class="btn btn-default btn-danger" v-on:click="resetPriceGrid">Delete Chart</a>

                            <button dusk="create-button" class="btn btn-default btn-primary bg-green" @click="submitPriceGrid">
                                {{__('Save')}}
                            </button>
                            <a href="javascript:void(0)" class="btn btn-default btn-danger" v-on:click="closeMainPopup">Cancel</a>
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
    export default {
        name: "OptionPriceGrid",
        props:['option'],
        data: () => ({
            hideFileDiv:true,
            createPriceGrid:true,
            showUploadGrid:false,
            cols:[],
            rows:[],
            showModal:true,
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
            option_price_structure:''

        }),
        async created() {
            this.showModal = true;
            this.addShowDataTable(this.option);


        },
        methods: {
            /*
            * To delete a column from the grid
            * */
            closeMainPopup(){
                this.showModal = false;
                this.option = null;
                this.$emit('close');
            },
            async deleteCol(col){
                if(confirm('Are you sure?')){
                    this.cols.indexOf(col);
                    let widthToDel = col;
                    let heightToDel = [];
                    this.cols.splice( this.cols.indexOf(col), 1 );
                    for(let i=0; i< this.rows.length; i++){
                        heightToDel.push(this.rows[i][col]);
                        delete this.rows[i][col];
                    }
                    let fd = new FormData();
                    fd.append('option_id',this.option.id);
                    fd.append('heights',heightToDel);
                    fd.append('widths',widthToDel);
                    await Nova.request().post('/nova-vendor/ProductBuilder/deletePriceGrid',
                        fd
                    ).then((response) =>{
                        if(response.data.success){
                            this.saveGrid();
                        }
                    });
                }

            },
            /*
            * To delete a row from the grid
            * */
            async deleteRow(index){
                if(confirm('Are you sure?')){
                    this.cols.indexOf(index);
                    let heightToDel = this.rows[index];
                    this.rows.splice( index , 1 );
                    let fd = new FormData();
                    fd.append('option_id',this.option.id);
                    fd.append('heights',heightToDel);
                    fd.append('widths',widthToDel);
                    await Nova.request().post('/nova-vendor/ProductBuilder/deletePriceGrid',
                        fd
                    ).then((response) =>{
                        if(response.data.success){
                            this.saveGrid();
                        }
                    });
                }

            },
            /*
            * To add a column from the grid
            * */
            addTableCols(){
                this.cols.push(this.colsToAdd);
                for(let i=0; i< this.rows.length; i++){
                    this.rows[i][this.colsToAdd]= '';
                }
                this.showAddColsPopup = false;
            },
            /*
            * To add a row from the grid
            * */
            addTableRows(){
                let newRow =  [];
                newRow[0] = this.rowsToAdd;
                for(let i=1; i< this.cols.length; i++){
                    newRow[this.cols[i]] = '';
                }
                this.rows.push(newRow);
                this.showAddRowsPopup = false;
            },
            /*
            * Add totally new price grid
            * */
            addTableOfPriceGrid () {
                this.resetGrid = true;
                this.addTable(this.option.price_structure);
            },
            /*
            * Add totally new price grid
            * */
            addTable(){


                if(this.option.price_structure == 'width_and_height_based_chart_in' || this.option.price_structure == 'width_and_height_based_chart_ft' || this.option.price_structure == 'width_and_height_based_chart_mt'){
                    this.sizesWH = true;
                    this.option_price_structure = 'grid_option_chart';
                }
                if(this.option.price_structure == 'width_based_chart_in' || this.option.price_structure == 'width_based_chart_ft' || this.option.price_structure == 'width_based_chart_mt'){
                    this.changeLabel = 'Width';
                    this.widthObject = true;
                    this.option_price_structure = 'width_based_chart';
                }
                if(this.option.price_structure == 'height_based_chart_in' || this.option.price_structure == 'height_based_chart_ft' || this.option.price_structure == 'height_based_chart_mt'){
                    this.changeLabel = 'Height';
                    this.widthObject = true;
                    this.option_price_structure = 'height_based_chart';
                }

                this.showUploadGrid = true;
                this.showModal = false;
                this.createPriceGrid = false;
                this.saveGrid = true;

            },
            /*
            * Add totally new price grid
            * */
            addObjectWidth(){
                if(this.changeLabel == 'Height'){
                    this.width.push({'height':'', 'price':''});
                } else {
                    this.width.push({'width':'', 'price':''});
                }
            },
            async deleteWidth(item,index) {
                if(confirm('Are you sure')){
                    let heightToDel = [];
                    let widthToDel = [];
                    if(item.hasOwnProperty('width')){
                        widthToDel = item.width;
                        heightToDel = [];

                    }else if(item.hasOwnProperty('height')){
                        heightToDel  = item.height;
                        widthToDel = [];
                    }

                    var idx = this.width.indexOf(item);
                    this.width.splice(idx, 1);

                    let fd = new FormData();
                    fd.append('option_id',this.option.id);
                    fd.append('heights',heightToDel);
                    fd.append('widths',widthToDel);
                    await Nova.request().post('/nova-vendor/ProductBuilder/deletePriceGrid',
                        fd
                    ).then((response) =>{
                        if(response.data.success){
                        }
                    });
                }

            },
            onFileChanged (event) {
                this.selectedFile = event.target.files[0];
            },
            uploadGridButton(){
                this.showModal = false;
                this.showUploadGrid = true;
                this.uploadGrid = true;
            },
            async submitPriceGridFile() {

                try {

                    let formData = new FormData();
                    formData.append('file', this.selectedFile);
                    formData.append('price_grid_type', this.option.price_structure);

                    const response = await Nova.request().post('/nova-vendor/ProductBuilder/excelReader',
                        formData, {headers: {'Content-Type': 'multipart/form-data'}}
                    );

                    if (response.data.data.rows.length > 0) {

                        if(this.option.price_structure == 'width_and_height_based_chart_in' || this.option.price_structure == 'width_and_height_based_chart_ft' || this.option.price_structure == 'width_and_height_based_chart_mt'){

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
                        this.hideFileDiv = false;
                        this.showUploadGrid = false;
                        this.createPriceGrid = false;
                        this.resetGrid = true;
                        this.saveGrid = true;
                        this.$toasted.show(
                            this.__(':resource loaded!', {
                                resource: 'Price Chart',
                            }),
                            {type: 'success'}
                        )
                    } else {
                        this.$toasted.show(
                            this.__('No :resource were found', {
                                resource: 'Price Chart',
                            }),
                            {type: 'success'}
                        )
                    }

                } catch (error) {
                }

            },
            async submitPriceGrid() {

                try {

                    const gridData = [];
                    if(this.option.price_structure == 'width_and_height_based_chart_in' || this.option.price_structure == 'width_and_height_based_chart_ft' || this.option.price_structure == 'width_and_height_based_chart_mt'){
                        for (let height of this.rows){
                            for (let price of this.cols){
                                gridData.push({'height':height[0],'width':price,'price':height[price]});
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

                    this.$emit('saveOptionGrid',JSON.stringify(gridData));
                    this.closeMainPopup();

                    // if (response) {
                    //
                    //     this.$toasted.show(
                    //         this.__(':resource loaded!', {
                    //             resource: 'Product Price',
                    //         }),
                    //         {type: 'success'}
                    //     );
                    //     if(this.changeTab){
                    //         this.setTabs();
                    //     }
                    //
                    // } else {
                    //     this.$toasted.show(
                    //         this.__('No :resource were found', {
                    //             resource: 'Product Price',
                    //         }),
                    //         {type: 'success'}
                    //     )
                    // }

                } catch (error) {

                }
            },
            async resetPriceGrid(){
                if(confirm('Are you sure, you want to Reset?')){

                    this.cols = [];
                    this.rows = [];
                    this.width = [];
                    this.showUploadGrid = false;
                    this.sizesWH = false;
                    this.resetGrid = false;
                    //this.widthObject = false;
                    this.saveGrid = false;
                    this.createPriceGrid = true;
                    this.squaremeter = false;
                    this.choose_price_grid = '';
                    this.hideFileDiv = true;
                    this.$set(this.option, 'price_grid', '');
                    let fd = new FormData();
                    fd.append('option_id',this.option.id);

                    await Nova.request().post('/nova-vendor/ProductBuilder/deletePriceGrid',
                        fd
                    ).then((response) =>{
                        if(response.data.success){
                            this.$toasted.show(
                                this.__(':resource Removed!', {
                                    resource: 'Price Chart',
                                }),
                                {type: 'success'}
                            );
                        }
                    });

                }

            },
            async addShowDataTable(grid){

                if(grid.price_structure == 'width_and_height_based_chart_in' || grid.price_structure == 'width_and_height_based_chart_ft' || grid.price_structure == 'width_and_height_based_chart_mt'){
                    this.sizesWH = true;
                    this.option_price_structure = 'grid_option_chart';
                    if(this.option.price_grid.length >0){
                        let formData = new FormData();
                        formData.append('grid',this.option.price_grid);
                        const response = await Nova.request().post('/nova-vendor/ProductBuilder/parsePriceGrid',
                            formData
                        );
                        if (response.data.rows.length > 0) {
                            this.cols = response.data.columns;
                            this.rows = response.data.rows;
                            this.hideFileDiv = false;
                        }
                    }

                }
                if(grid.price_structure == 'width_based_chart_in' || grid.price_structure == 'width_based_chart_ft' || grid.price_structure == 'width_based_chart_mt'){
                    this.changeLabel = 'Width';
                    this.widthObject = true;
                    this.hideFileDiv = false;
                    this.option_price_structure = 'width_based_chart';
                }
                if(grid.price_structure == 'height_based_chart_in' || grid.price_structure == 'height_based_chart_ft' || grid.price_structure == 'height_based_chart_mt'){
                    this.changeLabel = 'Height';
                    this.widthObject = true;
                    this.hideFileDiv = false;
                    this.option_price_structure = 'height_based_chart';
                }

                if(grid.price_grid.length > 0){
                    this.width = JSON.parse(grid.price_grid);
                } else {
                    this.width = [];
                }

                this.showUploadGrid = false;
                this.createPriceGrid = false;
                this.saveGrid = true;
                this.resetGrid = true;

            },

        }
    }
</script>

<style scoped>
    .price_grid_type{
        overflow-x:scroll;
        width: 100%;
    }
</style>

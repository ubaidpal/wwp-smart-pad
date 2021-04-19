<template>
    <div class="vld-parent">
        <loading :active.sync="isLoading"
                 :can-cancel="false"
                 :on-cancel="onCancel"
                 :is-full-page="fullPage"></loading>
        <div class="float-left" style="padding: 10px;">
            <button v-on:click="ProductOptionCard" class="btn btn-default btn-primary" v-if="this.productID > 0">Add New Main Option</button>
        </div><br/><br/><br/>

        <div v-if="this.productID == 0" class="bg-orange-lightest border-l-4 border-orange text-orange-dark p-4" role="alert">
            <p class="font-bold">Be Warned</p>
            <p v-if="this.productID == 0">Please Add Product Information First.</p>
        </div>

        <div v-if="this.productID != 0 && this.productOptions.length == 0" class="bg-orange-lightest border-l-4 border-orange text-orange-dark p-4" role="alert">
            <p class="font-bold">Be Warned</p>
            <p >There is no Price Options added for this product.</p>
        </div>

        <options
            :productOptions = "productOptions"
            :mainOptions = "productOptions"
            :isGroupOption="false"
            :parentOptionName='false'
            :productID="productID"
            :level="1"
            ref="selectedOptions"
            @delete="submitOptions"
            @orderChange="changeOptionsOrder"
        ></options>

        <div class="float-right" style="padding-top: 30px; padding-bottom: 10px;">
            <button dusk="create-button" class="btn btn-default bg-green" v-if="productOptions.length > 0"
                    v-on:click="submitOptions" :disabled="isLoading == true">
                {{__('Save')}}
            </button>
            <button v-on:click="showCopySelectionPopup" class="btn btn-default btn-primary">Copy All Selected</button>
            <button v-on:click="deleteBulk" class="btn btn-default btn-danger">Delete All Selected</button>
        </div>

        <option-copy-popup
            v-if="copySelectionPopup"
            @close="closeCopySelectionPopup"
            :options="productOptions"
            :productID="productID"
            @refreshOptions="refreshOptions"
            @addOption="copyHere"
            @addOptionToGroup="copyToGroup"
            @copyDone="resetAllCheckboxes"
        />
    </div>
</template>

<script>
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';
    export default {
        name: "ProductOptions",
        props: ['productID'],
        components: {
            Loading
        },
        data: () => ({
            productOptions: [],
            deleteOptions: [],
            copySelectionPopup:false,
            loading: true,
            isLoading:false,
        }),
        async created() {

            if (this.productID) {
                this.getProductOptions();
            }
        },
        methods: {
            copyHere(newOptions){
                newOptions.forEach(function (option) {
                    this.productOptions.push(option);
                    this.submitOptions(false , false);
                }.bind(this));
            },
            copyToGroup(newOptions,groupId){
                let groupIndex = this.productOptions.findIndex(x => x.id == groupId);
                if(groupIndex >= 0){
                    newOptions.forEach(function (option) {
                        this.productOptions[groupIndex].sub_options.push(option);
                    }.bind(this));
                    // this.submitOptions();
                    this.submitOptions(false, false);

                }
            },
            changeOptionsOrder(options){
                this.productOptions=options;
                this.productOptions = this.reOrderOptions();
            },
            reOrderOptions(){
                let newOrderedOptions = [];
                let independentOptionIds = this.productOptions.map(elem => {
                    if(elem.type != 'group'){
                        return elem.id.toString();
                    }else{
                        return "-1";
                    }
                });
                for(let i = 0; i < this.productOptions.length; i++){
                    if(this.productOptions[i].type == 'group' && !this.productOptions[i].linked_sub_id){
                        this.productOptions[i].linked_sub_id = 0;
                    }
                    if((this.productOptions[i].type == 'group' && !independentOptionIds.includes(this.productOptions[i].linked_sub_id.toString())) || this.productOptions[i].type != 'group'){

                        if(this.productOptions[i].type == 'group' && !independentOptionIds.includes(this.productOptions[i].linked_sub_id.toString())){
                            this.productOptions[i].linked_sub_id = 0;
                        }
                        let optionId = this.productOptions[i].id;
                        newOrderedOptions.push(this.productOptions[i]);
                        let linkedGroups = this.productOptions.filter(elem => elem.linked_sub_id == optionId);
                        for(let j=0; j< linkedGroups.length; j++){
                            newOrderedOptions.push(linkedGroups[j]);
                        }
                    }
                }
                return newOrderedOptions;
            },
            deleteBulk(){
                this.$refs.selectedOptions.deleteBulk();
            },
            showCopySelectionPopup(){

                this.copySelectionPopup = true;
            },
            closeCopySelectionPopup(){
                this.copySelectionPopup = false;
            },
            refreshOptions(options){
                this.productOptions = options;
            },
            ProductOptionCard: function () {
                var data = [];
                data = {
                    'id': this.getRandomId(),
                    'type': '',
                    'secret': '',
                    'price': '',
                    'sub_options': [],
                    'constraints': [],
                    'linked_sub_count':0,
                    'is_selected': false
                };
                this.productOptions.push(data);
            },
            async submitOptions(data=false, resetView = true) {

                try {
                    this.isLoading = true;
                    this.productOptions = this.reOrderOptions();
                    let formData = new FormData();
                    formData.append('product_id', this.productID);

                    const optionsData = [];
                    if(data && data === 'All'){
                        this.productOptions = [];
                    }
                    this.resetAllCheckboxes();
                    formData.append('options',JSON.stringify(this.productOptions));
                    const response = await Nova.request().post('/nova-vendor/ProductBuilder/productOptions',
                        formData, {headers: {'Content-Type': 'multipart/form-data'}}
                    );
                    this.getProductOptions();
                    if (response) {
                        this.isLoading = false;

                        if(resetView) {
                            Nova.$emit('resetView');
                            this.$toasted.show(
                                this.__(':resource loaded!', {
                                    resource: 'Product Options',
                                }),
                                {type: 'success'}
                            );
                        }
                    } else {
                        this.$toasted.show(
                            this.__('No :resource were found', {
                                resource: 'Product Options',
                            }),
                            {type: 'success'}
                        )
                    }

                } catch (error) {
                    console.log('>>>Product Options>>' + error);
                }
            },
            async getProductOptions() {
                this.isLoading = true;
                const response = await Nova.request().get('/nova-vendor/ProductBuilder/productOptions/' + this.productID);
                if (response.data.options.options) {
                    this.productOptions = JSON.parse(response.data.options.options);
                    this.isLoading = false;
                }
            },
            getRandomId(){
                let min=10000;
                let max=99999;
                let random = 'r-'+Math.floor(Math.random() * (+max - +min));
                return random;
            },
            resetAllCheckboxes() {
                if(this.productOptions) {
                    for (let main_option of this.productOptions) {
                        this.$set(main_option, 'is_selected', false);
                        //this.resetAllCheckboxes(main_option.sub_options);
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>

<style>
    /*.option-level-1 tr td{*/
    /*background-color:white;*/
    /*}*/

    /*.option-level-2 tr td{*/
    /*background-color:whitesmoke;*/
    /*}*/

    /*.option-level-3 tr td{*/
    /*background-color:lightgray;*/
    /*}*/

    /*.option-level-4 tr td{*/
    /*background-color:lightgoldenrodyellow;*/
    /*}*/

</style>

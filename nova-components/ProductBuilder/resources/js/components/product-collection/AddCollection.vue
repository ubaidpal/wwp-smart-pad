<template>
    <!-- Add Product to Collection Modal -->
    <modal v-if="addCollection" @close="addCollection = false">
        <div class="bg-white rounded-lg shadow-lg overflow-visible" style="width: 460px">
            <form  @submit.prevent="addProductCollection">
                <div class="p-8">
                    <heading v-if="this.ProductID == 0" :level="2" class="mb-6">Create Collection</heading>
                    <heading v-else="this.ProductID > 0" :level="2" class="mb-6">Add Product To Collection</heading>
                </div>
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" >
                    <div>
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0" v-if="this.ProductID == 0">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">
                                Choose Brand
                            </label>

                            <div class="relative">
                                <!----> <select data-testid="brands-select" dusk="brands" class="form-control form-select mb-3 w-full" v-model="BrandID" :disabled="productToAddId.length > 0" @change="reloadProducts">
                                <option value="" disabled="disabled" selected="selected">Choose Brand First</option>
                                <option v-if="brands" v-for="brand in brands" :value="brand.id">{{brand.name}}</option>
                            </select>
                            </div>
                        </div>
                        <div class="md:flex md:items-center">
                            <div class="md:w-1/3"></div>
                        </div>
                    </div>
                    <div v-if="BrandID>0 || BrandID == -1">
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0" >
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">
                                Collection Name
                            </label>

                            <div class="relative">
                                <input v-if="this.ProductID == 0" v-model="collect_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline" id="collection_name" type="text" placeholder="Enter Name">
                                <select v-if="this.ProductID > 0" v-model="collect_name" id="collect_name" name="movie" class="block appearance-none w-full bg-grey-lighter border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-grey">
                                    <option data-v-73ca7226="" value="">Choose Collection Name</option>
                                    <option v-for="collect in collections" :value="collect.id">{{collect.name}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0" v-if="ProductID == 0">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">
                                Choose Products
                            </label>

                            <div class="relative">
                                <multiselect v-model="productToAddId"
                                             placeholder="Search or Select Product"
                                             label="name"
                                             track-by="id"
                                             :required="true"
                                             :options="options"
                                             :multiple="true"
                                             :preselect-first="false"
                                             :taggable="false"
                                ></multiselect>
                            </div>
                        </div>
                        <div class="md:flex md:items-center">
                            <div class="md:w-1/3"></div>
                        </div>
                    </div>

                </div>

                <div class="bg-30 px-6 py-3 flex">

                    <div class="ml-auto">
                        <button type="button" @click="closeModelEvent" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Close')}}</button>
                        <input type="submit" class="btn btn-default btn-primary rounded" value="Add Product"/>
                    </div>
                </div>
            </form>
        </div>
    </modal>
</template>

<script>
    import Multiselect from 'vue-multiselect';

    export default {
        name: "AddCollection",
        props: ['BrandID','ProductID'],
        data: () => ({
            brands:[],
            addCollection:true,
            productToAddId:[],
            options:[],
            collections:[],
            collect_name:''

        }),
        methods:{
            closeModelEvent() {
                this.ProductID = 0;
                this.BrandID = 0;
                this.$emit('close');
            },
            /*
            * It will create new collection and add product in that collection
            */
            async addProductCollection(){

                let formData = new FormData();
                if(this.ProductID > 0){
                    formData.append('product_id', this.ProductID);
                    formData.append('collection_id', this.collect_name);
                } else {
                    var ProductIds = [];
                    for(let i=0; i< this.productToAddId.length; i++){
                        if(this.productToAddId[i].id > 0){
                            ProductIds.push(this.productToAddId[i].id)
                        }
                    }
                    formData.append('product_id', ProductIds);
                    formData.append('brand_id', this.BrandID);
                    formData.append('collection_name', this.collect_name);
                }

                const response = await Nova.request().post('/nova-vendor/ProductBuilder/addProductCollection',
                    formData
                );
                if(response.data.success){
                    this.$toasted.show('Products Collection Successfully!', { type: 'success' })
                }else{
                    this.$toasted.show('Products Collection Failed!', { type: 'error' })
                }
                this.closeModelEvent();
                this.$emit('add');

            },
            async reloadProducts(){
                this.options = [];
                await Nova.request().get(`/nova-vendor/ProductBuilder/productsToAdd?brand_id=`+this.BrandID).then((response) => {
                    for(let i=0; i< response.data.length; i++){
                        this.options.push({'name':response.data[i].name,'id':response.data[i].id});
                    }
                });
            }

        },
        async created(){
            // Get all product of the brand
            console.log(this.ProductID);
            console.log(this.BrandID);
            if(this.ProductID > 0){
                await Nova.request().get(`/nova-vendor/ProductBuilder/getProductCollections/`+this.ProductID).then((response) => {
                    this.collections = response.data;
                    console.log(this.collections );
                });
            } else {
                this.reloadProducts();
            }
            // if(this.BrandID > 0){
            // }else{
                // Get Brand List
                await Nova.request().get(`/nova-vendor/ProductBuilder/getBrandList`).then((response) => {
                    this.brands = response.data.brand;
                });
            //}
        },
        components: {
            Multiselect,
        },
    }
</script>

<style scoped>

</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

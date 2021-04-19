<template>
    <modal v-if="showAddModal" @close="showAddModal = false">

        <div class="bg-white rounded-lg shadow-lg overflow-hidden"
             style="width: 750px">
            <form
                    @submit.prevent="addBrand"
                    id="app"
                    method="post"
                    novalidate="true"
                    name="brandForm"
            >
                <div class="p-8">

                    <heading :level="2" class="mb-6" >Add Brand </heading>
                </div>

                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <div class="mb-4">
                       <p v-if="errors.length">
                            <b>Please correct the following error(s):</b>
                        <ul class="error_color">
                            <li  v-for="error in errors">{{ error }}</li>
                        </ul>
                        </p>

                        <p >
                            <label class="block text-grey-darker text-sm font-bold mb-2" for="name">
                                Name
                            </label>

                            <input
                                    type="text"
                                    v-model="newBrand.brand_name"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
                                    placeholder="Enter Brand Name"
                                    required>
                        </p>

                        <p>
                            <label class="block text-grey-darker text-sm font-bold mb-2" for="movie">
                                Type
                            </label>
                            <select
                                    id="type"
                                    v-model="newBrand.type"
                                    class="block appearance-none w-full bg-grey-lighter border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-grey"
                            >
                                <option value="">Choose an option</option>
                                <option  :value="1">Exclusive</option>
                                <option  :value="0">Non Exclusive</option>
                            </select>
                        </p>
                        <!--<p>-->
                            <!--<label class="block text-grey-darker text-sm font-bold mb-2" for="movie">-->
                                <!--Select Country-->
                            <!--</label>-->
                            <!--<select-->
                                    <!--id="countries_name"-->
                                    <!--v-model="newBrand.countries_name"-->
                                    <!--class="block appearance-none w-full bg-grey-lighter border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-grey"-->
                            <!--&gt;-->
                                <!--<option value="">Choose Country</option>-->
                                <!--<option v-for="Country in countries" :value="Country.id">{{Country.name}}</option>-->
                            <!--</select>-->
                        <!--</p>-->

                        <!--<p>-->
                            <!--<label class="block text-grey-darker text-sm font-bold mb-2" for="movie">-->
                                <!--Status-->
                            <!--</label>-->
                            <!--<input-->
                                    <!--type="checkbox"-->
                                    <!--v-model="newBrand.active"-->
                                    <!--true-value="1"-->
                                    <!--false-value="0"-->

                            <!--&gt;-->

                        <!--</p>-->

                        <!--<p>-->
                            <!--<label class="block text-grey-darker text-sm font-bold mb-2" for="movie">-->
                                <!--Fabric-->
                            <!--</label>-->
                            <!--<input-->
                                    <!--type="checkbox"-->
                                    <!--v-model="newBrand.is_fabric"-->
                                    <!--true-value="1"-->
                                    <!--false-value="0"-->

                            <!--&gt;-->

                        <!--</p>-->
                        <p >
                            <label class="block text-grey-darker text-sm font-bold mb-2" for="name">
                                Comments
                            </label>
                            <textarea  v-model="newBrand.comments" id="comment" dusk="comment" rows="5" class="w-full form-control form-input form-input-bordered py-3 h-auto"></textarea>

                        </p>

                    </div>

                </div>

                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <button type="button"  @click="closeModelEvent" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}</button>

                        <button class="btn btn-default btn-primary "
                        >{{__('Add Brand')}}
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </modal>
</template>

<script>
    export default {
        name: "brand.vue",
        props:[
            'isFabric',
            'country',
            'is_active'
        ],
        data: () => ({
            showAddModal:true,
            countries:[],
            brands:[],
            errors: [],
            countries_name: '',
            newBrand : {
                'brand_name': '',
                'is_active': '1',
                'type': '1',
                'countries_name': '0',
                'is_fabric': '0',
                'comments': '',
            }
        }),
        methods: {
            closeModelEvent() {
                this.$emit('close');
            },
            async addBrand(){

                const response = await Nova.request().post('/nova-vendor/ProductBuilder/addNewBrand',
                    this.newBrand
                );
                if(response.data.success) {
                    this.$emit('brandAdded',response.data.message);

                    this.newBrand = {
                        'brand_name': '',
                        'is_active': '1',
                        'type': '0',
                        'countries_name': '0',
                        'is_fabric': '0',
                        'comments': '',
                    };

                    this.$toasted.show(
                        this.__('The :Brand are added!',{
                            resource: 'Brand',
                        }),
                        {type: 'success'}
                    );
                    this.showAddModal = false;
                    this.closeModelEvent();

                }
            },

        },
        async created(){
            // const response = await Nova.request().get('/nova-vendor/ProductBuilder/getActiveCountry');
            // this.countries = response.data.countries;
            this.newBrand.countries_name = this.country.id;
            this.newBrand.is_fabric = this.isFabric;
            this.newBrand.is_active = this.is_active;

        }
    }
</script>

<style scoped>
    .error_color{
        color: #f5573b;
    }


</style>

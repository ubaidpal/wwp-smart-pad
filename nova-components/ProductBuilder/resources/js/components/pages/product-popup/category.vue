<template>
    <modal v-if="showAddModal" @close="showAddModal = false">

        <div class="bg-white rounded-lg shadow-lg overflow-hidden"
             style="width: 750px">
            <form
                    @submit.prevent="addCategory"
                    id="app"
                    method="post"
                    novalidate="true"
                    name="category"
            >
                <div class="p-8">

                    <heading :level="2" class="mb-6" >Add Category </heading>
                </div>

                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <div class="mb-4">



                        <p >
                            <label class="block text-grey-darker text-sm font-bold mb-2" for="name">
                                Name
                            </label>

                            <input
                                    type="text"
                                    v-model="newCategory.category_name"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline"
                                    placeholder="Enter Category Name"
                                    required>
                        </p>


                        <!--<p>-->
                            <!--<label class="block text-grey-darker text-sm font-bold mb-2" for="movie">-->
                                <!--Status-->
                            <!--</label>-->
                            <!--<input-->
                                    <!--type="checkbox"-->
                                    <!--v-model="newCategory.status"-->
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
                                    <!--v-model="newCategory.is_fabric"-->
                                    <!--true-value="1"-->
                                    <!--false-value="0"-->

                            <!--&gt;-->

                        <!--</p>-->


                    </div>

                </div>

                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <button type="button"  @click="closeModelEvent" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}</button>

                        <button class="btn btn-default btn-primary "
                        >{{__('Add Category')}}
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </modal>
</template>

<script>
    export default {
        name: "category.vue",
        props:[
            'isFabric',
            'is_active'
        ],
        data: () => ({
            showAddModal:true,
            newCategory :{
                'category_name': '',
                'is_active': '0',
                'is_fabric': '0',
            },
            errors: [],
        }),
        methods: {
            closeModelEvent() {
                this.$emit('close');
            },
            async addCategory(){

                    const response = await Nova.request().post('/nova-vendor/ProductBuilder/addNewCategory',
                        this.newCategory
                    );
                    if(response.data.success) {
                        this.$emit('categoryAdded',response.data.message);
                        this.newCategory = {
                            'category_name': '',
                            'is_active': '1',
                            'is_fabric': '0',
                        };

                        this.$toasted.show(
                            this.__('The :Category are added!',{
                                resource: 'Category',
                            }),
                            {type: 'success'}
                        );
                        this.closeModelEvent();
                        this.showAddModal = false;
                    }



            },
        },
        async created(){
            this.newCategory.is_active = this.is_active;
            this.newCategory.is_fabric = this.isFabric;
        }
    }
</script>

<style scoped>

    .error_color{
        color: #f5573b;
    }

</style>

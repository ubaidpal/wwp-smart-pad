<template>
    <li>
        <div>
            <span v-if="!open && parent" @click="toggle(model.id)">[{{ open ? '-' : '+' }}]</span>
            <span v-if="!model.collections" >{{ model.name }} </span>
            <span v-if="model.collections">{{ model.collections.length }} Collection(s) </span>
            <span v-if="model.products">( {{ model.products.length }} Products) </span>
            <span v-if="isFolder" @click="toggle">[{{ open ? '-' : '+' }}]</span>
            <span v-if="model.collections">
                <a href="javascript:void(0)" title="Add Collection" v-on:click="addCollectionEvent(model)" class="cursor-pointer text-70 hover:text-primary spp-save"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" class="fill-current" viewBox="0 0 20 20"><path d="M0 4c0-1.1.9-2 2-2h7l2 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2 2v10h16V6H2zm7 4V8h2v2h2v2h-2v2H9v-2H7v-2h2z"/></svg></a>
            </span>
            <span v-if="model.products">
                <a href='javascript:void(0)' @click="edit(model)" class="cursor-pointer text-70 hover:text-primary spp-save" title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current"><path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path></svg>
                </a>
            </span>
            <span v-if="(model.products && model.products.length == 0) || (model.collection_id)">
                <a href="javascript:void(0)"  @click="deleteEvent(model)" data-testid="products-items-0-delete-button" title="Delete" class="appearance-none cursor-pointer text-70 hover:text-primary spp-delete"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg>
                </a>
            </span>
            <span v-if="model.products">
            <a href="javascript:void(0)"  @click="addProduct(model)" data-testid="products-items-0-delete-button" title="Add Product" class="appearance-none cursor-pointer text-70 hover:text-primary spp-copy-clone">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 20 20" class="fill-current" ><path d="M15 9h-3v2h3v3h2v-3h3V9h-3V6h-2v3zM0 3h10v2H0V3zm0 8h10v2H0v-2zm0-4h10v2H0V7zm0 8h10v2H0v-2z"/></svg>
            </a>
            </span>
        </div>

        <ul v-if="model.collections" v-show="open" >
            <product-collection-item
                class="item"
                v-for="(model, index) in model.collections"
                :key="index"
                :model="model"
                v-on:edit="edit"
                v-on:delete="deleteEvent"
                v-on:addProduct="addProduct"
            >
            </product-collection-item>

        </ul>

        <ul v-if="model.products" v-show="open" >
            <product-collection-item
                class="item"
                v-for="(model, index) in model.products"
                :key="index"
                :model="model"
                v-on:edit="edit"
                v-on:delete="deleteEvent"

            >
            </product-collection-item>

        </ul>

    </li>
</template>

<script>
    export default {
        name: "product-collection-item",
        resourceName: {
            type: String,
        },
        data: () => ({
            open: false,
        }),
        props:{
            model:Object,
            parent: true
        },

        mounted()
        {
            // this.get(this.model.id);
        },
        methods: {
            async get(id)
            {
                await Nova.request().get(`/nova-vendor/ProductBuilder/brand/collections/`+id).then((response) => {
                    this.model = response.data;
                    this.$emit('loadData',response.data);
                });
            },
            /*
            * To throw the add collection Event
            * */
            addCollectionEvent(model){
                this.$emit('addCollection',model);
            },
            /*
            * To throw the Edit collection Event
            * */
            edit(model){
                this.$emit('edit',model);
            },
            /*
            * To throw the Delete Product or Collection Event
            * */
            deleteEvent(model){
                this.$emit('delete',model);
            },
            /*
            * Throw add product to collection Event
            * */
            addProduct(model){
              this.$emit('addProduct',model);
            },
            /*
            * Toggle the tree
            * */
            toggle: function (id) {
                this.open = !this.open;
                if(this.open && this.parent){
                    this.parent = false;
                    this.get(id);
                }  
            }
        },
        computed: {
            isFolder: function () {
                return (this.model.collections &&
                    this.model.collections.length) || (this.model.products &&
                    this.model.products.length);
            }
        },
        created(){
            if(this.model.collections){
                this.open = true;
            }
        }
    }
</script>
<style scoped>
    .item {
        cursor: pointer;
    }
    .bold {
        font-weight: bold;
    }
    ul {
        padding-left: 1em;
        line-height: 1.5em;
        list-style-type: dot;
    }

</style>

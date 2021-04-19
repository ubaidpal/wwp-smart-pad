<template>
    <li>
        <div>
            <span :class="{bold: isFolder}" v-if="model.username">{{ model.username}}</span>
            <!--<span :class="{bold: isFolder}" v-if="model.employees">{{ model.employees.username}}</span>-->
            <span :class="{bold: isFolder}" v-else-if="model.company">{{ model.company.name}}</span>

            <span v-if="isFolder" @click="toggle">[{{ open ? '-' : '+' }}]</span>
        </div>

        <ul v-if="isFolder" v-show="open" >
            <tree-item
                class="item"
                v-for="(model, index) in model.employees"
                :key="index"
                :model="model"

            >
            </tree-item>

            <tree-item
                class="item"
                v-for="(model, index) in model.company_info"
                :key="index"
                :model="model"
            >
            </tree-item>

        </ul>


    </li>
</template>

<script>
    export default {
        name: "tree-item",
        resourceName: {
            type: String,
        },
        data: () => ({
            open: false,

        }),
        props:{
            model:Object
        },
        methods: {
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
            toggle: function () {
                if (this.isFolder) {
                    this.open = !this.open
                }
            },
        },
        computed: {
            isFolder: function () {
                return (this.model.employees &&
                    this.model.employees.length) || (this.model.company_info &&
                    this.model.company_info.length);
            }
        },
        created(){
            if(this.model.employees){
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

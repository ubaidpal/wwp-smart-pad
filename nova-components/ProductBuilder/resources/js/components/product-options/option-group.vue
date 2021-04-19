<template>
    <div>
        <!--<label>{{ subOptions }}</label>-->
        <table class="table w-full">
            <thead>
            <tr>
                <th>&nbsp;</th>
                <th>Option Name</th>
                <th>Type</th>
                <th>Secert</th>
                <th>Price %</th>
                <th style="width: 100px;">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(g,key) in subOptions">
                <td>
                    <button
                        class="appearance-none cursor-pointer text-70 hover:text-primary mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20"
                             class="fill-current">
                            <path class="heroicon-ui"
                                  d="M20 6a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h7.41l2 2H20zM4 6v12h16V8h-7.41l-2-2H4zm9 6h2a1 1 0 0 1 0 2h-2v2a1 1 0 0 1-2 0v-2H9a1 1 0 0 1 0-2h2v-2a1 1 0 0 1 2 0v2z"/>
                        </svg>
                    </button>
                    <!--<button v-if="showSub == key" @click="hideSuboptions()"-->
                    <!--class="appearance-none cursor-pointer text-70 hover:text-primary mr-3">-->
                    <!--<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20"-->
                    <!--class="fill-current">-->
                    <!--<path class="heroicon-ui"-->
                    <!--d="M20 6a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h7.41l2 2H20zM4 6v12h16V8h-7.41l-2-2H4zm12 7a1 1 0 0 1-1 1H9a1 1 0 0 1 0-2h6a1 1 0 0 1 1 1z"/>-->
                    <!--</svg>-->
                    <!--</button>-->
                </td>
                <td>
                    <input v-model='g.options_name' name="options_name"
                           class="w-full form-control form-input form-input-bordered"/>
                </td>
                <td>
                    <select v-model="g.type" id="type" name="type"
                            class="form-control" style="width:150px">
                        <option v-for="type in optionTypes" :value="type.name" >{{type.label}}</option>
                    </select>
                </td>
                <td>
                    <select v-model="g.secret" :multiple="true" name="secret" class="example-getting-started">
                        <option value="S">Hide On Screen</option>
                        <option value="Q">Hide On Quote</option>
                        <option value="O">Hide On Order</option>
                        <option value="I">Hide On Install &amp; Measure</option>
                    </select>
                </td>
                <td>
                    <input v-model="g.price" name="price" type="text"
                           class="w-full form-control form-input form-input-bordered">
                </td>
                <td>
                    <button title="Add Sub Option" @click="addProductSubCard(p.sub_options,key,p)"
                            class="appearance-none cursor-pointer text-70 hover:text-primary mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                             aria-labelledby="add" role="presentation" class="fill-current">
                            <path class="heroicon-ui"
                                  d="M6 2h9a1 1 0 0 1 .7.3l4 4a1 1 0 0 1 .3.7v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2zm9 2.41V7h2.59L15 4.41zM18 9h-3a2 2 0 0 1-2-2V4H6v16h12V9zm-5 4h2a1 1 0 0 1 0 2h-2v2a1 1 0 0 1-2 0v-2H9a1 1 0 0 1 0-2h2v-2a1 1 0 0 1 2 0v2z"/>
                        </svg>
                    </button>
                    <button @click="deleteOption(key)" dusk="delete-button" title="Delete Option"
                            class="appearance-none cursor-pointer text-70 hover:text-primary mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                             aria-labelledby="delete" role="presentation" class="fill-current">
                            <path fill-rule="nonzero"
                                  d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path>
                        </svg>
                    </button>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <component v-bind:is="g.type"
                               :option="key"
                               :subOptions="g.sub_options"
                               v-on:input="triggerChange">
                    </component>
                </td>
            </tr>

            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "option-group",
        props: ['option', 'subOptions'],
        data: () => ({
            optionTypes:[
                { is_sub_allowed:true,name:"option", label:"Option"},
                { is_sub_allowed:true,name:"multi",label:"Multi Select"},
                { is_sub_allowed:true,name:"group",label:"Group"},
                { is_sub_allowed:false,name:"size",label:"Size"},
                { is_sub_allowed:false,name:"text",label:"Text"},
                { is_sub_allowed:false,name:"price",label:"Price"},
                { is_sub_allowed:false,name:"text_only",label:"Text Only"},
                { is_sub_allowed:true,name:"display",label:"Display"},
                { is_sub_allowed:true,name:"fabric_quantity",label:"Fabric Quantity"},
                { is_sub_allowed:true,name:"fabric_quantity_not_edit",label:"Fabric Quantity Not Editable"},
                { is_sub_allowed:true,name:"fabric",label:"All Fabrics"},
                { is_sub_allowed:true,name:"trim",label:"Trim Selection"},
                { is_sub_allowed:true,name:"lining",label:"Lining Selection"},
                { is_sub_allowed:true,name:"accessory",label:"Accessory Selection"},
                { is_sub_allowed:true,name:"cost_price",label:"Cost Price"},
                { is_sub_allowed:true,name:"manufactoring",label:"Manufactoring"}
            ],
        }),
    }
</script>

<style scoped>

</style>
